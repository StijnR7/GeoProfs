<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_leave_balance_decreases_on_approval()
    {
        $user = User::factory()->create(['leave_balance' => 10]);
        $leave = new Leave([
            'leave_start' => now(),
            'leave_end' => now()->addDays(2),
            'type' => 'verlof',
            'reason' => 'Test',
            'status' => 'pending',
        ]);
        $leave->user()->associate($user);
        $leave->save();

        $leave->status = 'approved';
        $leave->save();

        $user->refresh();
        $this->assertEquals(7, $user->leave_balance);
    }

    public function test_leave_balance_not_decreases_for_non_verlof()
    {
        $user = User::factory()->create(['leave_balance' => 10]);
        $leave = new Leave([
            'leave_start' => now(),
            'leave_end' => now()->addDays(2),
            'type' => 'ziek',
            'reason' => 'Test',
            'status' => 'pending',
        ]);
        $leave->user()->associate($user);
        $leave->save();

        $leave->status = 'approved';
        $leave->save();

        $user->refresh();
        $this->assertEquals(10, $user->leave_balance);
    }
}
