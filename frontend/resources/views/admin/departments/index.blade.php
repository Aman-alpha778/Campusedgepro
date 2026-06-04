@extends('admin.layouts.app', ['title' => 'Departments'])

@section('content')
  <section class="portal-page-head"><h1>Department Management</h1><p>Create departments and assign HOD users.</p></section>
  <div class="portal-admin-toolbar"><span></span><button class="portal-button" onclick="document.getElementById('createDepartment').showModal()">Create department</button></div>
  <section class="portal-card"><table class="portal-table"><thead><tr><th>Name</th><th>Campus</th><th>HOD</th><th>Courses</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    @foreach ($departments as $department)
      <tr><td><strong>{{ $department->name }}</strong><div class="portal-table-note">{{ $department->code }}</div></td><td>{{ $department->campus->name }}</td><td>{{ $department->hod?->name ?? 'Unassigned' }}</td><td>{{ $department->courses_count }}</td><td><span class="portal-badge {{ $department->status }}">{{ $department->status }}</span></td><td class="portal-actions"><button class="portal-button-ghost portal-button-small" onclick="document.getElementById('editDepartment{{ $department->id }}').showModal()">Edit</button><form method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.departments.destroy', $department) }}">@csrf @method('delete')<button class="portal-button-danger portal-button-small">Delete</button></form></td></tr>
      @include('admin.departments.partials.form', ['dialogId' => 'editDepartment'.$department->id, 'department' => $department, 'campuses' => $campuses, 'hodUsers' => $hodUsers, 'action' => route(($adminRoutePrefix ?? 'admin').'.departments.update', $department), 'method' => 'put'])
    @endforeach
  </tbody></table><div class="portal-pagination">{{ $departments->links() }}</div></section>
  @include('admin.departments.partials.form', ['dialogId' => 'createDepartment', 'department' => null, 'campuses' => $campuses, 'hodUsers' => $hodUsers, 'action' => route(($adminRoutePrefix ?? 'admin').'.departments.store'), 'method' => 'post'])
@endsection
