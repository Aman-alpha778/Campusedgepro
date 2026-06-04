<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Services\ActivityLogService;
use App\Services\CampusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampusController extends Controller
{
    public function __construct(private CampusService $campuses, private ActivityLogService $logs) {}

    public function index(Request $request): View
    {
        return view('admin.campuses.index', ['campuses' => $this->campuses->list($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $campus = $this->campuses->create($this->validated($request));
        $this->logs->record('create', 'Campus', "Created campus {$campus->name}", $request);

        return back()->with('admin_success', 'Campus created successfully.');
    }

    public function show(Campus $campus): View
    {
        return view('admin.campuses.show', ['campus' => $campus->load(['departments.courses', 'students.user'])]);
    }

    public function update(Request $request, Campus $campus): RedirectResponse
    {
        $this->campuses->update($campus, $this->validated($request, $campus->id));
        $this->logs->record('update', 'Campus', "Updated campus {$campus->name}", $request);

        return back()->with('admin_success', 'Campus updated successfully.');
    }

    public function toggle(Campus $campus): RedirectResponse
    {
        $this->campuses->toggleStatus($campus);
        $this->logs->record('update', 'Campus', "Changed campus status for {$campus->name}");

        return back()->with('admin_success', 'Campus status updated.');
    }

    public function destroy(Campus $campus): RedirectResponse
    {
        $campus->delete();
        $this->logs->record('delete', 'Campus', "Deleted campus {$campus->name}");

        return back()->with('admin_success', 'Campus deleted successfully.');
    }

    private function validated(Request $request, ?int $campusId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:campuses,code,'.$campusId],
            'email' => ['required', 'email', 'unique:campuses,email,'.$campusId],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
