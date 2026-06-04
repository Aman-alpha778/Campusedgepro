@extends('admin.layouts.app', ['title' => $campus->name])

@section('content')
  <section class="portal-page-head"><h1>{{ $campus->name }}</h1><p>{{ $campus->address }}</p></section>
  <section class="portal-grid-4">
    <article class="portal-stat"><span class="portal-stat-label">Code</span><strong>{{ $campus->code }}</strong><div class="portal-stat-note">{{ $campus->email }}</div></article>
    <article class="portal-stat"><span class="portal-stat-label">Departments</span><strong>{{ $campus->departments->count() }}</strong></article>
    <article class="portal-stat"><span class="portal-stat-label">Students</span><strong>{{ $campus->students->count() }}</strong></article>
    <article class="portal-stat"><span class="portal-stat-label">Status</span><strong>{{ ucfirst($campus->status) }}</strong></article>
  </section>
  <section class="portal-card">
    <div class="portal-card-head"><div><h2>Departments and courses</h2><p>Academic structure connected to this campus.</p></div></div>
    <table class="portal-table"><thead><tr><th>Department</th><th>HOD</th><th>Courses</th></tr></thead><tbody>
      @foreach ($campus->departments as $department)
        <tr><td>{{ $department->name }}</td><td>{{ $department->hod?->name ?? 'Unassigned' }}</td><td>{{ $department->courses->pluck('name')->join(', ') }}</td></tr>
      @endforeach
    </tbody></table>
  </section>
@endsection
