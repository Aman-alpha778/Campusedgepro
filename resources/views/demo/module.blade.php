@extends('demo.layouts.app', ['title' => $workspace['shortName'].' - '.$module['label']])

@section('content')
  <section class="portal-page-head">
    <span class="demo-role-kicker">{{ $workspace['shortName'] }} Workspace</span>
    <h1>{{ $module['label'] }}</h1>
    <p>{{ $module['description'] }}</p>
  </section>

  <section class="demo-workspace-content">
    <article class="portal-card module-command-card">
      <div class="portal-card-head">
        <div>
          <h2>Workflow Preview</h2>
          <p class="portal-muted">{{ $module['description'] }}</p>
        </div>
        <span class="portal-badge active">Ready</span>
      </div>
      <div class="demo-step-row module-flow-row">
        @foreach (($module['workflow'] ?? ['Open Module', 'Review Data', 'Create or Edit', 'Generate Report']) as $step)
          <span>{{ $step }}</span>
        @endforeach
      </div>
    </article>

    <article class="portal-card module-command-card">
      <div class="portal-card-head">
        <div>
          <h2>Available Actions</h2>
          <p class="portal-muted">Actions enabled for {{ $workspace['shortName'] }} access.</p>
        </div>
      </div>
      <div class="demo-permission-grid module-action-grid">
        @foreach (($module['actions'] ?? ['View Records', 'Create Records', 'Edit Records', 'Generate Reports']) as $action)
          <span>{{ $action }}</span>
        @endforeach
      </div>
      @isset($module['details'])
        <div class="module-detail-strip">
          @foreach ($module['details'] as $detail)
            <span>{{ $detail }}</span>
          @endforeach
        </div>
      @endisset
      <div class="portal-restricted">Delete, system configuration, subscription settings, and licensing changes are restricted.</div>
    </article>
  </section>

  <section class="portal-module-card">
    <div class="portal-card-head">
      <div>
        <h2>Sample Data View</h2>
        <p class="portal-muted">Filtered for {{ $workspace['shortName'] }} access.</p>
      </div>
      <span class="portal-badge approved">Demo Data</span>
    </div>
    <table class="portal-table">
      <thead>
        <tr>
          <th>Record</th>
          <th>Detail</th>
          <th>Metric</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($module['records'] as $row)
          <tr>
            @foreach ($row as $value)
              <td>{{ $value }}</td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="demo-action-row">
      <button class="portal-button" type="button">{{ $module['actions'][0] ?? 'Create Record' }}</button>
      <button class="portal-button-ghost" type="button">{{ $module['actions'][1] ?? 'Edit Record' }}</button>
      <button class="portal-button-ghost" type="button">{{ $module['actions'][2] ?? 'Generate Report' }}</button>
      <button class="portal-button-danger portal-disabled" type="button">Delete Record</button>
    </div>
  </section>
@endsection
