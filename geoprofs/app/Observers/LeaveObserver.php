<?php

namespace App\Observers;

use Illuminate\Support\Facades\DB;
use App\Models\Leave;

class LeaveObserver
{
    /**
     * Handle the Leave "created" event.
     */
    public function created(Leave $leave): void
    {
        //
    }

    /**
     * Handle the Leave "updated" event.
     */


public function updated(Leave $leave)
{
    if($leave->status === 'approved' && $leave->getOriginal('status') !== 'approved') {
        $user = $leave->user;
        $days = \Carbon\Carbon::parse($leave->leave_start)
            ->diffInDays(\Carbon\Carbon::parse($leave->leave_end)) + 1;

        $user->leave_balance = max($user->leave_balance - $days, 0);
        $user->save();
    }
}


    /**
     * Handle the Leave "deleted" event.
     */
    public function deleted(Leave $leave): void
    {
        //
    }

    /**
     * Handle the Leave "restored" event.
     */
    public function restored(Leave $leave): void
    {
        //
    }

    /**
     * Handle the Leave "force deleted" event.
     */
    public function forceDeleted(Leave $leave): void
    {
        //
    }
}
