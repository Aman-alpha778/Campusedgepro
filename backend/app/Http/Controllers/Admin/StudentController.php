<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Course;
use App\Models\Department;
use App\Models\Student;
use App\Services\ActivityLogService;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(private StudentService $students, private ActivityLogService $logs) {}

    public function index(Request $request): View
    {
        return view('admin.students.index', [
            'students' => $this->students->list($request),
            'campuses' => Campus::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'courses' => Course::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $student = $this->students->create($this->validated($request));
        $this->logs->record('create', 'Student', "Created student {$student->registration_number}", $request);

        return back()->with('admin_success', 'Student created successfully.');
    }

    public function show(Student $student): View
    {
        return view('admin.students.show', ['student' => $student->load(['user', 'campus', 'department', 'course', 'fees.payments', 'documents'])]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $this->students->update($student, $this->validated($request, $student));
        $this->logs->record('update', 'Student', "Updated student {$student->registration_number}", $request);

        return back()->with('admin_success', 'Student updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();
        $student->user->update(['status' => 'inactive']);
        $this->logs->record('delete', 'Student', "Deleted student {$student->registration_number}");

        return back()->with('admin_success', 'Student deleted successfully.');
    }

    public function uploadDocument(Request $request, Student $student): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'document' => ['required', 'file', 'max:5120'],
        ]);

        $path = $request->file('document')->store('student-documents', 'public');
        $student->documents()->create([
            'name' => $data['name'],
            'path' => $path,
            'mime_type' => $request->file('document')->getMimeType(),
        ]);
        $this->logs->record('create', 'Student Document', "Uploaded document for {$student->registration_number}", $request);

        return back()->with('admin_success', 'Document uploaded successfully.');
    }

    private function validated(Request $request, ?Student $student = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.($student?->user_id ?? 'NULL')],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => [$student ? 'nullable' : 'required', 'string', 'min:8'],
            'campus_id' => ['required', 'exists:campuses,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'roll_number' => ['required', 'string', 'max:80', 'unique:students,roll_number,'.($student?->id ?? 'NULL')],
            'registration_number' => ['required', 'string', 'max:80', 'unique:students,registration_number,'.($student?->id ?? 'NULL')],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'admission_date' => ['required', 'date'],
            'status' => ['required', 'in:active,inactive,graduated,suspended'],
        ]);
    }
}
