<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman view profile admin (read-only)
     * Akan menggunakan view: profile/index.blade.php
     */
    public function index()
    {
        $user = Auth::user();

        // Pastikan hanya admin yang bisa akses
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.profile.index', compact('user'));
    }

    /**
     * Menampilkan halaman edit profile admin
     * Akan menggunakan view: profile/edit.blade.php
     */
    public function edit()
    {
        $user = Auth::user();

        // Pastikan hanya admin yang bisa akses
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Memperbarui profil admin
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi hanya untuk admin
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:800',
        ]);

        // FOTO PROFILE
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $data['photo'] = $request->file('photo')->store('admin/profile', 'public');
        }

        // PASSWORD
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        // Redirect ke halaman view profile setelah update
        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile admin berhasil diperbarui');
    }
}
