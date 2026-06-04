@extends('admin.layouts.app', ['title' => 'Students'])

@section('content')
  <section class="portal-page-head"><h1>Student Management</h1><p>Add, edit, delete, filter, and open student profiles.</p></section>
  <div class="portal-admin-toolbar"><form method="get"><input name="search" value="{{ request('search') }}" placeholder="Search student"><select name="campus_id"><option value="">All campuses</option>@foreach($campuses as $campus)<option value="{{ $campus->id }}" @selected(request('campus_id') == $campus->id)>{{ $campus->name }}</option>@endforeach</select><button class="portal-button-ghost">Filter</button></form><button class="portal-button" onclick="document.getElementById('createStudent').showModal()">Add student</button></div>
  <section class="portal-card"><table class="portal-table"><thead><tr><th>Student</th><th>Campus</th><th>Course</th><th>Roll</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    @foreach($students as $student)
      <tr><td><strong>{{ $student->user->name }}</strong><div class="portal-table-note">{{ $student->user->email }}</div></td><td>{{ $student->campus->name }}</td><td>{{ $student->course->name }}</td><td>{{ $student->roll_number }}</td><td><span class="portal-badge {{ $student->status }}">{{ $student->status }}</span></td><td class="portal-actions"><a class="portal-button-ghost portal-button-small" href="{{ route(($adminRoutePrefix ?? 'admin').'.students.show', $student) }}">Profile</a><button class="portal-button-ghost portal-button-small" onclick="document.getElementById('editStudent{{ $student->id }}').showModal()">Edit</button><form method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.students.destroy', $student) }}">@csrf @method('delete')<button class="portal-button-danger portal-button-small">Delete</button></form></td></tr>
      @include('admin.students.partials.form', ['dialogId' => 'editStudent'.$student->id, 'student' => $student, 'campuses' => $campuses, 'departments' => $departments, 'courses' => $courses, 'action' => route(($adminRoutePrefix ?? 'admin').'.students.update', $student), 'method' => 'put'])
    @endforeach
  </tbody></table><div class="portal-pagination">{{ $students->links() }}</div></section>
  @include('admin.students.partials.form', ['dialogId' => 'createStudent', 'student' => null, 'campuses' => $campuses, 'departments' => $departments, 'courses' => $courses, 'action' => route(($adminRoutePrefix ?? 'admin').'.students.store'), 'method' => 'post'])
@endsection
