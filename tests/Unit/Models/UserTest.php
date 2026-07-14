<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Enums\UserRole;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_add_user_hashes_password_and_casts_role()
    {
        $user = User::addUser([
            'name' => 'Test User',
            'email' => 'unittest.user@erp.test',
            'password' => 'secret123',
            'role' => UserRole::ADMIN->value,
        ]);

        $this->assertNotEquals('secret123', $user->getRawOriginal('password'));
        $this->assertTrue($user->isAdmin());
        $this->assertInstanceOf(UserRole::class, $user->role);
    }

    public function test_get_all_users_filters_by_search()
    {
        User::addUser([
            'name' => 'Findable Person',
            'email' => 'findable.person@erp.test',
            'password' => 'secret123',
            'role' => UserRole::STAFF->value,
        ]);

        $results = User::getAllUsers('Findable Person');

        $this->assertGreaterThanOrEqual(1, $results->total());
        $this->assertEquals('Findable Person', $results->first()->name);
    }

    public function test_update_user_without_password_keeps_old_password()
    {
        $user = User::addUser([
            'name' => 'Original Name',
            'email' => 'update.user@erp.test',
            'password' => 'secret123',
            'role' => UserRole::STAFF->value,
        ]);
        $originalHash = $user->getRawOriginal('password');

        User::updateUser($user->id, [
            'name' => 'Updated Name',
            'email' => 'update.user@erp.test',
            'password' => '',
            'role' => UserRole::ADMIN->value,
        ]);

        $fresh = $user->fresh();
        $this->assertEquals('Updated Name', $fresh->name);
        $this->assertEquals($originalHash, $fresh->getRawOriginal('password'));
        $this->assertTrue($fresh->isAdmin());
    }

    public function test_delete_user_removes_record()
    {
        $user = User::addUser([
            'name' => 'To Delete',
            'email' => 'delete.user@erp.test',
            'password' => 'secret123',
            'role' => UserRole::STAFF->value,
        ]);

        $deleted = User::deleteUser($user->id);

        $this->assertEquals(1, $deleted);
        $this->assertNull(User::find($user->id));
    }
}
