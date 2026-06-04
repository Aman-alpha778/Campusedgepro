@extends('admin.layouts.app', ['title' => 'Demo Requests'])

@section('content')
  <section class="portal-page-head">
    <h1>Demo request management</h1>
    <p>Search, filter, approve, reject, or remove incoming demo requests with secure admin actions.</p>
  </section>

  <section class="portal-card" style="margin-bottom: 18px;">
    <form class="portal-filter-form" method="get" action="{{ route(($adminRoutePrefix ?? 'admin').'.demo-requests.index') }}">
      <label class="portal-field">
        <span>Search</span>
        <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="College, admin, email, phone">
      </label>
      <label class="portal-field">
        <span>Status</span>
        <select name="status">
          <option value="">All statuses</option>
          @foreach (['Pending', 'Approved', 'Rejected'] as $status)
            <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ $status }}</option>
          @endforeach
        </select>
      </label>
      <div class="portal-field portal-filter-actions">
        <span>&nbsp;</span>
        <div>
          <button class="portal-button portal-uniform-button" type="submit">Search</button>
          @if ($filters['search'] !== '' || $filters['status'] !== '')
            <a class="portal-button-ghost portal-uniform-button" href="{{ route(($adminRoutePrefix ?? 'admin').'.demo-requests.index') }}">Reset</a>
          @endif
        </div>
      </div>
    </form>
  </section>

  <section class="portal-table-wrap">
    <table class="portal-table">
      <thead>
        <tr>
          <th>College Name</th>
          <th>Admin Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Status</th>
          <th>Date</th>
          <th>Credentials</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($demoRequests as $demoRequest)
          @php
            $canResendAccess = $demoRequest->status === 'Approved' || $demoRequest->demoUser;
          @endphp
          <tr>
            <td>
              <strong>{{ $demoRequest->college_name }}</strong>
              <div class="portal-muted">{{ $demoRequest->student_strength }} students</div>
              @if ($demoRequest->requirements)
                <div class="portal-muted" style="margin-top: 6px;">{{ \Illuminate\Support\Str::limit($demoRequest->requirements, 90) }}</div>
              @endif
            </td>
            <td class="portal-table-nowrap">{{ $demoRequest->admin_name }}</td>
            <td class="portal-table-email">{{ $demoRequest->email }}</td>
            <td class="portal-table-nowrap">{{ $demoRequest->phone }}</td>
            <td class="portal-table-center"><span class="portal-badge {{ strtolower($demoRequest->status) }}">{{ $demoRequest->status }}</span></td>
            <td class="portal-table-nowrap">{{ $demoRequest->created_at->format('d M Y') }}</td>
            <td>
              @if ($demoRequest->demoUser)
                <div><strong>{{ $demoRequest->demoUser->username }}</strong></div>
                <div class="portal-table-note">Expires {{ $demoRequest->demoUser->expiry_date->format('d M Y') }}</div>
                <form class="portal-inline-form portal-resend-form" method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.demo-requests.resend-access', $demoRequest) }}" onsubmit="return confirm('Generate a new temporary password and resend access details?');">
                  @csrf
                  <button class="portal-button-ghost portal-button-small" type="submit">Resend Access</button>
                </form>
              @elseif ($demoRequest->status === 'Approved')
                <form class="portal-inline-form portal-resend-form" method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.demo-requests.resend-access', $demoRequest) }}" onsubmit="return confirm('Generate demo credentials and resend access details?');">
                  @csrf
                  <button class="portal-button-ghost portal-button-small" type="submit">Resend Access</button>
                </form>
              @else
                <span class="portal-muted">Not generated</span>
              @endif
            </td>
            <td>
              <div class="portal-actions">
                @if ($demoRequest->status === 'Approved')
                  <button class="portal-button portal-button-muted" type="button" disabled>Approved</button>
                @elseif ($demoRequest->status === 'Rejected')
                  <button class="portal-button portal-button-muted" type="button" disabled>Approve</button>
                @else
                  <form class="portal-inline-form" method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.demo-requests.approve', $demoRequest) }}" onsubmit="return confirm('Approve this demo request and send credentials?');">
                    @csrf
                    <button class="portal-button" type="submit">Approve</button>
                  </form>
                @endif
                @if ($demoRequest->status === 'Rejected')
                  <button class="portal-button-ghost portal-button-muted" type="button" disabled>Rejected</button>
                @elseif ($demoRequest->status === 'Approved')
                  <button class="portal-button-ghost portal-button-muted" type="button" disabled>Reject</button>
                @else
                  <form class="portal-inline-form" method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.demo-requests.reject', $demoRequest) }}" onsubmit="return confirm('Reject this demo request and notify the user?');">
                    @csrf
                    <button class="portal-button-ghost" type="submit">Reject</button>
                  </form>
                @endif
                @if ($canResendAccess)
                  <form class="portal-inline-form" method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.demo-requests.resend-access', $demoRequest) }}" onsubmit="return confirm('Generate a new temporary password and resend access details?');">
                    @csrf
                    <button class="portal-button-ghost" type="submit">Resend Access</button>
                  </form>
                @endif
                <form class="portal-inline-form" method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.demo-requests.destroy', $demoRequest) }}" onsubmit="return confirm('Delete this demo request permanently?');">
                  @csrf
                  @method('delete')
                  <button class="portal-button-danger" type="submit">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8">No demo requests found for the selected filters.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="portal-pagination">
      {{ $demoRequests->links() }}
    </div>
  </section>
@endsection
