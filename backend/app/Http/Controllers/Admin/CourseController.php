<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(private ActivityLogService $logs) {}

    public function index(): View
    {
        return view('admin.courses.index', [
            'courses' => Course::with('department')->withCount('students')->latest()->paginate(15)->withQueryString(),
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $course = Course::create($this->validated($request));
        $this->logs->record('create', 'Course', "Created course {$course->name}", $request);

        return back()->with('admin_success', 'Course created successfully.');
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $course->update($this->validated($request, $course->id));
        $this->logs->record('update', 'Course', "Updated course {$course->name}", $request);

        return back()->with('admin_success', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();
        $this->logs->record('delete', 'Course', "Deleted course {$course->name}");

        return back()->with('admin_success', 'Course deleted successfully.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:80', 'unique:courses,code,'.$id],
            'duration' => ['required', 'string', 'max:80'],
            'total_semesters' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['semester_count'] = $data['total_semesters'];

        return $data;
    }
}
