<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_page_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        User::addUser([
            'name' => 'Login Tester',
            'email' => 'login.tester@erp.test',
            'password' => 'password123',
            'role' => UserRole::STAFF->value,
        ]);

        $response = $this->post('/login', [
            'email' => 'login.tester@erp.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs(User::where('email', 'login.tester@erp.test')->first());
    }

    public function test_login_fails_with_wrong_password()
    {
        User::addUser([
            'name' => 'Login Tester',
            'email' => 'login.tester2@erp.test',
            'password' => 'password123',
            'role' => UserRole::STAFF->value,
        ]);

        $response = $this->post('/login', [
            'email' => 'login.tester2@erp.test',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_login_fails_for_unknown_email()
    {
        $response = $this->post('/login', [
            'email' => 'unknown.user@erp.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout()
    {
        $user = User::addUser([
            'name' => 'Logout Tester',
            'email' => 'logout.tester@erp.test',
            'password' => 'password123',
            'role' => UserRole::STAFF->value,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_dashboard_requires_login()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_logged_in_user_can_see_dashboard()
    {
        $user = User::addUser([
            'name' => 'Dashboard Tester',
            'email' => 'dashboard.tester@erp.test',
            'password' => 'password123',
            'role' => UserRole::STAFF->value,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }
}
