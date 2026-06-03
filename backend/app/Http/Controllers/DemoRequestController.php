<?php

namespace App\Http\Controllers;

use App\Models\DemoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'college_name' => ['required', 'string', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'student_strength' => ['required', 'string', 'max:255'],
            'requirements' => ['nullable', 'string', 'max:5000'],
        ]);

        DemoRequest::create([
            ...$validated,
            'status' => 'Pending',
        ]);

        return back()->with('demo_request_success', 'Your demo request has been submitted. Our team will review it shortly.');
    }
}
