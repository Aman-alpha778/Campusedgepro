<?php

use App\Http\Middleware\EnsureDemoUserIsActive;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('demo-access:maintain')->dailyAt('08:00');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(
            at: '*',
            headers: SymfonyRequest::HEADER_X_FORWARDED_FOR
                | SymfonyRequest::HEADER_X_FORWARDED_HOST
                | SymfonyRequest::HEADER_X_FORWARDED_PORT
                | SymfonyRequest::HEADER_X_FORWARDED_PROTO
                | SymfonyRequest::HEADER_X_FORWARDED_PREFIX
                | SymfonyRequest::HEADER_X_FORWARDED_AWS_ELB
        );

        $middleware->redirectGuestsTo(function (Request $request): string {
            if ($request->is('demo-portal') || $request->is('demo-portal/*')) {
                return route('demo.login');
            }

            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            return '/';
        });

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'demo.active' => EnsureDemoUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
