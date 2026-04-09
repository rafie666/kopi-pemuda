<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');
        $view = $request->query('view', 'users'); // default to users
        
        $query = User::query();
        if ($role) {
            $query->where('role', $role);
        } else {
            // In master view, show everyone except maybe admin if desired, 
            // but user wants master list usually.
        }
        
        $users = $query->latest()->get();
        
        // Always load shifts for the shift view or if needed
        $shiftType = $request->query('shift', 'all');
        $shifts = \App\Models\Shift::with('user')
            ->when($shiftType !== 'all', function ($query) use ($shiftType) {
                if ($shiftType === 'siang') {
                    $query->whereTime('start_time', '>=', '10:00:00')
                          ->whereTime('start_time', '<', '14:00:00'); 
                } else {
                     $query->whereTime('start_time', '>=', '14:00:00');
                }
            })
            ->latest()
            ->get();
            
        $kasirs = User::where('role', 'kasir')->latest()->get(); 
        $potentialKasirs = User::where('role', 'pegawai')->latest()->get();

        $allJabatans = User::distinct()
            ->whereNotNull('jabatan')
            ->pluck('jabatan')
            ->map(fn($item) => ucfirst(strtolower(trim($item))))
            ->unique()
            ->toArray();
            
        // Ensure standard roles are in the list if not present
        if (!in_array('Admin', $allJabatans)) $allJabatans[] = 'Admin';
        if (!in_array('Kasir', $allJabatans)) $allJabatans[] = 'Kasir';
        
        $allJabatans = array_unique($allJabatans);
        sort($allJabatans);

        return view('admin.user.index', compact('users', 'shifts', 'shiftType', 'kasirs', 'view', 'role', 'allJabatans', 'potentialKasirs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'nullable|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jabatan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['role'] = 'pegawai'; // Default
        
        // Use name to generate a base username but make it unique
        $baseUsername = str_replace(' ', '', strtolower($request->name));
        $data['username'] = $baseUsername . '_' . Str::random(5);
        $data['password'] = Hash::make(Str::random(10));

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Pegawai baru berhasil ditambahkan.');
    }

    public function assignKasir(Request $request)
    {
        // Check for conflicting usernames in trash and delete them
        User::onlyTrashed()->where('username', $request->username)->forceDelete();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'username' => 'required|unique:users,username,' . $request->user_id,
            'password' => 'required|min:4',
        ], [
            'username.unique' => 'Username ini sudah dipakai oleh orang lain.',
            'username.required' => 'Waspada! Username kasir harus diisi.',
            'password.min' => 'Password minimal 4 karakter ya!',
        ]);

        $user = User::findOrFail($request->user_id);
        
        $success = $user->update([
            'role' => 'kasir',
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if ($success) {
            return redirect()->route('admin.users.index', ['role' => 'kasir'])->with('success', 'Berhasil! ' . $user->name . ' sekarang punya akses Kasir.');
        }

        return back()->with('error', 'Gagal memproses pendaftaran kasir.');
    }

    public function update(Request $request, User $user)
    {
        // Check for conflicting usernames in trash and delete them
        if ($request->filled('username')) {
            User::onlyTrashed()->where('username', $request->username)
                ->where('id', '!=', $user->id)
                ->forceDelete();
        }

        $request->validate([
            'name' => 'required',
            'phone_number' => 'nullable|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jabatan' => 'nullable|string',
            'username' => 'nullable|unique:users,username,' . $user->id,
            'password' => 'nullable|min:4',
        ]);

        $data = $request->except(['photo', 'password']);
        
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir.');
        }

        if ($user->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

        $user->delete();
        return redirect()->back()->with('success', 'Pegawai berhasil dihapus.');
    }
}
