<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Portal' }} | CampusEdgePro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="portal-body">
    <div class="portal-layout">
      <aside class="portal-sidebar">
        <a class="portal-brand" href="{{ route('admin.dashboard') }}">
          <span class="portal-brand-copy">
            <span>CampusEdgePro Admin</span>
            <small>Control Center</small>
          </span>
        </a>
        <nav>
          <a class="portal-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
          <a class="portal-nav-link {{ request()->routeIs('admin.demo-requests.*') ? 'active' : '' }}" href="{{ route('admin.demo-requests.index') }}">Demo Requests</a>
          <a class="portal-nav-link" href="#admin-profile">Profile</a>
        </nav>
      </aside>
      <main class="portal-main">
        <div class="portal-topbar">
          <div>
            <div class="portal-muted">Secure administration area</div>
            <strong>{{ auth()->user()?->name }}</strong>
          </div>
          <div class="portal-profile-chip">
            <span class="portal-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}</span>
            <span class="portal-profile-copy">
              <strong>{{ auth()->user()?->name }}</strong>
              <span class="portal-muted">{{ auth()->user()?->email }}</span>
            </span>
          </div>
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
