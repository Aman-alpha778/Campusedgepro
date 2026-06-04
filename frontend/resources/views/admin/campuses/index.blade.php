@extends('admin.layouts.app', ['title' => 'Campuses'])

@section('content')
  <section class="portal-page-head"><h1>Campus Management</h1><p>Create, update, activate, deactivate, and inspect campus records.</p></section>
  <div class="portal-admin-toolbar">
    <form method="get">
      <input name="search" value="{{ request('search') }}" placeholder="Search campuses">
      <select name="status"><option value="">All statuses</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="inactive" @selected(request('status') === 'inactive')>Inactive</option></select>
      <button class="portal-button-ghost" type="submit">Filter</button>
    </form>
    <button class="portal-button" onclick="document.getElementById('createCampus').showModal()">Add campus</button>
  </div>
  <section class="portal-card">
    <table class="portal-table">
      <thead><tr><th>Name</th><th>Code</th><th>Location</th><th>Departments</th><th>Students</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse ($campuses as $campus)
          <tr>
            <td><strong>{{ $campus->name }}</strong><div class="portal-table-note">{{ $campus->email }}</div></td>
            <td>{{ $campus->code }}</td><td>{{ $campus->city }}, {{ $campus->state }}</td><td>{{ $campus->departments_count }}</td><td>{{ $campus->students_count }}</td>
            <td><span class="portal-badge {{ $campus->status }}">{{ $campus->status }}</span></td>
            <td class="portal-actions">
              <a class="portal-button-ghost portal-button-small" href="{{ route(($adminRoutePrefix ?? 'admin').'.campuses.show', $campus) }}">View</a>
              <button class="portal-button-ghost portal-button-small" onclick="document.getElementById('editCampus{{ $campus->id }}').showModal()">Edit</button>
              <form method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.campuses.toggle', $campus) }}">@csrf<button class="portal-button-ghost portal-button-small">{{ $campus->status === 'active' ? 'Deactivate' : 'Activate' }}</button></form>
              <form method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.campuses.destroy', $campus) }}">@csrf @method('delete')<button class="portal-button-danger portal-button-small">Delete</button></form>
            </td>
          </tr>
          @include('admin.campuses.partials.form', ['dialogId' => 'editCampus'.$campus->id, 'campus' => $campus, 'action' => route(($adminRoutePrefix ?? 'admin').'.campuses.update', $campus), 'method' => 'put'])
        @empty
          <tr><td colspan="7">No campuses found.</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="portal-pagination">{{ $campuses->links() }}</div>
  </section>
  @include('admin.campuses.partials.form', ['dialogId' => 'createCampus', 'campus' => null, 'action' => route(($adminRoutePrefix ?? 'admin').'.campuses.store'), 'method' => 'post'])
@endsection
