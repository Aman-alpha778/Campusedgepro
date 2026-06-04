@extends('admin.layouts.app', ['title' => 'Courses'])

@section('content')
  <section class="portal-page-head"><h1>Course Management</h1><p>Add courses, assign departments, and manage semester counts.</p></section>
  <div class="portal-admin-toolbar"><span></span><button class="portal-button" onclick="document.getElementById('createCourse').showModal()">Add course</button></div>
  <section class="portal-card"><table class="portal-table"><thead><tr><th>Course</th><th>Department</th><th>Duration</th><th>Semesters</th><th>Students</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    @foreach($courses as $course)
      <tr><td><strong>{{ $course->name }}</strong><div class="portal-table-note">{{ $course->code }}</div></td><td>{{ $course->department->name }}</td><td>{{ $course->duration }}</td><td>{{ $course->total_semesters }}</td><td>{{ $course->students_count }}</td><td><span class="portal-badge {{ $course->status }}">{{ $course->status }}</span></td><td class="portal-actions"><button class="portal-button-ghost portal-button-small" onclick="document.getElementById('editCourse{{ $course->id }}').showModal()">Edit</button><form method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.courses.destroy', $course) }}">@csrf @method('delete')<button class="portal-button-danger portal-button-small">Delete</button></form></td></tr>
      @include('admin.courses.partials.form', ['dialogId' => 'editCourse'.$course->id, 'course' => $course, 'departments' => $departments, 'action' => route(($adminRoutePrefix ?? 'admin').'.courses.update', $course), 'method' => 'put'])
    @endforeach
  </tbody></table><div class="portal-pagination">{{ $courses->links() }}</div></section>
  @include('admin.courses.partials.form', ['dialogId' => 'createCourse', 'course' => null, 'departments' => $departments, 'action' => route(($adminRoutePrefix ?? 'admin').'.courses.store'), 'method' => 'post'])
@endsection
