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

    public function test_user_fillable_attributes()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'leave_balance' => 20,
            'functie' => 'employee',
            'age' => 30,
        ]);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals(20, $user->leave_balance);
        $this->assertEquals('employee', $user->functie);
        $this->assertEquals(30, $user->age);
    }
}
