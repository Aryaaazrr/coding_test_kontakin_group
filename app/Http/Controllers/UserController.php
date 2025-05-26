<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('mahasiswa')->where('username', '!=', 'administrator')->get();

        return Inertia::render('Accounts/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'administrator')->get();

        return Inertia::render('Accounts/Create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required'],
        ]);

        $user = new User();
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        if ($validated['role'] === 'mahasiswa') {
            $mahasiswa = new Mahasiswa();
            $mahasiswa->id_mahasiswa = Str::uuid();
            $mahasiswa->id_users = $user->id;
            $mahasiswa->prodi = 'Teknik Informatika';
            $mahasiswa->save();
        }

        $user->assignRole($validated['role']);

        return redirect()->route('user-account.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::where('name', '!=', 'administrator')->get();

        return Inertia::render('Accounts/Edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

    $validated = $request->validate([
        'username' => ['required', 'string', 'min:3', Rule::unique('users')->ignore($user->id)],
        'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        'role' => ['required', 'string'],
    ]);

    $user->username = $validated['username'];
    $user->email = $validated['email'];
    $user->save();

    $user->syncRoles($validated['role']);

    return redirect()->route('user-account.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}