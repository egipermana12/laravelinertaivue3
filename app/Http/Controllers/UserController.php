<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $users = User::with('roles', 'permissions')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->paginate(10)
            ->through(fn($user) => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->permissions->pluck('id'),
            ])
            ->withQueryString();

        return Inertia::render('User/UserIndex', [
            'users' => $users,
            'roles' => Role::all()->pluck('name'),
            'permissions' => Permission::all()->select('id', 'name'),
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:255|unique:' . User::class,
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role);
        if ($request->has('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        return redirect()->back()->with('success', 'Selected users have been added.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->update($request->only('name', 'username', 'email'));

        $user->syncRoles([$request->role]);

        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->back()->with('success', 'User has been updated.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        foreach ($users as $user) {
            $avatarRaw = $user->getRawOriginal('avatar');
            if ($avatarRaw) {
                Storage::disk('public')->delete($avatarRaw);
            }
            $user->delete();
        }

        return redirect()->back()->with('success', 'Selected users have been deleted.');
    }
}
