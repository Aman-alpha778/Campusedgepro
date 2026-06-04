<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticeController extends Controller
{
    public function __construct(private ActivityLogService $logs) {}

    public function index(): View
    {
        return view('admin.notices.index', ['notices' => Notice::with('creator')->latest()->paginate(15)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $notice = Notice::create($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'publish_date' => ['required', 'date'],
            'status' => ['required', 'in:draft,published,archived'],
        ]) + ['created_by' => auth()->id() ?? User::where('is_admin', true)->value('id') ?? User::query()->value('id')]);
        $this->logs->record('create', 'Notice', "Created notice {$notice->title}", $request);

        return back()->with('admin_success', 'Notice saved successfully.');
    }

    public function publish(Notice $notice): RedirectResponse
    {
        $notice->update(['status' => 'published']);
        $this->logs->record('update', 'Notice', "Published notice {$notice->title}");

        return back()->with('admin_success', 'Notice published successfully.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();
        $this->logs->record('delete', 'Notice', "Deleted notice {$notice->title}");

        return back()->with('admin_success', 'Notice deleted successfully.');
    }
}
