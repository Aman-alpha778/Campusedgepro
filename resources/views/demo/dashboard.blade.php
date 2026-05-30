@extends('demo.layouts.app', ['title' => 'Role Experience Center'])

@section('content')
  <section class="demo-role-hero">
    <span class="demo-role-kicker">Role Experience Center</span>
    <h1>Welcome to CampusEdge ERP</h1>
    <p>Explore the ERP platform through different institutional roles. Select a workspace to experience how each stakeholder interacts with the system.</p>
  </section>

  <section class="demo-workspace-grid">
    @foreach ($workspaces as $key => $workspace)
      <article class="demo-workspace-card {{ $workspace['accent'] }}">
        <div class="demo-workspace-top">
          <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
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

  <section class="portal-card demo-tracking-card">
    <div>
      <h2>Demo Data Requirements</h2>
      <p class="portal-muted">The demo environment is prepared with realistic records for role-specific exploration.</p>
    </div>
    <div class="demo-data-strip">
      @foreach ($demoStats as $stat)
        <span><strong>{{ $stat['value'] }}</strong>{{ $stat['label'] }}</span>
      @endforeach
    </div>
    <div class="portal-restricted">
      Allowed actions: view records, create records, edit records, and generate reports. Restricted actions: delete records, modify system configuration, access subscription settings, and modify licensing.
    </div>
  </section>
@endsection
