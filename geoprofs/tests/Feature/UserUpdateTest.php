<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_age_and_functie()
    {
        $user = User::factory()->create([
            'age' => 25,
            'functie' => 'employee',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('user.update'), [
            'age' => 30,
            'functie' => 'manager',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals(30, $user->age);
        $this->assertEquals('manager', $user->functie);
    }

    public function test_user_update_validation()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('user.update'), [
            'age' => 150, // Invalid age
            'functie' => str_repeat('a', 300), // Too long functie
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['age', 'functie']);
    }

    public function test_account_page_accessible()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('account'));

        $response->assertStatus(200);
        $response->assertSee('Accountgegevens');
    }
}
