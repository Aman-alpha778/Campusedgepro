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
      <strong>{{ $workspaceKey === 'super-admin' ? 'Live Institution Pulse' : 'Demo Analytics Tracking' }}</strong>
      <span>{{ $workspaceKey === 'super-admin' ? 'Admissions, attendance, fee collection, leaves, notices, reports, access events, and audit logs are visible at a glance.' : 'Login, workspace selection, module visits, report generation, and consultation requests are tracked for product insight.' }}</span>
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

  @isset($workspace['dashboardGroups'])
    <section class="super-admin-grid">
      @foreach ($workspace['dashboardGroups'] as $group)
        <article class="super-admin-card">
          <div class="super-admin-card-head">
            <span>{{ substr($group['title'], 0, 2) }}</span>
            <h2>{{ $group['title'] }}</h2>
          </div>
          <div class="super-admin-metric-list">
            @foreach ($group['items'] as $item)
              <div>
                <span>{{ $item['label'] }}</span>
                <strong>{{ $item['value'] }}</strong>
              </div>
            @endforeach
          </div>
        </article>
      @endforeach
    </section>

    <section class="super-admin-command-grid">
      <article class="portal-card super-admin-flow-card">
        <div class="portal-card-head">
          <div>
            <h2>Daily Workflow</h2>
            <p class="portal-muted">Super Admin operating rhythm for today.</p>
          </div>
          <span class="portal-badge active">Live</span>
        </div>
        <div class="super-admin-flow">
          @foreach ($workspace['dailyWorkflow'] as $step)
            <span>{{ $step }}</span>
          @endforeach
        </div>
      </article>

      <article class="portal-card">
        <div class="portal-card-head">
          <div>
            <h2>Priority Queue</h2>
            <p class="portal-muted">Items that need administrative attention.</p>
          </div>
        </div>
        <div class="super-admin-queue">
          @foreach ($workspace['priorityQueue'] as $item)
            <div class="{{ $item['tone'] }}">
              <span>{{ $item['label'] }}</span>
              <strong>{{ $item['value'] }}</strong>
            </div>
          @endforeach
        </div>
      </article>
    </section>
  @endisset

  <section class="demo-workspace-content">
    <article class="portal-card">
      <h2>{{ $workspaceKey === 'super-admin' ? 'ERP Control Modules' : 'Dedicated Navigation' }}</h2>
      <p class="portal-muted">{{ $workspaceKey === 'super-admin' ? 'Every core institution function is available from this command area.' : 'Only relevant modules are displayed for this role.' }}</p>
      <div class="demo-module-links super-admin-module-links">
        @foreach ($workspace['modules'] as $module)
          <a href="{{ route('demo.workspace.module', [$workspaceKey, $module['key']]) }}">{{ $module['label'] }}</a>
        @endforeach
      </div>
    </article>

    <article class="portal-card">
      @isset($workspace['auditTrail'])
        <h2>Recent Audit Logs</h2>
        <p class="portal-muted">Latest traceable activity across the ERP.</p>
        <div class="super-admin-audit-list">
          @foreach ($workspace['auditTrail'] as $event)
            <div>
              <time>{{ $event['time'] }}</time>
              <strong>{{ $event['event'] }}</strong>
              <span>{{ $event['actor'] }}</span>
            </div>
          @endforeach
        </div>
      @else
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
      @endisset
    </article>
  </section>
@endsection
