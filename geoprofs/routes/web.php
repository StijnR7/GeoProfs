<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LeaveController;

Route::get('/', function () {
    return view('welcome');
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
    $userLeaves = Auth::user()->leaves()->orderBy('created_at', 'desc')->get();
    return view('dashboard', ['userLeaves' => $userLeaves]);
})->middleware('auth')->name('dashboard');


Route::post('/leave/submit', [LeaveController::class, 'store'])
    ->middleware('auth')
    ->name('leave.submit');


Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');
