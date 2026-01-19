<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Leave;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_leaves()
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

        $this->assertInstanceOf(Leave::class, $user->leaves->first());
        $this->assertEquals($leave->id, $user->leaves->first()->id);
    }
}
