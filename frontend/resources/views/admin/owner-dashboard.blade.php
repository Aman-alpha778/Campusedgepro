@extends('admin.layouts.app', ['title' => 'Demo Request Dashboard'])

@section('content')
  <section class="portal-page-head">
    <h1>Demo Request Dashboard</h1>
    <p>Review incoming demo requests, approve access, resend credentials, and monitor request status.</p>
  </section>

  <section class="portal-grid-4">
    <article class="portal-stat"><span class="portal-stat-label">Total Requests</span><strong>{{ $stats['total'] }}</strong><div class="portal-stat-note">All inbound demo submissions.</div></article>
    <article class="portal-stat"><span class="portal-stat-label">Pending</span><strong>{{ $stats['pending'] }}</strong><div class="portal-stat-note">Waiting for review.</div></article>
    <article class="portal-stat"><span class="portal-stat-label">Approved</span><strong>{{ $stats['approved'] }}</strong><div class="portal-stat-note">Demo access granted.</div></article>
    <article class="portal-stat"><span class="portal-stat-label">Rejected</span><strong>{{ $stats['rejected'] }}</strong><div class="portal-stat-note">Closed without approval.</div></article>
  </section>

  <section class="portal-grid-2">
    <article class="portal-card">
      <div class="portal-card-head">
        <div><h2>Recent Requests</h2><p class="portal-muted">Latest demo submissions from the website.</p></div>
        <a class="portal-button-ghost" href="{{ route('admin.demo-requests.index') }}">Manage requests</a>
      </div>
      <table class="portal-table">
        <thead><tr><th>College</th><th>Admin</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
          @forelse ($recentRequests as $request)
            <tr>
              <td><strong>{{ $request->college_name }}</strong><div class="portal-table-note">{{ $request->email }}</div></td>
              <td>{{ $request->admin_name }}</td>
              <td><span class="portal-badge {{ strtolower($request->status) }}">{{ $request->status }}</span></td>
              <td>{{ $request->created_at?->format('d M Y') }}</td>
            </tr>
          @empty
            <tr><td colspan="4">No requests submitted yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </article>

    <article class="portal-card">
      <div class="portal-card-head">
        <div><h2>Website Owner Access</h2><p class="portal-muted">Admin accounts that can approve demo requests.</p></div>
      </div>
      <table class="portal-table">
        <thead><tr><th>Name</th><th>Email</th><th>Added</th></tr></thead>
        <tbody>
          @forelse ($adminUsers as $adminUser)
            <tr><td>{{ $adminUser->name }}</td><td>{{ $adminUser->email }}</td><td>{{ $adminUser->created_at?->format('d M Y') }}</td></tr>
          @empty
            <tr><td colspan="3">No admin users found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </article>
  </section>
@endsection
