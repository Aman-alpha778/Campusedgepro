<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(private ActivityLogService $logs) {}

    public function index(Request $request): View
    {
        return view('admin.roles.index', [
            'roles' => Role::withCount('users')->with('permissions')->latest()->paginate(15)->withQueryString(),
            'permissions' => Permission::orderBy('group')->orderBy('name')->get()->groupBy('group'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create($data + ['guard_name' => 'web']);
        $role->permissions()->sync($data['permissions'] ?? []);
        $this->logs->record('create', 'Role', "Created role {$role->name}", $request);

        return back()->with('admin_success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
            'description' => ['nullable', 'string'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update($data);
        $role->permissions()->sync($data['permissions'] ?? []);
        $this->logs->record('update', 'Role', "Updated role {$role->name}", $request);

        return back()->with('admin_success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->exists()) {
            return back()->with('admin_warning', 'Role is assigned to users and cannot be deleted.');
        }

        $role->delete();
        $this->logs->record('delete', 'Role', "Deleted role {$role->name}");

        return back()->with('admin_success', 'Role deleted successfully.');
    }
}
