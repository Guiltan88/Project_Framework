<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        // Helper function untuk foto profile
        $avatar = $this->getProfilePhoto($user);

        return view('guest.profile.edit', compact('user', 'avatar'));
    }

    /**
     * Update guest profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ];

        // Custom validation messages
        $messages = [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus JPG, PNG, GIF, atau WebP.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle photo upload
            $photoPath = $user->photo;
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                // Upload new photo
                $file = $request->file('photo');
                $fileName = 'profile_' . $user->id . '_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
                $photoPath = $file->storeAs('profile-photos', $fileName, 'public');
            }

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->position = $request->position;

            if ($photoPath) {
                $user->photo = $photoPath;
            }

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->route('guest.profile.edit')
                ->with([
                    'success' => 'Profile berhasil diperbarui!',
                    'show_toast' => true
                ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove profile photo
     */
    public function removePhoto()
    {
        $user = Auth::user();

        try {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
                $user->photo = null;
                $user->save();
            }

            return redirect()->route('guest.profile.edit')
                ->with([
                    'success' => 'Foto profil berhasil dihapus!',
                    'show_toast' => true
                ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper function untuk foto profile
     */
    private function getProfilePhoto($user)
    {
        if ($user->photo) {
            // Cek file di storage
            $storagePath = 'storage/' . $user->photo;
            $publicPath = public_path($storagePath);

            if (file_exists($publicPath)) {
                return asset($storagePath);
            }

            // Cek di storage link
            if (Storage::disk('public')->exists($user->photo)) {
                return asset('storage/' . $user->photo);
            }
        }

        // Fallback ke avatar generator
        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name) .
               '&background=696cff&color=fff&size=150&bold=true&format=svg';
    }

}
