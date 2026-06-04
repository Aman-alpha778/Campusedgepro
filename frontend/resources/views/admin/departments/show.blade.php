@extends('admin.layouts.app', ['title' => $department->name])

@section('content')
  <section class="department-detail-hero">
    <div class="department-detail-mark">{{ strtoupper(substr($department->code, 0, 2)) }}</div>
    <div>
      <span class="department-kicker">Department Overview</span>
      <h1>{{ $department->name }}</h1>
      <p>{{ $department->description ?: 'Department overview and linked academic records.' }}</p>
      <div class="department-detail-meta">
        <span>{{ $department->code }}</span>
        <span>{{ ucfirst($department->status) }}</span>
        <span>Intake {{ $department->total_intake }}</span>
      </div>
    </div>
    <a class="portal-button-ghost" href="{{ route($adminRoutePrefix.'.departments.index') }}">Back</a>
  </section>

  <section class="portal-grid-4 department-stat-grid">
    <article class="portal-stat department-stat-card"><span class="portal-stat-label">Students</span><strong>{{ $department->students_count }}</strong></article>
    <article class="portal-stat department-stat-card"><span class="portal-stat-label">Faculty</span><strong>{{ $department->faculty_count }}</strong></article>
    <article class="portal-stat department-stat-card"><span class="portal-stat-label">Courses</span><strong>{{ $department->courses_count }}</strong></article>
    <article class="portal-stat department-stat-card"><span class="portal-stat-label">Subjects</span><strong>{{ $department->subjects_count }}</strong></article>
  </section>

  <section class="portal-card department-directory-card">
    <div class="portal-card-head"><div><h2>Overview</h2><p>Core department information.</p></div></div>
    <div class="department-overview-grid">
      <div><strong>HOD</strong><span>{{ $department->hod?->name ?? 'Unassigned' }}</span></div>
      <div><strong>Email</strong><span>{{ $department->email ?? 'Not set' }}</span></div>
      <div><strong>Phone</strong><span>{{ $department->phone ?? 'Not set' }}</span></div>
      <div><strong>Location</strong><span>{{ $department->location ?? 'Not set' }}</span></div>
      <div><strong>Established</strong><span>{{ $department->established_year ?? 'Not set' }}</span></div>
      <div><strong>Intake Capacity</strong><span>{{ $department->total_intake }}</span></div>
    </div>
  </section>

  <section class="portal-grid-2 department-detail-grid">
    <article class="portal-card department-mini-card"><h2>Faculty</h2><table class="portal-table"><thead><tr><th>Name</th><th>Employee</th><th>Status</th></tr></thead><tbody>@forelse($department->faculty as $member)<tr><td>{{ $member->user->name }}</td><td>{{ $member->employee_id }}</td><td><span class="department-status {{ $member->status }}">{{ ucfirst($member->status) }}</span></td></tr>@empty<tr><td colspan="3">No faculty linked.</td></tr>@endforelse</tbody></table></article>
    <article class="portal-card department-mini-card"><h2>Students</h2><table class="portal-table"><thead><tr><th>Name</th><th>Roll</th><th>Status</th></tr></thead><tbody>@forelse($department->students as $student)<tr><td>{{ $student->user->name }}</td><td>{{ $student->roll_number }}</td><td><span class="department-status {{ $student->status }}">{{ ucfirst($student->status) }}</span></td></tr>@empty<tr><td colspan="3">No students linked.</td></tr>@endforelse</tbody></table></article>
  </section>

  <section class="portal-grid-2 department-detail-grid">
    <article class="portal-card department-mini-card"><h2>Courses</h2><table class="portal-table"><thead><tr><th>Name</th><th>Code</th><th>Status</th></tr></thead><tbody>@forelse($department->courses as $course)<tr><td>{{ $course->name }}</td><td>{{ $course->code }}</td><td><span class="department-status {{ $course->status }}">{{ ucfirst($course->status) }}</span></td></tr>@empty<tr><td colspan="3">No courses linked.</td></tr>@endforelse</tbody></table></article>
    <article class="portal-card department-mini-card"><h2>Subjects</h2><table class="portal-table"><thead><tr><th>Name</th><th>Code</th><th>Status</th></tr></thead><tbody>@forelse($department->subjects as $subject)<tr><td>{{ $subject->name }}</td><td>{{ $subject->code }}</td><td><span class="department-status {{ $subject->status }}">{{ ucfirst($subject->status) }}</span></td></tr>@empty<tr><td colspan="3">No subjects linked.</td></tr>@endforelse</tbody></table></article>
  </section>

  <section class="portal-card department-directory-card"><h2>Activity Logs</h2><table class="portal-table"><thead><tr><th>Old HOD</th><th>New HOD</th><th>Changed By</th><th>Date</th></tr></thead><tbody>@forelse($department->hodHistory as $history)<tr><td>{{ $history->oldHod?->name ?? 'Unassigned' }}</td><td>{{ $history->newHod?->name ?? 'Unassigned' }}</td><td>{{ $history->changedBy?->name ?? 'System' }}</td><td>{{ $history->created_at?->format('d M Y h:i A') }}</td></tr>@empty<tr><td colspan="4">No HOD changes recorded.</td></tr>@endforelse</tbody></table></section>
@endsection
