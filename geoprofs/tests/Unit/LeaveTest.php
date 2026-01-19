<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_leave_belongs_to_user()
    {
        $user = User::factory()->create();
        $leave = new Leave([
            'leave_start' => now(),
            'leave_end' => now()->addDays(1),
            'type' => 'verlof',
            'reason' => 'Test',
            'status' => 'pending',
        ]);
        $leave->user()->associate($user);
        $leave->save();

        $this->assertInstanceOf(User::class, $leave->user);
        $this->assertEquals($user->id, $leave->user->id);
    }
}
