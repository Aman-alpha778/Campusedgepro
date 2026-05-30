@extends('demo.layouts.app', ['title' => $workspace['shortName'].' - '.$module['label']])

@section('content')
  <section class="portal-page-head">
    <span class="demo-role-kicker">{{ $workspace['shortName'] }} Workspace</span>
    <h1>{{ $module['label'] }}</h1>
    <p>{{ $module['description'] }}</p>
  </section>

  <section class="demo-workspace-content">
    <article class="portal-card">
      <h2>Workflow Preview</h2>
      <div class="demo-step-row">
        <span>Open Module</span>
        <span>Review Data</span>
        <span>Create or Edit</span>
        <span>Generate Report</span>
      </div>
    </article>

    <article class="portal-card">
      <h2>Permissions</h2>
      <div class="demo-permission-grid">
        <span>View Records</span>
        <span>Create Records</span>
        <span>Edit Records</span>
        <span>Generate Reports</span>
      </div>
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
      <button class="portal-button" type="button">Create Record</button>
      <button class="portal-button-ghost" type="button">Edit Record</button>
      <button class="portal-button-ghost" type="button">Generate Report</button>
      <button class="portal-button-danger portal-disabled" type="button">Delete Record</button>
    </div>
  </section>
@endsection
