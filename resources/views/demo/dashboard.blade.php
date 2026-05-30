@extends('demo.layouts.app', ['title' => 'Dashboard'])

@section('content')
  <section class="demo-dashboard-hero">
    <div>
      <span class="demo-chip">Full ERP Demo</span>
      <h1>Explore a complete college ERP workspace.</h1>
      <p>Review admissions, academics, attendance, exams, fees, payroll, notifications, and analytics with realistic demo data.</p>
      <div class="demo-hero-actions">
        <a class="portal-button" href="{{ route('demo.admissions') }}">Start Admission Workflow</a>
        <a class="portal-button-ghost" href="{{ route('demo.reports') }}">View Reports</a>
      </div>
    </div>
    <div class="demo-hero-panel">
      <strong>Daily Reset</strong>
      <span>02:00 AM</span>
      <p>Demo data is prepared for repeat product tours. Delete, subscription, settings, payment, and export actions remain restricted.</p>
    </div>
  </section>

  <section class="demo-kpi-grid">
    @foreach ($kpis as $kpi)
      <article class="demo-kpi-card {{ $kpi['tone'] }}">
        <span>{{ $kpi['label'] }}</span>
        <strong>{{ $kpi['value'] }}</strong>
        <small>{{ $kpi['trend'] }}</small>
      </article>
    @endforeach
  </section>

  <section class="demo-grid-2">
    <article class="portal-card demo-card">
      <div class="portal-card-head">
        <div>
          <h2>Demo User Roles</h2>
          <p>Switch perspectives during a sales walkthrough.</p>
        </div>
      </div>
      <div class="demo-role-grid">
        @foreach ($roles as $role)
          <article class="demo-role-card">
            <strong>{{ $role['name'] }}</strong>
            <p>{{ $role['purpose'] }}</p>
            <div>
              @foreach ($role['access'] as $access)
                <span>{{ $access }}</span>
              @endforeach
            </div>
          </article>
        @endforeach
      </div>
    </article>

    <article class="portal-card demo-card">
      <h2>Demo Data Coverage</h2>
      <div class="demo-data-grid">
        @foreach ($demoData as $item)
          <div>
            <strong>{{ $item['value'] }}</strong>
            <span>{{ $item['label'] }}</span>
          </div>
        @endforeach
      </div>
      <div class="portal-restricted">Delete records disabled. System settings read only. Subscription settings hidden.</div>
    </article>
  </section>

  <section class="demo-chart-grid">
    @foreach ($charts as $title => $points)
      <article class="portal-card demo-chart-card">
        <h3>{{ $title }}</h3>
        <div class="demo-bars">
          @foreach ($points as $point)
            <div>
              <span style="height: {{ $point['value'] }}%;"></span>
              <small>{{ $point['label'] }}</small>
            </div>
          @endforeach
        </div>
      </article>
    @endforeach
  </section>

  <section class="portal-card demo-card">
    <div class="portal-card-head">
      <div>
        <h2>Recommended MVP Walkthrough</h2>
        <p>Use this path to show the strongest product story first.</p>
      </div>
      <a class="portal-button-ghost" href="{{ route('demo.students') }}">Open Student Module</a>
    </div>
    <div class="demo-flow">
      @foreach (['Authentication & Roles', 'Dashboard', 'Admissions', 'Students', 'Academics', 'Attendance', 'Timetable', 'Exams', 'Assignments', 'Fees', 'Staff', 'Payroll', 'Notifications', 'Reports', 'Demo Management'] as $step)
        <span>{{ $step }}</span>
      @endforeach
    </div>
  </section>
@endsection
