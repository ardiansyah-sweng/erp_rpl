<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use App\Constants\Messages;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::getAllUsers($search);
        $roles = UserRole::cases();

        return view('users.list', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = UserRole::cases();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:' . implode(',', array_map(fn ($role) => $role->value, UserRole::cases())),
        ]);

        User::addUser($request->only(['name', 'email', 'password', 'role']));

        return redirect()->route('users.index')->with('success', Messages::USER_CREATED);
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return abort(404, Messages::USER_NOT_FOUND);
        }

        $roles = UserRole::cases();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:' . implode(',', array_map(fn ($role) => $role->value, UserRole::cases())),
        ]);

        $updated = User::updateUser($id, $request->only(['name', 'email', 'password', 'role']));

        if (!$updated) {
            return redirect()->back()->withInput()->with('error', Messages::USER_NOT_FOUND);
        }

        return redirect()->route('users.index')->with('success', Messages::USER_UPDATED);
    }

    public function destroy($id)
    {
        $deleted = User::deleteUser($id);

        if (!$deleted) {
            return redirect()->route('users.index')->with('error', Messages::USER_DELETE_FAILED);
        }

        return redirect()->route('users.index')->with('success', Messages::USER_DELETED);
    }
}
