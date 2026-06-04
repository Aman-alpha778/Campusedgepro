@extends('admin.layouts.app', ['title' => 'Departments'])

@section('content')
  <section class="portal-page-head department-page-head">
    <div>
      <span class="department-kicker">Single Institution ERP</span>
      <h1>Department Management</h1>
      <p>Manage academic departments, HOD ownership, intake capacity, and linked records from one clean workspace.</p>
    </div>
    <button class="portal-button" onclick="document.getElementById('createDepartment').showModal()">Create department</button>
  </section>

  <section class="portal-grid-4 department-stat-grid">
    <article class="portal-stat department-stat-card"><span class="portal-stat-label">Total Departments</span><strong>{{ $stats['total_departments'] }}</strong><div class="portal-stat-note">All academic units.</div></article>
    <article class="portal-stat department-stat-card"><span class="portal-stat-label">Active</span><strong>{{ $stats['active_departments'] }}</strong><div class="portal-stat-note">Available for allocation.</div></article>
    <article class="portal-stat department-stat-card"><span class="portal-stat-label">Inactive</span><strong>{{ $stats['inactive_departments'] }}</strong><div class="portal-stat-note">Temporarily paused.</div></article>
    <article class="portal-stat department-stat-card"><span class="portal-stat-label">Faculty / Students</span><strong>{{ $stats['total_faculty'] }} / {{ $stats['total_students'] }}</strong><div class="portal-stat-note">Linked people records.</div></article>
  </section>

  <section class="portal-card department-directory-card">
    <div class="department-directory-head">
      <div>
        <h2>Department Directory</h2>
        <p>Search, filter, assign HOD, and inspect linked records.</p>
      </div>
      <form class="department-filter-form" method="get">
        <input name="search" value="{{ request('search') }}" placeholder="Search department, code, email">
        <select name="status"><option value="">All statuses</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="inactive" @selected(request('status') === 'inactive')>Inactive</option></select>
        <button class="portal-button-ghost">Filter</button>
      </form>
    </div>

    <div class="department-table-wrap">
      <table class="portal-table department-table">
        <thead><tr><th>#</th><th>Department</th><th>HOD</th><th>Faculty</th><th>Students</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse ($departments as $department)
            <tr>
              <td class="department-row-index">{{ $departments->firstItem() + $loop->index }}</td>
              <td>
                <div class="department-name-cell">
                  <span>{{ strtoupper(substr($department->code, 0, 2)) }}</span>
                  <div>
                    <strong>{{ $department->name }}</strong>
                    <small>{{ $department->code }} · {{ $department->location ?? 'Location not set' }}</small>
                  </div>
                </div>
              </td>
              <td>
                <div class="department-hod-cell">
                  <strong>{{ $department->hod?->name ?? 'Unassigned' }}</strong>
                  <small>{{ $department->email ?? 'No department email' }}</small>
                </div>
              </td>
              <td><span class="department-count-pill">{{ $department->faculty_count }}</span></td>
              <td><span class="department-count-pill">{{ $department->students_count }}</span></td>
              <td><span class="department-status {{ $department->status }}">{{ ucfirst($department->status) }}</span></td>
              <td>
                <div class="department-actions">
                  <a class="portal-button-ghost portal-button-small" href="{{ route($adminRoutePrefix.'.departments.show', $department) }}">View</a>
                  <button class="portal-button-ghost portal-button-small" onclick="document.getElementById('editDepartment{{ $department->id }}').showModal()">Edit</button>
                  <button class="portal-button-ghost portal-button-small" onclick="document.getElementById('assignHod{{ $department->id }}').showModal()">HOD</button>
                  <form method="post" action="{{ route($adminRoutePrefix.'.departments.status', $department) }}">@csrf @method('patch')<input type="hidden" name="status" value="{{ $department->status === 'active' ? 'inactive' : 'active' }}"><button class="portal-button-ghost portal-button-small">{{ $department->status === 'active' ? 'Deactivate' : 'Activate' }}</button></form>
                  <form method="post" action="{{ route($adminRoutePrefix.'.departments.destroy', $department) }}">@csrf @method('delete')<button class="portal-button-danger portal-button-small">Delete</button></form>
                </div>
              </td>
            </tr>
            @include('admin.departments.partials.form', ['dialogId' => 'editDepartment'.$department->id, 'department' => $department, 'hodUsers' => $hodUsers, 'action' => route($adminRoutePrefix.'.departments.update', $department), 'method' => 'put'])
            <dialog class="portal-modal" id="assignHod{{ $department->id }}"><div class="portal-modal-body"><div class="portal-card-head"><div><h2>Assign HOD</h2><p>{{ $department->name }}</p></div><button type="button" class="portal-button-ghost" onclick="this.closest('dialog').close()">Close</button></div><form class="portal-form" method="post" action="{{ route($adminRoutePrefix.'.departments.assign-hod', $department) }}">@csrf<label class="portal-field"><span>HOD</span><select name="hod_id"><option value="">Unassigned</option>@foreach($hodUsers as $user)<option value="{{ $user->id }}" @selected($department->hod_id === $user->id)>{{ $user->name }}</option>@endforeach</select></label><button class="portal-button">Save HOD</button></form></div></dialog>
          @empty
            <tr><td class="department-empty-row" colspan="7">No departments found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="portal-pagination">{{ $departments->links() }}</div>
  </section>

  @include('admin.departments.partials.form', ['dialogId' => 'createDepartment', 'department' => null, 'hodUsers' => $hodUsers, 'action' => route($adminRoutePrefix.'.departments.store'), 'method' => 'post'])
@endsection
