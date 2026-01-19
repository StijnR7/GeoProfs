<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Leave;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_leave_request()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/leave/submit', [
            'leave_start' => now()->format('Y-m-d'),
            'leave_end' => now()->addDays(1)->format('Y-m-d'),
            'type' => 'verlof',
            'reason' => 'Test reason',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('leaves', [
            'user_id' => $user->id,
            'type' => 'verlof',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_leave_request()
    {
        $admin = User::factory()->create(['functie' => 'admin', 'leave_balance' => 10]);
        $user = User::factory()->create(['leave_balance' => 5]);
        $leave = new Leave([
            'leave_start' => now(),
            'leave_end' => now()->addDays(2),
            'type' => 'verlof',
            'reason' => 'Test',
            'status' => 'pending',
        ]);
        $leave->user()->associate($user);
        $leave->save();

        $this->actingAs($admin);

        $response = $this->post("/leave/{$leave->id}/approve");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('leaves', [
            'id' => $leave->id,
            'status' => 'approved',
        ]);
        $user->refresh();
        $this->assertEquals(2, $user->leave_balance); 
    }

    public function test_admin_can_reject_leave_request()
    {
        $admin = User::factory()->create(['functie' => 'admin']);
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

        $this->actingAs($admin);

        $response = $this->post("/leave/{$leave->id}/reject");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('leaves', [
            'id' => $leave->id,
            'status' => 'rejected',
        ]);
    }

    public function test_dashboard_shows_user_leaves()
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

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($leave->reason);
    }
}
