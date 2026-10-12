<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount(['users', 'permissions'])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'distinct', 'exists:permissions,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $role = Role::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'status' => 1,
            ]);

            $role->permissions()->sync($validated['permissions'] ?? []);
        });

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');

        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['0', '1', 0, 1])],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'distinct', 'exists:permissions,id'],
        ]);

        if ($role->name === 'Administrator' && (int) $validated['status'] !== 1) {
            return back()->withInput()->withErrors([
                'status' => 'The Administrator role cannot be deactivated.',
            ]);
        }

        DB::transaction(function () use ($validated, $role) {
            $role->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'status' => (int) $validated['status'],
            ]);

            $role->permissions()->sync($validated['permissions'] ?? []);
        });

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }
}
