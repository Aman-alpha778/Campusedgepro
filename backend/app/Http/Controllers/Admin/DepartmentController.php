<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentHodHistory;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\Student;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function __construct(private ActivityLogService $logs) {}

    public function index(Request $request): View
    {
        $departments = Department::query()
            ->with('hod')
            ->withCount(['faculty', 'students', 'courses', 'subjects'])
            ->search($request->string('search')->toString())
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.departments.index', [
            'departments' => $departments,
            'adminRoutePrefix' => $this->routePrefix(),
            'stats' => [
                'total_departments' => Department::count(),
                'active_departments' => Department::active()->count(),
                'inactive_departments' => Department::inactive()->count(),
                'total_faculty' => Faculty::count(),
                'total_students' => Student::count(),
                'total_courses' => Course::count(),
                'assigned_hods' => Department::whereNotNull('hod_id')->count(),
                'total_intake' => Department::sum('total_intake'),
            ],
            'hodUsers' => $this->hodUsers(),
        ]);
    }

    public function show(Department $department): View
    {
        return view('admin.departments.show', [
            'adminRoutePrefix' => $this->routePrefix(),
            'department' => $department->load([
                'hod',
                'faculty.user',
                'students.user',
                'courses',
                'subjects',
                'hodHistory.oldHod',
                'hodHistory.newHod',
                'hodHistory.changedBy',
            ])->loadCount(['faculty', 'students', 'courses', 'subjects']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $department = Department::create($data);
        $this->recordHodChange($department, null, $department->hod_id);
        $this->logs->record('create', 'Department', "Created department {$department->name}", $request);

        return back()->with('admin_success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $data = $this->validated($request, $department->id);
        $oldHod = $department->hod_id;
        $data['slug'] = $department->slug ?: $this->uniqueSlug($data['name'], $department->id);
        $data['updated_by'] = Auth::id();

        $department->update($data);
        $this->recordHodChange($department, $oldHod, $department->hod_id);
        $this->logs->record('update', 'Department', "Updated department {$department->name}", $request);

        return back()->with('admin_success', 'Department updated successfully.');
    }

    public function assignHod(Request $request, Department $department): RedirectResponse
    {
        $data = $request->validate(['hod_id' => ['nullable', 'exists:users,id']]);
        $oldHod = $department->hod_id;
        $department->update(['hod_id' => $data['hod_id'] ?? null, 'updated_by' => Auth::id()]);
        $this->recordHodChange($department, $oldHod, $department->hod_id);
        $this->logs->record('update', 'Department', "Assigned HOD for {$department->name}", $request);

        return back()->with('admin_success', 'HOD assigned successfully.');
    }

    public function changeStatus(Request $request, Department $department): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:active,inactive']]);
        $department->update(['status' => $data['status'], 'updated_by' => Auth::id()]);
        $this->logs->record('update', 'Department', "Changed department status for {$department->name}", $request);

        return back()->with('admin_success', 'Department status updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->students()->exists() || $department->faculty()->exists() || $department->courses()->exists()) {
            return back()->with('admin_warning', 'Department contains linked records and cannot be deleted.');
        }

        $department->delete();
        $this->logs->record('delete', 'Department', "Deleted department {$department->name}");

        return back()->with('admin_success', 'Department deleted successfully.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:40', Rule::unique('departments', 'code')->ignore($id)],
            'description' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:255'],
            'hod_id' => ['nullable', 'exists:users,id'],
            'established_year' => ['nullable', 'integer', 'min:1900', 'max:'.now()->year],
            'total_intake' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }

    private function routePrefix(): string
    {
        return request()->is('demo-portal/super-admin') || request()->is('demo-portal/super-admin/*')
            ? 'demo.super-admin'
            : 'admin';
    }

    private function hodUsers()
    {
        return Faculty::query()
            ->with('user')
            ->whereHas('user', fn ($query) => $query->where('status', 'active'))
            ->get()
            ->pluck('user')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    private function recordHodChange(Department $department, ?int $oldHod, ?int $newHod): void
    {
        if ($oldHod === $newHod) {
            return;
        }

        DepartmentHodHistory::create([
            'department_id' => $department->id,
            'old_hod' => $oldHod,
            'new_hod' => $newHod,
            'changed_by' => Auth::id(),
            'created_at' => now(),
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $index = 2;

        while (Department::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$index}";
            $index++;
        }

        return $slug;
    }
}
