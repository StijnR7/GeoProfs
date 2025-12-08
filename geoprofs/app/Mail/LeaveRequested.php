<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Leave;

class LeaveRequested extends Mailable
{
    use Queueable, SerializesModels;

    public Leave $leave;

    /**
     * Create a new message instance.
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New leave request')
                    ->view('emails.leave_requested')
                    ->with(['leave' => $this->leave]);
    }
}
