<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Demo ERP' }} | CampusEdgePro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="portal-body">
    <div class="portal-layout demo-role-layout">
      <aside class="portal-sidebar demo-role-sidebar">
        <a class="portal-brand" href="{{ route('demo.dashboard') }}">
          <span>{{ $workspace['shortName'] ?? 'CampusEdgePro Demo ERP' }}</span>
        </a>
        <nav>
          @isset($workspaceKey, $workspace)
            <a class="portal-nav-link {{ request()->routeIs('demo.workspace') ? 'active' : '' }}" href="{{ route('demo.workspace', $workspaceKey) }}">Dashboard</a>
            @foreach ($workspace['modules'] as $item)
              <a class="portal-nav-link {{ ($moduleKey ?? '') === $item['key'] ? 'active' : '' }}" href="{{ route('demo.workspace.module', [$workspaceKey, $item['key']]) }}">
                {{ $item['label'] }}
              </a>
            @endforeach
          @else
            <a class="portal-nav-link active" href="{{ route('demo.dashboard') }}">Role Experience Center</a>
          @endisset
        </nav>
      </aside>
      <main class="portal-main">
        <div class="portal-lockout demo-restriction-banner">
          You are currently using a Demo Environment. Changes may not be permanently saved.
        </div>
        @yield('content')
      </main>
    </div>
  </body>
</html>
