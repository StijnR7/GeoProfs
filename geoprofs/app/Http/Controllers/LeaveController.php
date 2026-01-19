<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateLeaveRequest;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function store(CreateLeaveRequest $request)
    {
        $leave = new Leave([
            'leave_start' => $request->leave_start,
            'leave_end' => $request->leave_end,
            'type' => $request->type,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        $leave->user()->associate(Auth::user());
        $leave->save();

        return back()->with('success', 'Aanvraag succesvol ingediend!');
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $user = $leave->user;

        if ($leave->type === 'verlof') {
            $days = $leave->leave_start->diffInDays($leave->leave_end) + 1;

            if ($user->leave_balance >= $days) {
                $leave->status = 'approved';
                $leave->save();
                return back()->with('success', 'Verlofaanvraag goedgekeurd. ' . $days . ' dagen afgetrokken.');
            } else {
                return back()->with('error', 'Onvoldoende verlofdagen beschikbaar.');
            }
        } else {
            // Voor ziek, gewoon goedkeuren zonder aftrek
            $leave->status = 'approved';
            $leave->save();
            return back()->with('success', 'Ziekaanvraag goedgekeurd.');
        }
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->status = 'rejected';
        $leave->save();

        return back()->with('success', 'Aanvraag afgewezen.');
    }
}
