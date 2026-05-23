@extends('demo.layouts.app', ['title' => $module['title']])

@section('content')
  <section class="portal-page-head">
    <h1>{{ $module['title'] }}</h1>
    <p>{{ $module['description'] }}</p>
  </section>

  <section class="portal-module-card">
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

    <div style="display: flex; gap: 12px; margin-top: 18px; flex-wrap: wrap;">
      <button class="portal-button-danger portal-disabled" type="button">Delete Selected</button>
      <button class="portal-button-ghost portal-disabled" type="button">Export Report</button>
      <button class="portal-button-ghost portal-disabled" type="button">Open Payment Gateway</button>
      <button class="portal-button-ghost portal-disabled" type="button">Modify Settings</button>
    </div>
  </section>
@endsection
