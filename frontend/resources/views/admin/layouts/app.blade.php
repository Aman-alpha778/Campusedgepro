<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Portal' }} | CampusEdgePro</title>
    @vite(['frontend/resources/css/app.css', 'frontend/resources/js/app.js'])
  </head>
  <body class="portal-body {{ request()->is('demo-portal/super-admin') || request()->is('demo-portal/super-admin/*') ? 'super-admin-ui' : '' }}">
    @php
      $isDemoSuperAdmin = request()->is('demo-portal/super-admin') || request()->is('demo-portal/super-admin/*');
      $adminRoutePrefix = $adminRoutePrefix ?? ($isDemoSuperAdmin ? 'demo.super-admin' : 'admin');
      $adminUser = $isDemoSuperAdmin ? auth('demo')->user() : auth()->user();
      $adminName = $isDemoSuperAdmin
        ? ($adminUser?->demoRequest?->admin_name ?? $adminUser?->username ?? 'Demo Super Admin')
        : ($adminUser?->name ?? 'Super Admin');
      $adminEmail = $isDemoSuperAdmin
        ? ($adminUser?->demoRequest?->email ?? $adminUser?->username ?? 'demo@campusedge.test')
        : ($adminUser?->email ?? 'admin@campusedge.test');
      $adminRole = 'Administrator';
      if ($isDemoSuperAdmin) {
        $adminRole = 'Super Admin Demo';
      } elseif ($adminUser && \Illuminate\Support\Facades\Schema::hasTable('roles')) {
        $adminRole = $adminUser->roles->pluck('name')->join(', ') ?: 'Administrator';
      }
    @endphp
    <div class="portal-layout">
      <aside class="portal-sidebar">
        <a class="portal-brand" href="{{ route(($adminRoutePrefix ?? 'admin').'.dashboard') }}">
          @unless ($isDemoSuperAdmin)
            <img class="portal-brand-logo" src="/assets/logoas.png" alt="CampusEdgePro">
          @endunless
          <span class="portal-brand-copy">
            <span>{{ $isDemoSuperAdmin ? 'Super Admin Experience' : 'CampusEdgePro Admin' }}</span>
            <small>{{ $isDemoSuperAdmin ? 'Demo Portal' : 'Control Center' }}</small>
          </span>
        </a>
        <nav>
          @if ($isDemoSuperAdmin)
            @foreach ([
              ['Dashboard', 'dashboard', 'dashboard'],
              ['Departments', 'departments.index', 'departments.*'],
              ['Courses', 'courses.index', 'courses.*'],
              ['Students', 'students.index', 'students.*'],
              ['Faculty', 'faculty.index', 'faculty.*'],
              ['Admissions', 'reports.index', 'reports.*'],
              ['Fees', 'fees.index', 'fees.*'],
              ['Notices', 'notices.index', 'notices.*'],
              ['Reports', 'reports.index', 'reports.*'],
              ['Users', 'users.index', 'users.*'],
              ['Roles', 'roles.index', 'roles.*'],
              ['Settings', 'settings.index', 'settings.*'],
              ['Activity Logs', 'activity-logs.index', 'activity-logs.*'],
            ] as [$label, $route, $active])
              <a class="portal-nav-link {{ request()->routeIs($adminRoutePrefix.'.'.$active) ? 'active' : '' }}" href="{{ route($adminRoutePrefix.'.'.$route, $label === 'Admissions' ? ['type' => 'admissions'] : []) }}">{{ $label }}</a>
            @endforeach
          @else
            <a class="portal-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="portal-nav-link {{ request()->routeIs('admin.demo-requests.*') ? 'active' : '' }}" href="{{ route('admin.demo-requests.index') }}">Demo Requests</a>
          @endif
        </nav>
      </aside>
      <main class="portal-main">
        <div class="portal-topbar">
          <div class="portal-topbar-context">
            <span class="portal-topbar-kicker">{{ $isDemoSuperAdmin ? 'Super Admin Workspace' : 'Owner Workspace' }}</span>
            <strong>{{ $adminName }}</strong>
            <div class="portal-topbar-pills">
              <span>{{ $adminRole }}</span>
              <span>{{ $isDemoSuperAdmin ? 'Demo portal' : 'Admin portal' }}</span>
            </div>
          </div>
          @if ($isDemoSuperAdmin)
            <div class="super-admin-top-search" role="search">
              <span aria-hidden="true">&#9906;</span>
              <input type="search" placeholder="Search students, fees, faculty..." aria-label="Search dashboard">
            </div>
            <div class="super-admin-top-actions" aria-label="Dashboard actions">
              <button type="button" aria-label="Messages">&#9993;</button>
              <button type="button" aria-label="Notifications">&#8226;</button>
            </div>
          @endif
          <details class="portal-profile-menu">
            <summary class="portal-profile-chip" aria-label="Open admin profile menu">
              <span class="portal-avatar">{{ strtoupper(substr($adminName, 0, 1)) }}</span>
              <span class="portal-profile-copy">
                <strong>{{ $adminName }}</strong>
                <span>{{ $adminEmail }}</span>
              </span>
              <span class="portal-profile-caret" aria-hidden="true">&#8964;</span>
            </summary>
            <div class="portal-profile-panel">
              <div class="portal-profile-row">
                <span class="portal-avatar">{{ strtoupper(substr($adminName, 0, 1)) }}</span>
                <div class="portal-profile-meta">
                  <strong>{{ $adminName }}</strong>
                  <span class="portal-muted">{{ $adminEmail }}</span>
                </div>
              </div>
              <div class="portal-key-value portal-profile-panel-details">
                <div>
                  <strong>Role</strong>
                  <span>{{ $adminRole }}</span>
                </div>
                <div>
                  <strong>Signed in</strong>
                  <span>{{ $isDemoSuperAdmin ? 'Demo portal' : 'Admin portal' }}</span>
                </div>
              </div>
              <form method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.logout') }}">
                @csrf
                <button class="portal-button-danger portal-profile-logout" type="submit">Logout</button>
              </form>
            </div>
          </details>
        </div>
        @if (session('admin_success'))
          <div class="portal-alert">{{ session('admin_success') }}</div>
        @endif
        @if (session('admin_warning'))
          <div class="portal-alert" style="background:#fff7ed; color:#9a3412; border-color:#fed7aa;">{{ session('admin_warning') }}</div>
        @endif
        @yield('content')
      </main>
    </div>
  </body>
</html>
