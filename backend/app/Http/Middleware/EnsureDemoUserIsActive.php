<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDemoUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $demoUser = Auth::guard('demo')->user();

        if (! $demoUser) {
            return redirect()->route('demo.login');
        }

        if ($demoUser->isExpired() || $demoUser->status !== 'Active') {
            $demoUser->forceFill(['status' => 'Expired'])->save();

            Auth::guard('demo')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('demo.login')
                ->withErrors(['username' => 'Your demo access has expired. Please contact CampusEdgePro.']);
        }

        return $next($request);
    }
}
