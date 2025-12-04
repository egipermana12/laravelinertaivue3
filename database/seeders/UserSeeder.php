<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(50)->create();

        // Assign roles to users
        $users = User::all();
        foreach ($users as $user) {
            if ($user->id % 2 == 0) {
                $user->assignRole('kasir');
            } else {
                $user->assignRole('owner');
            }
        }

        // admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email_verified_at' => now(),
            'password' =>  Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        $admin->assignRole('admin');
        $admin->givePermissionTo('manage_users', 'view_users', 'manage_roles', 'view_roles', 'manage_permissions', 'view_permissions');

        // developer user
        $developer = User::factory()->create([
            'name' => 'Developer User',
            'username' => 'developer',
            'email_verified_at' => now(),
            'password' =>  Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        $developer->assignRole('developer');
        $developer->givePermissionTo('manage_users', 'view_users', 'manage_roles', 'view_roles', 'manage_permissions', 'view_permissions');

        //staff user
        $staff = User::factory()->create([
            'name' => 'Staff User',
            'username' => 'staff',
            'email_verified_at' => now(),
            'password' =>  Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        $staff->assignRole('staff');
    }
}
