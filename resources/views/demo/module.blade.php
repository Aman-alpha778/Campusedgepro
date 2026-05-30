@extends('demo.layouts.app', ['title' => $module['title']])

@section('content')
  <section class="portal-page-head demo-module-head">
    <span class="demo-chip">{{ $module['title'] }}</span>
    <h1>{{ $module['title'] }}</h1>
    <p>{{ $module['description'] }}</p>
  </section>

  @if (! empty($module['metrics']))
    <section class="demo-module-metrics">
      @foreach ($module['metrics'] as $metric)
        <article>
          <span>{{ $metric[0] }}</span>
          <strong>{{ $metric[1] }}</strong>
        </article>
      @endforeach
    </section>
  @endif

  <section class="demo-grid-2">
    @if (! empty($module['workflow']))
      <article class="portal-card demo-card">
        <h2>Workflow</h2>
        <div class="demo-workflow">
          @foreach ($module['workflow'] as $step)
            <span>{{ $step }}</span>
          @endforeach
        </div>
      </article>
    @endif

    @if (! empty($module['form']))
      <article class="portal-card demo-card">
        <h2>Demo Form Fields</h2>
        <div class="demo-field-grid">
          @foreach ($module['form'] as $field)
            <label>
              <span>{{ $field }}</span>
              <input type="text" value="{{ $field }} sample" disabled>
            </label>
          @endforeach
        </div>
      </article>
    @endif

    @if (! empty($module['actions']))
      <article class="portal-card demo-card">
        <h2>Available Actions</h2>
        <div class="demo-action-grid">
          @foreach ($module['actions'] as $action)
            <button class="portal-button-ghost" type="button">{{ $action }}</button>
          @endforeach
        </div>
      </article>
    @endif

    @if (! empty($module['panels']))
      <article class="portal-card demo-card">
        <h2>Module Areas</h2>
        <div class="demo-panel-grid">
          @foreach ($module['panels'] as $panel)
            <div>
              <strong>{{ $panel[0] }}</strong>
              <span>{{ $panel[1] }}</span>
            </div>
          @endforeach
        </div>
      </article>
    @endif
  </section>

  <section class="portal-module-card demo-card">
    <div class="portal-card-head">
      <div>
        <h2>Live Demo Records</h2>
        <p>Sample operational data prepared for product exploration.</p>
      </div>
      <span class="portal-badge approved">Preview Only</span>
    </div>
    <table class="portal-table">
      <thead>
        <tr>
          @foreach ($module['headers'] as $header)
            <th>{{ $header }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach ($module['rows'] as $row)
          <tr>
            @foreach ($row as $value)
              <td>{{ $value }}</td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="portal-restricted">
      Restricted in demo version. Delete, export, payment, and settings actions remain disabled.
    </div>

    <div class="demo-disabled-actions">
      <button class="portal-button-danger portal-disabled" type="button">Delete Records</button>
      <button class="portal-button-ghost portal-disabled" type="button">Export Report</button>
      <button class="portal-button-ghost portal-disabled" type="button">Open Payment Gateway</button>
      <button class="portal-button-ghost portal-disabled" type="button">Modify Settings</button>
    </div>
  </section>
@endsection
