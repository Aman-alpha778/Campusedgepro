@extends('admin.layouts.app', ['title' => 'Activity Logs'])

@section('content')
  <section class="portal-page-head"><h1>Activity Logs</h1><p>Login, create, update, and delete events captured from admin actions.</p></section>
  <div class="portal-admin-toolbar"><form method="get"><input name="module" value="{{ request('module') }}" placeholder="Module"><input name="action" value="{{ request('action') }}" placeholder="Action"><button class="portal-button-ghost">Filter</button></form></div>
  <section class="portal-card"><table class="portal-table"><thead><tr><th>User</th><th>Action</th><th>Module</th><th>Description</th><th>IP</th><th>Date</th></tr></thead><tbody>@foreach($logs as $log)<tr><td>{{ $log->user?->name ?? 'System' }}</td><td>{{ $log->action }}</td><td>{{ $log->module }}</td><td>{{ $log->description }}</td><td>{{ $log->ip_address }}</td><td>{{ $log->created_at?->format('d M Y H:i') }}</td></tr>@endforeach</tbody></table><div class="portal-pagination">{{ $logs->links() }}</div></section>
@endsection
