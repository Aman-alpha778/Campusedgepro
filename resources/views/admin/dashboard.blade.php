@extends('admin.layouts.app', ['title' => 'Dashboard'])

@section('content')
  <section class="portal-page-head">
    <h1>Demo request dashboard</h1>
    <p>Track incoming requests, monitor approval progress, and manage live demo access from one place.</p>
  </section>

  <section class="portal-grid-4">
    <article class="portal-stat">
      <span class="portal-muted">Total Requests</span>
      <strong>{{ $stats['total'] }}</strong>
    </article>
    <article class="portal-stat">
      <span class="portal-muted">Pending Requests</span>
      <strong>{{ $stats['pending'] }}</strong>
    </article>
    <article class="portal-stat">
      <span class="portal-muted">Approved Requests</span>
      <strong>{{ $stats['approved'] }}</strong>
    </article>
    <article class="portal-stat">
      <span class="portal-muted">Rejected Requests</span>
      <strong>{{ $stats['rejected'] }}</strong>
    </article>
  </section>

  <section class="portal-grid-2" style="margin-top: 18px;">
    <article class="portal-card">
      <h2>Recent requests</h2>
      <table class="portal-table">
        <thead>
          <tr>
            <th>College</th>
            <th>Admin</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($recentRequests as $request)
            <tr>
              <td>{{ $request->college_name }}</td>
              <td>{{ $request->admin_name }}</td>
              <td><span class="portal-badge {{ strtolower($request->status) }}">{{ $request->status }}</span></td>
              <td>{{ $request->created_at->format('d M Y') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4">No requests submitted yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </article>

    <article class="portal-card">
      <h2>Workflow highlights</h2>
      <p class="portal-muted">Approvals create a new demo login automatically, set a 7-day expiry window, and send credentials by email.</p>
      <div class="portal-restricted" style="margin-top: 18px;">
        Demo restrictions remain active across the portal:
        delete actions, report exports, payment tools, and settings changes stay locked for demo users.
      </div>
      <div style="margin-top: 18px;">
        <a class="portal-button" href="{{ route('admin.demo-requests.index') }}">Open request management</a>
      </div>
    </article>
  </section>
@endsection
