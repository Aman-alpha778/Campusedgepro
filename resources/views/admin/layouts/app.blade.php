<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Portal' }} | CampusEdgePro</title>
    <link rel="stylesheet" href="/assets/portal.css">
  </head>
  <body class="portal-body">
    <div class="portal-layout">
      <aside class="portal-sidebar">
        <a class="portal-brand" href="{{ route('admin.dashboard') }}">
          <span class="portal-brand-mark"></span>
          <span>CampusEdgePro Admin</span>
        </a>
        <nav>
          <a class="portal-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
          <a class="portal-nav-link {{ request()->routeIs('admin.demo-requests.*') ? 'active' : '' }}" href="{{ route('admin.demo-requests.index') }}">Demo Requests</a>
        </nav>
        <div class="portal-sidebar-foot">
          <form method="post" action="{{ route('admin.logout') }}">
            @csrf
            <button class="portal-button-ghost" type="submit">Logout</button>
          </form>
        </div>
      </aside>
      <main class="portal-main">
        <div class="portal-topbar">
          <div>
            <div class="portal-muted">Secure administration area</div>
            <strong>{{ auth()->user()?->name }}</strong>
          </div>
        </div>
        @if (session('admin_success'))
          <div class="portal-alert">{{ session('admin_success') }}</div>
        @endif
        @yield('content')
      </main>
    </div>
  </body>
</html>
