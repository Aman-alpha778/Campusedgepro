@extends('admin.layouts.app', ['title' => 'Faculty'])

@section('content')
  <section class="portal-page-head"><h1>Faculty Management</h1><p>Create faculty profiles and assign departments.</p></section>
  <div class="portal-admin-toolbar"><form method="get"><input name="search" value="{{ request('search') }}" placeholder="Search faculty"><button class="portal-button-ghost">Search</button></form><button class="portal-button" onclick="document.getElementById('createFaculty').showModal()">Add faculty</button></div>
  <section class="portal-card"><table class="portal-table"><thead><tr><th>Faculty</th><th>Department</th><th>Employee ID</th><th>Salary</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    @foreach($faculty as $member)
      <tr><td><strong>{{ $member->user->name }}</strong><div class="portal-table-note">{{ $member->qualification }}</div></td><td>{{ $member->department->name }}</td><td>{{ $member->employee_id }}</td><td>₹{{ number_format($member->salary, 2) }}</td><td><span class="portal-badge {{ $member->status }}">{{ $member->status }}</span></td><td class="portal-actions"><button class="portal-button-ghost portal-button-small" onclick="document.getElementById('editFaculty{{ $member->id }}').showModal()">Edit</button><form method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.faculty.destroy', $member) }}">@csrf @method('delete')<button class="portal-button-danger portal-button-small">Delete</button></form></td></tr>
      @include('admin.faculty.partials.form', ['dialogId' => 'editFaculty'.$member->id, 'member' => $member, 'departments' => $departments, 'action' => route(($adminRoutePrefix ?? 'admin').'.faculty.update', $member), 'method' => 'put'])
    @endforeach
  </tbody></table><div class="portal-pagination">{{ $faculty->links() }}</div></section>
  @include('admin.faculty.partials.form', ['dialogId' => 'createFaculty', 'member' => null, 'departments' => $departments, 'action' => route(($adminRoutePrefix ?? 'admin').'.faculty.store'), 'method' => 'post'])
@endsection
