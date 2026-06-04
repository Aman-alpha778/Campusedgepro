@extends('admin.layouts.app', ['title' => 'Super Admin Dashboard'])

@php
  $maxAdmission = max(1, collect($admissionChart)->max('value'));
  $maxFee = max(1, collect($feeChart)->max('value'));
  $formatDashboardValue = function (float $value, string $type = 'number'): string {
    if ($type === 'currency') {
      return '&#8377;'.number_format($value, 0);
    }

    if ($type === 'percent') {
      return number_format($value, 1).'%';
    }

    return number_format($value, 0);
  };
@endphp

@section('content')
  <section class="super-admin-dashboard-hero">
    <div>
      <span class="super-admin-eyebrow">Welcome back</span>
      <h1>Super Admin Dashboard</h1>
      <p>Real-time academic, finance, and attendance intelligence from your database.</p>
    </div>
    <div class="super-admin-hero-status">
      <span>Database synced</span>
      <strong>{{ now()->format('d M Y') }}</strong>
    </div>
  </section>

  <section class="portal-grid-4 super-admin-stat-grid">
    @foreach ($cards as $card)
      @php
        $counterPrefix = ($card['format'] ?? null) === 'currency' ? '&#8377;' : '';
        $counterSuffix = $card['suffix'] ?? '';
        $counterDecimals = $card['decimals'] ?? 0;
        $cardIcon = collect(explode(' ', $card['label']))
          ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
          ->take(2)
          ->join('');
        $sparkPoints = count($card['details'] ?? []) > 0 ? $card['details'] : [['label' => 'No data', 'value' => 0, 'format' => 'number']];
        $sparkMax = max(1, collect($sparkPoints)->max('value'));
      @endphp
      <article class="portal-stat super-admin-stat-card tone-{{ $card['tone'] ?? 'blue' }}">
        <div class="super-admin-stat-top">
          <span class="super-admin-stat-icon">{{ $cardIcon }}</span>
          <span class="portal-stat-label">{{ $card['label'] }}</span>
        </div>
        <strong
          data-counter-target="{{ $card['value'] }}"
          data-counter-prefix="{!! $counterPrefix !!}"
          data-counter-suffix="{{ $counterSuffix }}"
          data-counter-decimals="{{ $counterDecimals }}"
          data-counter-separator="true"
        >{!! $counterPrefix !!}0{{ $counterSuffix }}</strong>
        <div class="super-admin-mini-chart" aria-hidden="true">
          @foreach ($sparkPoints as $point)
            <i data-live-height="{{ $point['value'] > 0 ? max(8, ($point['value'] / $sparkMax) * 100) : 0 }}" style="height: 0"></i>
          @endforeach
        </div>
        <div class="super-admin-card-details">
          @foreach ($sparkPoints as $point)
            @php
              $detailValue = ($point['format'] ?? 'number') === 'currency'
                ? '&#8377;'.number_format($point['value'], 0)
                : number_format($point['value'], 0);
              $detailWidth = $point['value'] > 0 ? ($point['value'] / $sparkMax) * 100 : 0;
            @endphp
            <div class="super-admin-card-detail-row">
              <span>{{ $point['label'] }}</span>
              <div><i data-live-width="{{ $detailWidth }}" style="width: 0"></i></div>
              <strong>{!! $detailValue !!}</strong>
            </div>
          @endforeach
        </div>
        <div class="portal-stat-note">{{ $card['note'] }}</div>
      </article>
    @endforeach
  </section>

  <section class="portal-grid-2 super-admin-dashboard-panels">
    <article class="portal-card super-admin-chart-card">
      <div class="portal-card-head"><div><h2>Student Admission Chart</h2><p>Monthly admission count from student records.</p></div></div>
      <div class="super-admin-live-chart">
        <div class="super-admin-chart-scale"><span>{{ number_format($maxAdmission) }}</span><span>0</span></div>
        <div class="portal-chart"><div class="portal-chart-bars">
          @foreach ($admissionChart as $point)
            @php $height = $point['value'] > 0 ? max(8, ($point['value'] / $maxAdmission) * 100) : 0; @endphp
            <div class="super-admin-bar-wrap">
              <span class="super-admin-bar-value">{{ number_format($point['value']) }}</span>
              <div title="{{ $point['label'] }}: {{ number_format($point['value']) }}" class="portal-chart-bar" data-live-height="{{ $height }}" style="height: 0"></div>
              <span class="super-admin-bar-label">{{ $point['label'] }}</span>
            </div>
          @endforeach
        </div></div>
      </div>
      <div class="super-admin-progress-list">
        @foreach ($admissionChart as $point)
          @php $width = $point['value'] > 0 ? ($point['value'] / $maxAdmission) * 100 : 0; @endphp
          <div class="super-admin-progress-row">
            <span>{{ $point['label'] }}</span>
            <div><i data-live-width="{{ $width }}" style="width: 0"></i></div>
            <strong>{{ number_format($point['value']) }}</strong>
          </div>
        @endforeach
      </div>
    </article>
    <article class="portal-card super-admin-chart-card">
      <div class="portal-card-head"><div><h2>Fee Collection Chart</h2><p>Monthly paid amount from payment records.</p></div></div>
      <div class="super-admin-live-chart">
        <div class="super-admin-chart-scale"><span>{!! $formatDashboardValue($maxFee, 'currency') !!}</span><span>&#8377;0</span></div>
        <div class="portal-chart"><div class="portal-chart-bars">
          @foreach ($feeChart as $point)
            @php $height = $point['value'] > 0 ? max(8, ($point['value'] / $maxFee) * 100) : 0; @endphp
            <div class="super-admin-bar-wrap">
              <span class="super-admin-bar-value">{!! $formatDashboardValue($point['value'], 'currency') !!}</span>
              <div title="{{ $point['label'] }}: Rs {{ number_format($point['value'], 2) }}" class="portal-chart-bar" data-live-height="{{ $height }}" style="height: 0"></div>
              <span class="super-admin-bar-label">{{ $point['label'] }}</span>
            </div>
          @endforeach
        </div></div>
      </div>
      <div class="super-admin-progress-list">
        @foreach ($feeChart as $point)
          @php $width = $point['value'] > 0 ? ($point['value'] / $maxFee) * 100 : 0; @endphp
          <div class="super-admin-progress-row">
            <span>{{ $point['label'] }}</span>
            <div><i data-live-width="{{ $width }}" style="width: 0"></i></div>
            <strong>{!! $formatDashboardValue($point['value'], 'currency') !!}</strong>
          </div>
        @endforeach
      </div>
    </article>
  </section>

  <section class="portal-card super-admin-chart-card super-admin-attendance-panel">
    <div class="portal-card-head"><div><h2>Attendance Percentage Chart</h2><p>Daily present percentage from attendance records.</p></div></div>
    <div class="super-admin-live-chart">
      <div class="super-admin-chart-scale"><span>100%</span><span>0%</span></div>
      <div class="portal-chart"><div class="portal-chart-bars">
        @foreach ($attendanceChart as $point)
          @php $height = $point['value'] > 0 ? max(8, min(100, $point['value'])) : 0; @endphp
          <div class="super-admin-bar-wrap">
            <span class="super-admin-bar-value">{{ number_format($point['value'], 1) }}%</span>
            <div title="{{ $point['label'] }}: {{ $point['value'] }}%" class="portal-chart-bar" data-live-height="{{ $height }}" style="height: 0"></div>
            <span class="super-admin-bar-label">{{ $point['label'] }}</span>
          </div>
        @endforeach
      </div></div>
    </div>
    <div class="super-admin-progress-list super-admin-progress-list-compact">
      @foreach ($attendanceChart as $point)
        <div class="super-admin-progress-row">
          <span>{{ $point['label'] }}</span>
          <div><i data-live-width="{{ min(100, max(0, $point['value'])) }}" style="width: 0"></i></div>
          <strong>{{ number_format($point['value'], 1) }}%</strong>
        </div>
      @endforeach
    </div>
  </section>

  <section class="portal-grid-2 super-admin-dashboard-panels">
    <article class="portal-card super-admin-table-card">
      <div class="portal-card-head"><div><h2>Recent Students</h2><p>Latest admission records.</p></div></div>
      <table class="portal-table">
        <thead><tr><th>Student</th><th>Campus</th><th>Course</th><th>Date</th></tr></thead>
        <tbody>
          @forelse ($recentStudents as $student)
            <tr><td>{{ $student->user->name }}</td><td>{{ $student->campus->name }}</td><td>{{ $student->course->name }}</td><td>{{ $student->admission_date?->format('d M Y') }}</td></tr>
          @empty
            <tr><td colspan="4">No students found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </article>
    <article class="portal-card super-admin-table-card">
      <div class="portal-card-head"><div><h2>Recent Payments</h2><p>Latest fee collection activity.</p></div></div>
      <table class="portal-table">
        <thead><tr><th>Student</th><th>Amount</th><th>Method</th><th>Date</th></tr></thead>
        <tbody>
          @forelse ($recentPayments as $payment)
            <tr><td>{{ $payment->fee->student->user->name }}</td><td>&#8377;{{ number_format($payment->amount, 2) }}</td><td>{{ $payment->payment_method }}</td><td>{{ $payment->payment_date?->format('d M Y') }}</td></tr>
          @empty
            <tr><td colspan="4">No payments found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </article>
  </section>
@endsection
