@extends('demo.layouts.app', ['title' => $workspace['shortName'].' Workspace'])

@section('content')
  <section class="demo-workspace-hero {{ $workspace['accent'] }}">
    <div>
      <span class="demo-role-kicker">{{ $workspace['shortName'] }} Workspace</span>
      <h1>{{ $workspace['objective'] }}</h1>
      <p>{{ $workspace['description'] }}</p>
      <div class="demo-workspace-actions">
        <a class="portal-button" href="{{ route('demo.workspace.module', [$workspaceKey, $workspace['modules'][0]['key']]) }}">Open First Module</a>
        <a class="portal-button-ghost" href="{{ route('demo.dashboard') }}">Switch Workspace</a>
      </div>
    </div>
    <div class="demo-session-panel">
      <strong>Demo Analytics Tracking</strong>
      <span>Login, workspace selection, module visits, report generation, and consultation requests are tracked for product insight.</span>
    </div>
  </section>

  <section class="demo-widget-grid">
    @foreach ($workspace['widgets'] as $widget)
      <article class="demo-widget-card">
        <span>{{ $widget['label'] }}</span>
        <strong>{{ $widget['value'] }}</strong>
        <small>{{ $widget['note'] }}</small>
      </article>
    @endforeach
  </section>

  <section class="demo-workspace-content">
    <article class="portal-card">
      <h2>Dedicated Navigation</h2>
      <p class="portal-muted">Only relevant modules are displayed for this role.</p>
      <div class="demo-module-links">
        @foreach ($workspace['modules'] as $module)
          <a href="{{ route('demo.workspace.module', [$workspaceKey, $module['key']]) }}">{{ $module['label'] }}</a>
        @endforeach
      </div>
    </article>

    <article class="portal-card">
      <h2>Role-specific Reports</h2>
      <p class="portal-muted">Reports and data views are filtered according to the selected workspace.</p>
      <table class="portal-table">
        <tbody>
          @foreach (array_slice($workspace['modules'], 0, 4) as $module)
            <tr>
              <td><strong>{{ $module['label'] }}</strong></td>
              <td>{{ $module['description'] }}</td>
              <td><span class="portal-badge approved">Available</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </article>
  </section>
@endsection
