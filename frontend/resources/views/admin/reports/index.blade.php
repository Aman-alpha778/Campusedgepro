@extends('admin.layouts.app', ['title' => 'Reports'])

@section('content')
  <section class="portal-page-head"><h1>Report Management</h1><p>Dynamic student, faculty, fee, attendance, and admission reports.</p></section>
  <div class="portal-admin-toolbar"><form method="get"><select name="type">@foreach(['students' => 'Student Report', 'faculty' => 'Faculty Report', 'fees' => 'Fee Report', 'attendance' => 'Attendance Report', 'admissions' => 'Admission Report'] as $value => $label)<option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>@endforeach</select><button class="portal-button-ghost">Run report</button></form><div class="portal-actions"><a class="portal-button-ghost" href="{{ route(($adminRoutePrefix ?? 'admin').'.reports.export', ['type' => $type, 'format' => 'excel']) }}">Export Excel</a><a class="portal-button-ghost" href="{{ route(($adminRoutePrefix ?? 'admin').'.reports.export', ['type' => $type, 'format' => 'pdf']) }}">Export PDF</a></div></div>
  <section class="portal-card"><table class="portal-table"><thead><tr><th>ID</th><th>Primary</th><th>Secondary</th><th>Status</th><th>Date</th></tr></thead><tbody>
    @foreach($rows as $row)
      <tr>
        <td>{{ $row->id }}</td>
        <td>{{ $row->user?->name ?? $row->student?->user?->name ?? $row->fee_type ?? $row->title ?? 'Record' }}</td>
        <td>{{ $row->campus?->name ?? $row->department?->name ?? $row->payment_method ?? $row->attendance_date?->format('d M Y') ?? '' }}</td>
        <td>{{ $row->status ?? 'recorded' }}</td>
        <td>{{ ($row->admission_date ?? $row->payment_date ?? $row->created_at)?->format('d M Y') }}</td>
      </tr>
    @endforeach
  </tbody></table><div class="portal-pagination">{{ $rows->links() }}</div></section>
@endsection
