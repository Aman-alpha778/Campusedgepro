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

  <section class="portal-grid-2" style="margin-top: 18px;">
    <article class="portal-card portal-profile-card" id="admin-profile">
      <div class="portal-profile-row">
        <span class="portal-avatar" style="width: 64px; height: 64px; border-radius: 20px;">{{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}</span>
        <div class="portal-profile-meta">
          <h2 style="margin: 0;">Admin profile</h2>
          <p class="portal-muted" style="margin: 0;">Primary administrator for the CampusEdgePro approval workflow.</p>
        </div>
      </div>
      <div class="portal-key-value">
        <div>
          <strong>Name</strong>
          <span>{{ auth()->user()?->name }}</span>
        </div>
        <div>
          <strong>Email</strong>
          <span>{{ auth()->user()?->email }}</span>
        </div>
        <div>
          <strong>Role</strong>
          <span>{{ auth()->user()?->is_admin ? 'Administrator' : 'User' }}</span>
        </div>
      </div>
    </article>

    <article class="portal-card">
      <h2>Admin quick actions</h2>
      <p class="portal-muted">Keep the review process moving with fast access to the most common administrative tasks.</p>
      <div style="display: grid; gap: 12px; margin-top: 16px;">
        <a class="portal-button" href="{{ route('admin.demo-requests.index') }}">Review pending requests</a>
        <a class="portal-button-ghost" href="#admin-profile">View profile details</a>
      </div>
      <div class="portal-restricted" style="margin-top: 18px;">
        This panel is tuned for daily approvals, status review, and account oversight with a brighter white-and-blue operator interface.
      </div>
    </article>
  </section>
@endsection
