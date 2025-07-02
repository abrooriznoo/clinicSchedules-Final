<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\Patients;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function patient()
    {
        $patients = User::all()->where('role_id', 3); // Assuming role_id 3 is for patients
        $roles = Roles::all();

        return view('admin.patient', compact('patients', 'roles'));
    }

    public function doctor()
    {
        $doctors = User::all()->where('role_id', 2); // Assuming role_id 2 is for doctors
        $roles = Roles::all();

        return view('admin.doctor', compact('doctors', 'roles'));
    }

    public function admin()
    {
        $admins = User::all()->where('role_id', 1); // Assuming role_id 1 is for admins
        $roles = Roles::all();
        if ($admins->isEmpty()) {
            return view('admin.admin', ['message' => 'No admins found']);
        }

        return view('admin.admin', compact('admins', 'roles'));
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'nip' => ($request->role_id == 1 ? 'A' : ($request->role_id == 2 ? 'D' : ($request->role_id == 3 ? 'P' : '')))
                . '-' . now()->format('dmy') . (User::max('id') + 1),
            'nik' => $request->nik,
            'phone' => $request->phone,
            'address' => $request->adress,
            'photo' => $request->file('photo') ? $request->file('photo')->store('profile-photos', 'public') : null,
        ]);

        // Jika role_id = 2 (dokter), insert juga ke tabel Doctors
        if ($request->role_id == 2) {
            $user = User::latest()->first();
            Doctors::create([
                'id_users' => $user->id,
                'id_roles' => $request->role_id,
            ]);
        }

        // Jika role_id = 3 (pasien), insert juga ke tabel Patients
        if ($request->role_id == 3) {
            $user = User::latest()->first();
            Patients::create([
                'id_users' => $user->id,
                'id_roles' => $request->role_id,
            ]);
        }

        return redirect()->back()->with('success', 'User created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        //     'role_id' => 'required|integer|exists:roles,id',
        //     'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'password' => 'nullable|string|min:6',
        // ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'nik' => $request->nik,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('profile-photos', 'public');
        }

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        // Logic to delete a user
        $user = User::findOrFail($id);

        // Hapus photo jika ada
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
