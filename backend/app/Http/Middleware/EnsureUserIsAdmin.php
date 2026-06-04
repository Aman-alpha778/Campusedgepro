<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $statusAllowsAccess = ! Schema::hasColumn('users', 'status') || ! isset($user->status) || $user->status === 'active';
        $hasAdminAccess = (bool) $user?->is_admin;

        if (! $hasAdminAccess && Schema::hasTable('roles')) {
            $hasAdminAccess = $user?->hasRole('Super Admin') ?? false;
        }

        if (! $user || ! $statusAllowsAccess || ! $hasAdminAccess) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'You do not have access to the admin dashboard.']);
        }

        return $next($request);
    }
}
