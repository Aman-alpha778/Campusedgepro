@extends('demo.layouts.app', ['title' => 'Role Experience Center', 'hideSidebar' => true, 'hideBanner' => true])

@section('content')
  <section class="demo-role-hero">
    <div class="demo-hero-copy">
      <span class="demo-role-kicker">Role Experience Center</span>
      <h1>Welcome to CampusEdge ERP</h1>
      <p>Explore the ERP platform through different institutional roles. Select a workspace to experience how each stakeholder interacts with the system.</p>
    </div>
    <div class="demo-hero-logo" aria-hidden="true">
      <img src="{{ asset('assets/logoas.png') }}" alt="">
    </div>
  </section>

  <section class="demo-workspace-grid">
    <article class="demo-workspace-card blue">
      <div class="demo-workspace-top">
        <span>01</span>
        <strong>Super Admin Experience</strong>
      </div>
      <p>Open the live, database-driven Super Admin dashboard with real CRUD modules, RBAC, fees, reports, settings, and activity logs.</p>
      <div class="demo-highlight-list">
        <span>Database Dashboard</span>
        <span>Department CRUD</span>
        <span>Student CRUD</span>
        <span>RBAC</span>
        <span>Reports</span>
        <span>Settings</span>
      </div>
      <a class="portal-button" href="{{ route('demo.super-admin.dashboard') }}">Launch Super Admin Workspace</a>
    </article>

    @foreach ($workspaces as $key => $workspace)
      <article class="demo-workspace-card {{ $workspace['accent'] }}">
        <div class="demo-workspace-top">
          <span>{{ str_pad((string) ($loop->iteration + 1), 2, '0', STR_PAD_LEFT) }}</span>
          <strong>{{ $workspace['name'] }}</strong>
        </div>
        <p>{{ $workspace['description'] }}</p>
        <div class="demo-highlight-list">
          @foreach ($workspace['highlights'] as $highlight)
            <span>{{ $highlight }}</span>
          @endforeach
        </div>
        <a class="portal-button" href="{{ route('demo.workspace', $key) }}">{{ $workspace['action'] }}</a>
      </article>
    @endforeach
  </section>
@endsection
