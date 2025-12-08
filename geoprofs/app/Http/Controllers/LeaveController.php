<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequested;

class LeaveController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $leave = Leave::create([
            'user_id' => $request->user()->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => 'pending',
        ]);

        // Send notification email to admin if ADMIN_EMAIL is configured
        $admin = config('mail.admin_address') ?? env('ADMIN_EMAIL');
        if ($admin) {
            try {
                Mail::to($admin)->send(new LeaveRequested($leave));
            } catch (\Exception $e) {
                // swallow mail errors for now — could log
            }
        }

        return redirect()->back()->with('success', 'Leave request submitted.');
    }
}
