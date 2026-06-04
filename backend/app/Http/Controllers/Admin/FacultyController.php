<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class FacultyController extends Controller
{
    public function __construct(private ActivityLogService $logs) {}

    public function index(Request $request): View
    {
        return view('admin.faculty.index', [
            'faculty' => Faculty::with(['user', 'department'])
                ->when($request->filled('search'), fn ($query) => $query->whereHas('user', fn ($user) => $user->where('name', 'like', "%{$request->search}%")))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $faculty = DB::transaction(function () use ($data): Faculty {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password'] ?? 'password'),
                'status' => $data['status'],
            ]);

            if ($role = Role::where('name', 'Faculty')->first()) {
                $user->assignRole($role);
            }

            return Faculty::create($this->payload($data, $user->id));
        });
        $this->logs->record('create', 'Faculty', "Created faculty {$faculty->employee_id}", $request);

        return back()->with('admin_success', 'Faculty created successfully.');
    }

    public function update(Request $request, Faculty $faculty): RedirectResponse
    {
        $data = $this->validated($request, $faculty);
        DB::transaction(function () use ($faculty, $data): void {
            $faculty->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'status' => $data['status'],
            ]);
            $faculty->update($this->payload($data, $faculty->user_id));
        });
        $this->logs->record('update', 'Faculty', "Updated faculty {$faculty->employee_id}", $request);

        return back()->with('admin_success', 'Faculty updated successfully.');
    }

    public function destroy(Faculty $faculty): RedirectResponse
    {
        $faculty->delete();
        $faculty->user->update(['status' => 'inactive']);
        $this->logs->record('delete', 'Faculty', "Deleted faculty {$faculty->employee_id}");

        return back()->with('admin_success', 'Faculty deleted successfully.');
    }

    private function validated(Request $request, ?Faculty $faculty = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.($faculty?->user_id ?? 'NULL')],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => [$faculty ? 'nullable' : 'required', 'string', 'min:8'],
            'department_id' => ['required', 'exists:departments,id'],
            'employee_id' => ['required', 'string', 'max:80', 'unique:faculty,employee_id,'.($faculty?->id ?? 'NULL')],
            'qualification' => ['nullable', 'string', 'max:255'],
            'experience' => ['required', 'integer', 'min:0', 'max:60'],
            'joining_date' => ['nullable', 'date'],
            'salary' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }

    private function payload(array $data, int $userId): array
    {
        return [
            'user_id' => $userId,
            'department_id' => $data['department_id'],
            'employee_id' => $data['employee_id'],
            'qualification' => $data['qualification'] ?? null,
            'experience' => $data['experience'],
            'joining_date' => $data['joining_date'] ?? null,
            'salary' => $data['salary'],
            'status' => $data['status'],
        ];
    }
}
