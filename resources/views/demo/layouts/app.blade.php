@php
  $demoNav = [
    ['route' => 'demo.dashboard', 'label' => 'Dashboard', 'icon' => 'DB'],
    ['route' => 'demo.admissions', 'label' => 'Admissions', 'icon' => 'AD'],
    ['route' => 'demo.students', 'label' => 'Students', 'icon' => 'ST'],
    ['route' => 'demo.academics', 'label' => 'Academics', 'icon' => 'AC'],
    ['route' => 'demo.attendance', 'label' => 'Attendance', 'icon' => 'AT'],
    ['route' => 'demo.timetable', 'label' => 'Timetable', 'icon' => 'TT'],
    ['route' => 'demo.exams', 'label' => 'Exams', 'icon' => 'EX'],
    ['route' => 'demo.assignments', 'label' => 'Assignments', 'icon' => 'AS'],
    ['route' => 'demo.fees', 'label' => 'Fees', 'icon' => 'FE'],
    ['route' => 'demo.staff', 'label' => 'Staff', 'icon' => 'SF'],
    ['route' => 'demo.payroll', 'label' => 'Payroll', 'icon' => 'PY'],
    ['route' => 'demo.notifications', 'label' => 'Notifications', 'icon' => 'NO'],
    ['route' => 'demo.reports', 'label' => 'Reports', 'icon' => 'RP'],
  ];
@endphp

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Demo ERP' }} | CampusEdgePro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="portal-body demo-erp-body">
    <div class="portal-layout demo-erp-layout">
      <aside class="portal-sidebar demo-erp-sidebar">
        <a class="portal-brand demo-erp-brand" href="{{ route('demo.dashboard') }}">
          <img class="portal-brand-logo" src="/assets/cmpus.png" alt="CampusEdgePro">
          <span class="portal-brand-copy">
            <span>CampusEdgePro</span>
            <small>Demo ERP</small>
          </span>
        </a>
        <nav class="demo-erp-nav">
          @foreach ($demoNav as $item)
            <a class="portal-nav-link demo-erp-nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
              <span>{{ $item['icon'] }}</span>
              {{ $item['label'] }}
            </a>
          @endforeach
        </nav>
        <div class="portal-sidebar-foot demo-erp-sales-card">
          <strong>Interested in CampusEdge ERP?</strong>
          <a class="portal-button" href="/contact.html">Talk to Sales</a>
          <form method="post" action="{{ route('demo.logout') }}">
            @csrf
            <button class="portal-button-ghost" type="submit">Logout</button>
          </form>
        </div>
      </aside>
      <main class="portal-main demo-erp-main">
        <div class="portal-lockout demo-erp-banner">
          You are currently using a Demo Environment. Changes may not be permanently saved.
        </div>
        @yield('content')
        <section class="demo-floating-cta" aria-label="Sales actions">
          <strong>Need this for your institution?</strong>
          <a href="/demo.html">Schedule Live Consultation</a>
          <a href="/pricing.html">Request Pricing</a>
        </section>
      </main>
    </div>
  </body>
</html>
