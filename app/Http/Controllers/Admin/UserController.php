<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk staff
        $query = User::role('staff');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('department', 'LIKE', "%{$search}%");
            });
        }

        $staffs = $query->latest()->paginate(10);

        return view('admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'department' => $request->department,
            // HAPUS: 'role' => 'staff',
        ]);

        // PERBAIKAN: Assign role menggunakan Spatie Permission
        $user->assignRole('staff');

        return redirect()->route('admin.users.index')
            ->with('success', 'Staff berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        // PERBAIKAN: Gunakan method isStaff() dari model
        abort_if(!$user->isStaff(), 404);

        return view('admin.staff.edit', ['staff' => $user]);
    }

    public function update(Request $request, User $user)
    {
        // PERBAIKAN: Gunakan method isStaff() dari model
        abort_if(!$user->isStaff(), 404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'department']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // PERBAIKAN: Gunakan method isStaff() dari model
        abort_if(!$user->isStaff(), 404);

        $user->delete();
        return back()->with('success', 'Staff dinonaktifkan.');
    }

    // BONUS: Method untuk mengaktifkan kembali user
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        abort_if(!$user->isStaff(), 404);

        $user->restore();
        return back()->with('success', 'Staff berhasil diaktifkan kembali.');
    }

    // BONUS: Method untuk melihat staff yang dinonaktifkan
    public function trashed()
    {
        $staffs = User::onlyTrashed()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'staff');
            })
            ->latest()
            ->paginate(10);

        return view('admin.staff.trashed', compact('staffs'));
    }
}
