<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Demo ERP' }} | CampusEdgePro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="portal-body">
    <div class="portal-layout">
      <aside class="portal-sidebar">
        <a class="portal-brand" href="{{ route('demo.dashboard') }}">
          <span class="portal-brand-mark"></span>
          <span>CampusEdgePro Demo ERP</span>
        </a>
        <nav>
          <a class="portal-nav-link {{ request()->routeIs('demo.dashboard') ? 'active' : '' }}" href="{{ route('demo.dashboard') }}">Dashboard</a>
          <a class="portal-nav-link {{ request()->routeIs('demo.students') ? 'active' : '' }}" href="{{ route('demo.students') }}">Students</a>
          <a class="portal-nav-link {{ request()->routeIs('demo.attendance') ? 'active' : '' }}" href="{{ route('demo.attendance') }}">Attendance</a>
          <a class="portal-nav-link {{ request()->routeIs('demo.fees') ? 'active' : '' }}" href="{{ route('demo.fees') }}">Fees</a>
          <a class="portal-nav-link {{ request()->routeIs('demo.faculty') ? 'active' : '' }}" href="{{ route('demo.faculty') }}">Faculty</a>
          <a class="portal-nav-link {{ request()->routeIs('demo.reports') ? 'active' : '' }}" href="{{ route('demo.reports') }}">Reports</a>
        </nav>
        <div class="portal-sidebar-foot">
          <form method="post" action="{{ route('demo.logout') }}">
            @csrf
            <button class="portal-button-ghost" type="submit">Logout</button>
          </form>
        </div>
      </aside>
      <main class="portal-main">
        <div class="portal-lockout">Restricted in demo version</div>
        @yield('content')
      </main>
    </div>
  </body>
</html>
