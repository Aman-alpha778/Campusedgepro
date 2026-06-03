<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoRequest;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'total' => DemoRequest::count(),
                'pending' => DemoRequest::where('status', 'Pending')->count(),
                'approved' => DemoRequest::where('status', 'Approved')->count(),
                'rejected' => DemoRequest::where('status', 'Rejected')->count(),
            ],
            'recentRequests' => DemoRequest::latest()->take(6)->get(),
            'adminUsers' => User::where('is_admin', true)->latest()->get(),
        ]);
    }
}
