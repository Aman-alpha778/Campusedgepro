<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Portal' }} | CampusEdgePro</title>
    @vite(['frontend/resources/css/app.css', 'frontend/resources/js/app.js'])
  </head>
  <body class="portal-body">
    @php
      $adminUser = auth()->user();
    @endphp
    <div class="portal-layout">
      <aside class="portal-sidebar">
        <a class="portal-brand" href="{{ route('admin.dashboard') }}">
          <img class="portal-brand-logo" src="/assets/logoas.png" alt="CampusEdgePro">
          <span class="portal-brand-copy">
            <span>CampusEdgePro Admin</span>
            <small>Control Center</small>
          </span>
        </a>
        <nav>
          <a class="portal-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
          <a class="portal-nav-link {{ request()->routeIs('admin.demo-requests.*') ? 'active' : '' }}" href="{{ route('admin.demo-requests.index') }}">Demo Requests</a>
          <a class="portal-nav-link" href="#admin-profile">Profile</a>
          <a class="portal-nav-link" href="#admin-users">Admin Users</a>
        </nav>
      </aside>
      <main class="portal-main">
        <div class="portal-topbar">
          <div>
            <div class="portal-muted">Secure administration area</div>
            <strong>{{ $adminUser?->name }}</strong>
          </div>
          <details class="portal-profile-menu">
            <summary class="portal-profile-chip" aria-label="Open admin profile menu">
              <span class="portal-avatar">{{ strtoupper(substr($adminUser?->name ?? 'A', 0, 1)) }}</span>
              <span class="portal-profile-copy">
                <strong>{{ $adminUser?->name }}</strong>
                <span class="portal-muted">{{ $adminUser?->email }}</span>
              </span>
              <span class="portal-profile-caret" aria-hidden="true">v</span>
            </summary>
            <div class="portal-profile-panel">
              <div class="portal-profile-row">
                <span class="portal-avatar">{{ strtoupper(substr($adminUser?->name ?? 'A', 0, 1)) }}</span>
                <div class="portal-profile-meta">
                  <strong>{{ $adminUser?->name }}</strong>
                  <span class="portal-muted">{{ $adminUser?->email }}</span>
                </div>
              </div>
              <div class="portal-key-value portal-profile-panel-details">
                <div>
                  <strong>Role</strong>
                  <span>{{ $adminUser?->is_admin ? 'Administrator' : 'User' }}</span>
                </div>
                <div>
                  <strong>Signed in</strong>
                  <span>Admin portal</span>
                </div>
              </div>
              <form method="post" action="{{ route('admin.logout') }}">
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
