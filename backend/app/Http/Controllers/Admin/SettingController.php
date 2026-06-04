<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(private ActivityLogService $logs) {}

    public function index(): View
    {
        return view('admin.settings.index', ['settings' => Setting::orderBy('key')->get()]);
    }

    public function update(Request $request): RedirectResponse
    {
        foreach ($request->validate(['settings' => ['array'], 'settings.*' => ['nullable', 'string']])['settings'] ?? [] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        $this->logs->record('update', 'Settings', 'Updated system settings', $request);

        return back()->with('admin_success', 'Settings updated successfully.');
    }
}
