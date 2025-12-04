<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $users = User::with('roles', 'permissions')
            ->paginate(10)
            ->through(fn($user) => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->permissions->pluck('name'),
            ]);

        return Inertia::render('User/UserIndex', [
            'users' => $users
        ]);
    }
}
