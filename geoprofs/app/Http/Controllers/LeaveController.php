<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave; 
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'leave_start' => 'required|date',
            'leave_end' => 'required|date|after_or_equal:leave_start',
            'type' => 'required|in:ziek,verlof',
            'reason' => 'nullable|string|max:255',
        ]);

        Leave::create([
            'user_id' => Auth::id(),
            'leave_start' => $request->leave_start,
            'leave_end' => $request->leave_end,
            'type' => $request->type,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Aanvraag succesvol ingediend!');
    }
}
