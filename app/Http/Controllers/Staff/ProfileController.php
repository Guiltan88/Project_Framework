<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('staff.profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('staff.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'password' => 'nullable|confirmed|min:6',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048', // 2MB max
        ]);

        // Update data dasar
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->department = $validated['department'] ?? null;

        // Handle password update
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            $this->deleteOldPhoto($user);

            // Upload new photo
            $photoPath = $this->uploadPhoto($request->file('photo'), $user);
            $user->photo = $photoPath;
        }

        $user->save();

        return redirect()->route('staff.profile.index')
            ->with('success', 'Profile berhasil diperbarui.')
            ->with('show_toast', true);
    }

    public function removePhoto()
    {
        $user = Auth::user();

        // Delete photo from storage
        $this->deleteOldPhoto($user);

        // Remove photo reference from database
        $user->photo = null;
        $user->save();

        return redirect()->route('staff.profile.edit')
            ->with('success', 'Foto profil berhasil dihapus.')
            ->with('show_toast', true);
    }

    /**
     * Delete old photo from storage
     */
    private function deleteOldPhoto($user)
    {
        if ($user->photo) {
            // Hapus dari storage
            $photoPath = 'public/' . $user->photo;
            if (Storage::exists($photoPath)) {
                Storage::delete($photoPath);
            }

            // Juga coba hapus dari public jika ada (untuk symlink)
            $publicPath = public_path('storage/' . $user->photo);
            if (file_exists($publicPath)) {
                @unlink($publicPath);
            }
        }
    }

    /**
     * Upload new photo
     */
    private function uploadPhoto($file, $user)
    {
        // Generate unique filename
        $filename = 'staff_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Pastikan folder exists
        $folderPath = 'public/profiles';
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        // Simpan file ke storage (original size)
        $path = $file->storeAs($folderPath, $filename);

        // Return path tanpa 'public/'
        return 'profiles/' . $filename;
    }

    /**
     * Helper method to get photo URL
     */
    public static function getPhotoUrl($user)
    {
        if ($user->photo) {
            // Cek apakah file ada di public storage (symlink)
            $publicPath = public_path('storage/' . $user->photo);
            if (file_exists($publicPath)) {
                return asset('storage/' . $user->photo);
            }

            // Cek apakah file ada di storage
            if (Storage::exists('public/' . $user->photo)) {
                return asset('storage/' . $user->photo);
            }
        }

        // Fallback ke avatar generator
        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name) .
               '&background=696cff&color=fff&size=150&bold=true';
    }
}
