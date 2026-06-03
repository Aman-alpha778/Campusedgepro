<?php

namespace App\Http\Controllers;

use App\Models\DemoUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DemoAuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::guard('demo')->check()) {
            return redirect()->route('demo.dashboard');
        }

        return view('demo.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        if (Auth::guard('demo')->check()) {
            return redirect()->route('demo.dashboard');
        }

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $demoUser = DemoUser::where('username', $credentials['username'])->first();

        if (! $demoUser || ! Hash::check($credentials['password'], $demoUser->password)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Invalid demo credentials.']);
        }

        if ($demoUser->isExpired() || $demoUser->status !== 'Active') {
            $demoUser->update(['status' => 'Expired']);

            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Your demo access has expired. Please contact CampusEdgePro.']);
        }

        Auth::guard('demo')->login($demoUser);
        $request->session()->regenerate();

        return redirect()->route('demo.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('demo')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('demo.login');
    }
}
