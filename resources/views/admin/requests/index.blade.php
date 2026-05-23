@extends('admin.layouts.app', ['title' => 'Demo Requests'])

@section('content')
  <section class="portal-page-head">
    <h1>Demo request management</h1>
    <p>Search, filter, approve, reject, or remove incoming demo requests with secure admin actions.</p>
  </section>

  <section class="portal-card" style="margin-bottom: 18px;">
    <form class="portal-filter-form" method="get" action="{{ route('admin.demo-requests.index') }}">
      <label class="portal-field">
        <span>Search</span>
        <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="College, admin, email, phone">
      </label>
      <label class="portal-field">
        <span>Status</span>
        <select name="status">
          <option value="">All statuses</option>
          @foreach (['Pending', 'Approved', 'Rejected', 'Contacted'] as $status)
            <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ $status }}</option>
          @endforeach
        </select>
      </label>
      <div class="portal-field">
        <span>&nbsp;</span>
        <button class="portal-button" type="submit">Apply filters</button>
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
          <tr>
            <td>
              <strong>{{ $demoRequest->college_name }}</strong>
              <div class="portal-muted">{{ $demoRequest->student_strength }} students</div>
              @if ($demoRequest->requirements)
                <div class="portal-muted" style="margin-top: 6px;">{{ \Illuminate\Support\Str::limit($demoRequest->requirements, 90) }}</div>
              @endif
            </td>
            <td>{{ $demoRequest->admin_name }}</td>
            <td>{{ $demoRequest->email }}</td>
            <td>{{ $demoRequest->phone }}</td>
            <td><span class="portal-badge {{ strtolower($demoRequest->status) }}">{{ $demoRequest->status }}</span></td>
            <td>{{ $demoRequest->created_at->format('d M Y') }}</td>
            <td>
              @if ($demoRequest->demoUser)
                <div><strong>{{ $demoRequest->demoUser->username }}</strong></div>
                <div class="portal-muted">Expires {{ $demoRequest->demoUser->expiry_date->format('d M Y') }}</div>
              @else
                <span class="portal-muted">Not generated</span>
              @endif
            </td>
            <td>
              <div class="portal-actions">
                <form class="portal-inline-form" method="post" action="{{ route('admin.demo-requests.approve', $demoRequest) }}" onsubmit="return confirm('Approve this demo request and send credentials?');">
                  @csrf
                  <button class="portal-button" type="submit">Approve</button>
                </form>
                <form class="portal-inline-form" method="post" action="{{ route('admin.demo-requests.reject', $demoRequest) }}" onsubmit="return confirm('Reject this demo request and notify the user?');">
                  @csrf
                  <button class="portal-button-ghost" type="submit">Reject</button>
                </form>
                <form class="portal-inline-form" method="post" action="{{ route('admin.demo-requests.contacted', $demoRequest) }}" onsubmit="return confirm('Mark this request as contacted?');">
                  @csrf
                  <button class="portal-button-ghost" type="submit">Contacted</button>
                </form>
                <form class="portal-inline-form" method="post" action="{{ route('admin.demo-requests.destroy', $demoRequest) }}" onsubmit="return confirm('Delete this demo request permanently?');">
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
