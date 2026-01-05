<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookings = $user->bookings()
            ->with(['room', 'room.building'])
            ->latest()
            ->paginate(10);

        return view('guest.profile.index', compact('user', 'bookings'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('guest.profile.edit', compact('user'));
    }

    /**
     * Update guest profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'password' => 'nullable|confirmed|min:6',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        // FOTO PROFILE
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $data['photo'] = $request->file('photo')->store('profiles', 'public');
        } else {
            unset($data['photo']);
        }

        // PASSWORD
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('guest.profile.index')
            ->with('success', 'Profile berhasil diperbarui.');
    }

    /**
     * Remove profile photo
     */
    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->photo = null;
            $user->save();
        }

        return redirect()->route('guest.profile.edit')
            ->with('success', 'Foto profil berhasil dihapus.');
    }
}
