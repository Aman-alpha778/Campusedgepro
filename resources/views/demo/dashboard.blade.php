@extends('demo.layouts.app', ['title' => 'Dashboard'])

@section('content')
  <section class="portal-page-head">
    <h1>Welcome to your CampusEdgePro demo</h1>
    <p>Explore the ERP using realistic sample data with safe read-only restrictions across sensitive actions.</p>
  </section>

  <section class="portal-hero-banner">
    <article class="portal-hero-panel">
      <h2 style="margin-top: 0;">Institution snapshot</h2>
      <p class="portal-muted">This preview environment mirrors a college operations workspace across students, fees, attendance, faculty, and reports.</p>
      <div style="margin-top: 16px;">
        <a class="portal-button" href="{{ route('demo.students') }}">Explore student records</a>
      </div>
    </article>
    <article class="portal-card">
      <h2 style="margin-top: 0;">Demo restrictions</h2>
      <ul class="portal-muted">
        <li>Delete records is disabled</li>
        <li>Export reports is disabled</li>
        <li>Payment system access is disabled</li>
        <li>Settings changes are disabled</li>
      </ul>
    </article>
  </section>

  <section class="portal-grid-4">
    @foreach ($kpis as $kpi)
      <article class="portal-stat">
        <span class="portal-muted">{{ $kpi['label'] }}</span>
        <strong>{{ $kpi['value'] }}</strong>
      </article>
    @endforeach
  </section>

  <section class="portal-grid-2" style="margin-top: 18px;">
    <article class="portal-card">
      <h2>Recommended walkthrough</h2>
      <table class="portal-table">
        <thead>
          <tr>
            <th>Step</th>
            <th>What to review</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>1</td><td>Open student profiles and admissions data</td></tr>
          <tr><td>2</td><td>Review daily attendance snapshots</td></tr>
          <tr><td>3</td><td>Inspect fee collection and outstanding dues</td></tr>
          <tr><td>4</td><td>Preview faculty workload and reporting dashboards</td></tr>
        </tbody>
      </table>
    </article>
    <article class="portal-card">
      <h2>Read-only mode</h2>
      <p class="portal-muted">Sensitive actions stay locked so your team can safely evaluate workflows without affecting any live data or connected services.</p>
      <div class="portal-restricted">
        Restricted in demo version
      </div>
    </article>
  </section>
@endsection
