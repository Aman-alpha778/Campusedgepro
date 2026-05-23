@extends('admin.layouts.app', ['title' => 'Dashboard'])

@section('content')
  <section class="portal-page-head">
    <h1>Demo request dashboard</h1>
    <p>Track incoming requests, monitor approval progress, and manage live demo access from one place.</p>
  </section>

  <section class="portal-grid-4">
    <article class="portal-stat">
      <span class="portal-stat-label">Total Requests</span>
      <strong>{{ $stats['total'] }}</strong>
      <div class="portal-stat-note">All inbound demo submissions across the system.</div>
    </article>
    <article class="portal-stat">
      <span class="portal-stat-label">Pending</span>
      <strong>{{ $stats['pending'] }}</strong>
      <div class="portal-stat-note">Waiting for review and approval action.</div>
    </article>
    <article class="portal-stat">
      <span class="portal-stat-label">Approved</span>
      <strong>{{ $stats['approved'] }}</strong>
      <div class="portal-stat-note">Credentials generated and access granted.</div>
    </article>
    <article class="portal-stat">
      <span class="portal-stat-label">Rejected</span>
      <strong>{{ $stats['rejected'] }}</strong>
      <div class="portal-stat-note">Closed requests that were not approved.</div>
    </article>
  </section>

  <section class="portal-hero-grid">
    <article class="portal-card portal-anchor-card">
      <div class="portal-card-head">
        <div>
          <h2>Operations overview</h2>
          <p class="portal-muted">Review platform activity, prioritize pending requests, and move quickly through approvals.</p>
        </div>
      </div>

      <div class="portal-metric-pair">
        <div class="portal-mini-stat">
          <span class="portal-muted">Fastest win</span>
          <strong>{{ $stats['pending'] > 0 ? 'Review pending approvals' : 'Queue is clear' }}</strong>
        </div>
        <div class="portal-mini-stat">
          <span class="portal-muted">Approval workflow</span>
          <strong>7-day demo cycle</strong>
        </div>
      </div>

      <div class="portal-card-actions">
        <a class="portal-button portal-uniform-button" href="{{ route('admin.demo-requests.index') }}">Open request management</a>
        <a class="portal-button-ghost portal-uniform-button" href="#admin-profile">View admin profile</a>
      </div>
    </article>

    <article class="portal-card portal-anchor-card">
      <div class="portal-card-head">
        <div>
          <h2>Workflow standards</h2>
          <p class="portal-muted">Keep responses consistent every time a demo request is reviewed.</p>
        </div>
      </div>

      <ul class="portal-list">
        <li class="portal-list-item">
          <div>
            <strong>Approval</strong>
            <span class="portal-muted">Generate credentials, set expiry, and email the access details.</span>
          </div>
          <span class="portal-badge approved">Automated</span>
        </li>
        <li class="portal-list-item">
          <div>
            <strong>Contact follow-up</strong>
            <span class="portal-muted">Mark requests as contacted when the team reaches out before approval.</span>
          </div>
          <span class="portal-badge contacted">Tracked</span>
        </li>
      </ul>

      <div class="portal-restricted">
        Demo restrictions remain active across the portal: delete records, report exports, payment tools, and settings changes stay locked for demo users.
      </div>
    </article>
  </section>

  <section class="portal-grid-2">
    <article class="portal-card">
      <div class="portal-card-head">
        <div>
          <h2>Recent requests</h2>
          <p class="portal-muted">Latest demo submissions arriving from the website form.</p>
        </div>
        <a class="portal-button-ghost" href="{{ route('admin.demo-requests.index') }}">View all</a>
      </div>

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
              <td>
                <strong>{{ $request->college_name }}</strong>
                <div class="portal-table-note">{{ $request->email }}</div>
              </td>
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

    <article class="portal-card portal-profile-card" id="admin-profile">
      <div class="portal-card-head">
        <div>
          <h2>Admin profile</h2>
          <p class="portal-muted">Primary administrator for the CampusEdgePro approval workflow.</p>
        </div>
      </div>
      <div class="portal-profile-row">
        <span class="portal-avatar" style="width: 64px; height: 64px; border-radius: 20px;">{{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}</span>
        <div class="portal-profile-meta">
          <strong>{{ auth()->user()?->name }}</strong>
          <span class="portal-muted">{{ auth()->user()?->email }}</span>
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
      <div class="portal-card-actions">
        <a class="portal-button portal-uniform-button" href="{{ route('admin.demo-requests.index') }}">Review pending requests</a>
        <a class="portal-button-ghost portal-uniform-button" href="#top">Back to top</a>
      </div>
    </article>
  </section>
@endsection
