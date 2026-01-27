<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LeaveController;
use App\Models\Leave;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'De opgegeven inloggegevens zijn niet correct.',
    ]);
})->name('login.post');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/my-requests', function () {
    $userLeaves = Auth::user()->leaves()->orderBy('created_at', 'desc')->get();
    return view('my-requests', compact('userLeaves'));
})->middleware('auth')->name('my-requests');

Route::get('/leave-days', function () {
    $user = Auth::user();
    $totalLeaveBalance = $user->leave_balance;

    // Calculate used days for each type
    $usedVacationDays = $user->leaves()
        ->where('status', 'approved')
        ->where('type', 'verlof')
        ->get()
        ->sum(function ($leave) {
            $start = new DateTime($leave->leave_start);
            $end = new DateTime($leave->leave_end);
            $interval = $start->diff($end);
            return $interval->days + 1; // +1 to include both start and end dates
        });

    $usedSickDays = $user->leaves()
        ->where('status', 'approved')
        ->where('type', 'ziek')
        ->get()
        ->sum(function ($leave) {
            $start = new DateTime($leave->leave_start);
            $end = new DateTime($leave->leave_end);
            $interval = $start->diff($end);
            return $interval->days + 1; // +1 to include both start and end dates
        });

    // Calculate remaining vacation days
    $remainingVacationDays = $totalLeaveBalance - $usedVacationDays;

    return view('leave-days', compact('totalLeaveBalance', 'remainingVacationDays', 'usedVacationDays', 'usedSickDays'));
})->middleware('auth')->name('leave-days');

Route::get('/department-calendar', function () {
    // Get all approved leaves with user information
    $approvedLeaves = Leave::where('status', 'approved')
        ->with('user')
        ->get()
        ->map(function ($leave) {
            return [
                'id' => $leave->id,
                'user_name' => $leave->user->name,
                'functie' => $leave->user->functie,
                'type' => $leave->type,
                'leave_start' => $leave->leave_start->format('Y-m-d'),
                'leave_end' => $leave->leave_end->format('Y-m-d'),
            ];
        });

    return view('department-calendar', compact('approvedLeaves'));
})->middleware('auth')->name('department-calendar');

Route::get('/manage-requests', function () {
    if (Auth::user()->functie !== 'admin') {
        abort(403);
    }
    $pendingLeaves = Leave::where('status', 'pending')->with('user')->get();
    return view('manage-requests', compact('pendingLeaves'));
})->middleware('auth')->name('manage-requests');


Route::post('/leave/submit', [LeaveController::class, 'store'])
    ->middleware('auth')
    ->name('leave.submit');

Route::post('/leave/{id}/approve', [LeaveController::class, 'approve'])
    ->middleware('auth')
    ->name('leave.approve');

Route::post('/leave/{id}/reject', [LeaveController::class, 'reject'])
    ->middleware('auth')
    ->name('leave.reject');


Route::post('/user/update', [UserController::class, 'update'])
    ->middleware('auth')
    ->name('user.update');

Route::get('/account', function () {
    return view('account');
})->middleware('auth')->name('account');


Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');
