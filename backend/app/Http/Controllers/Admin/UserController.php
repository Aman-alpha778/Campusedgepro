<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private ActivityLogService $logs) {}

    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::with('roles')->latest()->paginate(15)->withQueryString(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => $data['status'],
            'is_admin' => in_array('Super Admin', Role::whereIn('id', $data['roles'] ?? [])->pluck('name')->all(), true),
        ]);
        $user->syncRoles($data['roles'] ?? []);
        $this->logs->record('create', 'User', "Created user {$user->email}", $request);

        return back()->with('admin_success', 'User created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user->id);
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'],
        ]);
        $user->syncRoles($data['roles'] ?? []);
        $user->update(['is_admin' => $user->hasRole('Super Admin')]);
        $this->logs->record('update', 'User', "Updated user {$user->email}", $request);

        return back()->with('admin_success', 'User updated successfully.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['password' => ['required', 'string', 'min:8']]);
        $user->update(['password' => Hash::make($data['password'])]);
        $this->logs->record('update', 'User', "Reset password for {$user->email}", $request);

        return back()->with('admin_success', 'Password reset successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->update(['status' => 'inactive']);
        $this->logs->record('delete', 'User', "Disabled user {$user->email}");

        return back()->with('admin_success', 'User disabled successfully.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$id],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => [$id ? 'nullable' : 'required', 'string', 'min:8'],
            'status' => ['required', 'in:active,inactive'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);
    }
}
