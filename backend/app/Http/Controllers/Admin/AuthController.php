<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private ActivityLogService $logs) {}

    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()?->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        if (Auth::check() && (Auth::user()?->is_admin || Auth::user()?->hasRole('Super Admin'))) {
            return redirect()->route('admin.dashboard');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid admin credentials.']);
        }

        $request->session()->regenerate();

        if (! Auth::user()?->is_admin && ! Auth::user()?->hasRole('Super Admin')) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'You do not have access to the admin dashboard.']);
        }

        $this->logs->record('login', 'Auth', 'Admin login successful', $request);

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->logs->record('logout', 'Auth', 'Admin logout', $request);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
