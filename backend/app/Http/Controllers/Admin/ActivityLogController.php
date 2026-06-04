<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.activity-logs.index', [
            'logs' => ActivityLog::with('user')
                ->when($request->filled('module'), fn ($query) => $query->where('module', $request->module))
                ->when($request->filled('action'), fn ($query) => $query->where('action', $request->action))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
        ]);
    }
}
