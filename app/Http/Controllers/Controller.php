<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class Controller extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);
}
}
