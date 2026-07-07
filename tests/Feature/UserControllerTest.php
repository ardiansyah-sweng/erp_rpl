<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_list_page_can_be_rendered()
    {
        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Kelola User');
    }

    public function test_user_create_page_can_be_rendered()
    {
        $response = $this->get('/users/add');

        $response->assertStatus(200);
        $response->assertSee('Form Tambah User');
    }

    public function test_can_add_user_successfully_to_database()
    {
        $response = $this->post('/users/add', [
            'name' => 'Feature Test User',
            'email' => 'feature.test.user@erp.test',
            'password' => 'password123',
            'role' => UserRole::STAFF->value,
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'feature.test.user@erp.test',
            'name' => 'Feature Test User',
            'role' => UserRole::STAFF->value,
        ]);
    }

    public function test_add_user_validation_fails_if_fields_are_empty()
    {
        $response = $this->post('/users/add', []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_add_user_validation_fails_on_duplicate_email()
    {
        User::addUser([
            'name' => 'Existing User',
            'email' => 'duplicate.user@erp.test',
            'password' => 'password123',
            'role' => UserRole::STAFF->value,
        ]);

        $response = $this->post('/users/add', [
            'name' => 'Another User',
            'email' => 'duplicate.user@erp.test',
            'password' => 'password123',
            'role' => UserRole::STAFF->value,
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_can_update_user_role()
    {
        $user = User::addUser([
            'name' => 'Role Change User',
            'email' => 'role.change.user@erp.test',
            'password' => 'password123',
            'role' => UserRole::STAFF->value,
        ]);

        $response = $this->put("/users/{$user->id}", [
            'name' => 'Role Change User',
            'email' => 'role.change.user@erp.test',
            'password' => '',
            'role' => UserRole::ADMIN->value,
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertTrue($user->fresh()->isAdmin());
    }

    public function test_can_delete_user()
    {
        $user = User::addUser([
            'name' => 'Delete Me',
            'email' => 'delete.me@erp.test',
            'password' => 'password123',
            'role' => UserRole::STAFF->value,
        ]);

        $response = $this->delete("/users/{$user->id}");

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
