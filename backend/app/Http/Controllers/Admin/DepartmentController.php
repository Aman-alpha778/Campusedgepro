<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Department;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function __construct(private ActivityLogService $logs) {}

    public function index(Request $request): View
    {
        return view('admin.departments.index', [
            'departments' => Department::with(['campus', 'hod'])->withCount('courses')->latest()->paginate(15)->withQueryString(),
            'campuses' => Campus::orderBy('name')->get(),
            'hodUsers' => User::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $department = Department::create($this->validated($request));
        $this->logs->record('create', 'Department', "Created department {$department->name}", $request);

        return back()->with('admin_success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $department->update($this->validated($request, $department->id));
        $this->logs->record('update', 'Department', "Updated department {$department->name}", $request);

        return back()->with('admin_success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();
        $this->logs->record('delete', 'Department', "Deleted department {$department->name}");

        return back()->with('admin_success', 'Department deleted successfully.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'campus_id' => ['required', 'exists:campuses,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:80', 'unique:departments,code,'.$id],
            'hod_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
