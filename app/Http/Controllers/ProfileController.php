<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * update profile avatar
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();
        // 1. Validasi File
        $request->validate([
            // Aturan validasi: Wajib ada, harus berupa file gambar (jpeg, png, gif, svg, webp),
            // maksimum 2MB, dan harus rasio 1:1.
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'dimensions:min_width=100,min_height=100,ratio=1/1'],
        ]);

        // 3. Simpan file baru
        // store('avatars', 'public') akan menyimpan file di storage/app/public/avatars/
        // dan mengembalikan path-nya, misal: 'avatars/randomhash.jpg'
        $path = $request->file('avatar')->store('avatars', 'public');

        // 4. Update database
        $user->update(['avatar' => $path]);

        return Redirect::route('profile.edit');
    }

    /**
     * delete profile user
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        $avatarRaw = $user->getRawOriginal('avatar');

        // Opsional: Hapus file avatar lama dari storage sebelum update
        if ($avatarRaw) {
            Storage::disk('public')->delete($avatarRaw);
        }

        $user->update(['avatar' => null]);

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
