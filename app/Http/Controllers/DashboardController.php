<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return Inertia::render('Dashboard/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();

        return Inertia::render('Dashboard/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions']);

        return redirect()->route('dashboard.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return Inertia::render('Dashboard/Show', [
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();

        if ($role->name === 'administrator') {
            return redirect()->route('dashboard.index')->with('error', 'Admin role cannot be edited.');
        }

        return Inertia::render('Dashboard/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role->name = $validated['name'];
        $role->save();

        $role->syncPermissions($validated['permissions']);

        return redirect()->route('dashboard.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'administrator') {
            return redirect()->route('dashboard.index')->with('error', 'Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('dashboard.index')->with('success', 'Role deleted successfully.');
    }
}
