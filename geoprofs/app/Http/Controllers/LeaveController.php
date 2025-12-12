<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

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
}
