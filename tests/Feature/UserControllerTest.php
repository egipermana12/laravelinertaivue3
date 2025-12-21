<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 🔴 WAJIB untuk Spatie
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // Create role & attach permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create admin user
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);
    }

    public function test_index_displays_users_list()
    {
        $this->actingAs($this->adminUser);

        User::factory()->count(3)->create();

        $this->get(route('users.index'))
            ->assertStatus(200)
            ->assertInertia(
                fn(Assert $page) =>
                $page
                    // ✅ HARUS sama dengan controller
                    ->component('User/UserIndex')
                    ->has('users.data', 4)
                    ->has('roles')
                    ->has('permissions')
                    ->has('filters')
            );
    }

    public function test_index_can_filter_by_search()
    {
        $this->actingAs($this->adminUser);

        User::factory()->create(['name' => 'Alice Wonderland']);
        User::factory()->create(['name' => 'Bob Builder']);
        User::factory()->create(['name' => 'Charlie Chocolate']);

        $this->get(route('users.index', ['search' => 'Bob']))
            ->assertStatus(200)
            ->assertInertia(
                fn(Assert $page) =>
                $page
                    ->component('User/UserIndex')
                    ->has('users.data', 1)
                    ->where('users.data.0.name', 'Bob Builder')
            );
    }

    public function test_store_creates_user_with_role_and_permissions()
    {
        $this->actingAs($this->adminUser);

        $role = Role::create(['name' => 'editor']);
        $permission = Permission::create(['name' => 'edit articles']);
        $response = $this->post(route('users.store'), [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'role' => 'admin',
            'permissions' => [$permission->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        $user = User::where('username', 'testuser')->first();
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasPermissionTo('edit articles'));
    }

    public function test_store_requires_required_fields()
    {
        $this->actingAs($this->adminUser);

        $this->post(route('users.store'), [])
            ->assertSessionHasErrors([
                'name',
                'username',
                'email',
                'password',
                'role',
            ]);
    }

    public function test_update_user()
    {
        $this->actingAs($this->adminUser);

        $oldRole = Role::create(['name' => 'user']);

        $permission = Permission::create(['name' => 'edit user']);

        $user = User::factory()->create();
        $user->assignRole($oldRole);

        $this->put(route('users.update', $user), [
            'name' => 'Updated Name',
            'username' => $user->username,
            'email' => 'updated@example.com',
            'role' => 'admin',
            'permissions' => [$permission->id],
        ])->assertRedirect();

        $user->refresh();

        $this->assertEquals('Updated Name', $user->name);
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasPermissionTo('edit user'));
    }

    public function test_bulk_delete_users_and_avatar()
    {
        $this->actingAs($this->adminUser);

        $user = User::factory()->create([
            'avatar' => 'avatars/test.png',
        ]);

        Storage::disk('public')->put('avatars/test.png', 'dummy');

        $this->post(route('users.bulk-delete'), [
            'user_ids' => [$user->id],
        ])->assertRedirect();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        Storage::disk('public')->assertMissing('avatars/test.png');
    }

    public function test_guest_cannot_access_user_index()
    {
        $this->get(route('users.index'))
            ->assertRedirect(route('login'));
    }
}
