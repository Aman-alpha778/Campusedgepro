@extends('admin.layouts.app', ['title' => 'Super Admin Dashboard'])

@php
  $formatDashboardValue = function (float $value, string $type = 'number'): string {
    if ($type === 'currency') {
      return '&#8377;'.number_format($value, 0);
    }

    if ($type === 'percent') {
      return number_format($value, 1).'%';
    }

    return number_format($value, 0);
  };
  $dashboardGraphColors = ['#00897b', '#f7b313', '#42b8c2', '#ef476f', '#053b52', '#665fb6', '#22c55e', '#f97316', '#2563eb', '#9333ea', '#14b8a6', '#e11d48'];
  $makeDashboardGraphSeries = function ($points, string $fallbackFormat = 'number') use ($dashboardGraphColors) {
    return collect($points)
      ->values()
      ->map(function ($point, int $index) use ($dashboardGraphColors, $fallbackFormat) {
        $name = $point['name'] ?? $point['label'] ?? 'No data';

        return [
          'label' => $point['label'] ?? $name,
          'name' => $name,
          'total' => (float) ($point['total'] ?? $point['value'] ?? 0),
          'format' => $point['format'] ?? $fallbackFormat,
          'color' => $point['color'] ?? $dashboardGraphColors[$index % count($dashboardGraphColors)],
        ];
      })
      ->all();
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
        $isDepartmentListCard = ($card['label'] ?? '') === 'Total Departments';
        $usesDepartmentCombo = ! empty($card['comboChart']['series']);
        $cardGraphSeries = $usesDepartmentCombo
          ? $makeDashboardGraphSeries($card['comboChart']['series'], $card['format'] ?? 'number')
          : $makeDashboardGraphSeries($sparkPoints, $card['format'] ?? 'number');
        $cardGraphMax = max(1, collect($cardGraphSeries)->max('total'));
      @endphp
      <article class="portal-stat super-admin-stat-card tone-{{ $card['tone'] ?? 'blue' }} stat-{{ \Illuminate\Support\Str::slug($card['label'] ?? 'card') }}" style="--bar-count: {{ max(1, count($cardGraphSeries)) }}">
        <div class="super-admin-stat-top">
          <span class="super-admin-stat-icon">{{ $cardIcon }}</span>
          <span class="portal-stat-label">{{ $card['label'] }}</span>
          @if (! $isDepartmentListCard && ! empty($cardGraphSeries))
            <div class="super-admin-stat-top-values" aria-label="{{ $card['label'] }} by department">
              @foreach ($cardGraphSeries as $series)
                <span style="--dept-color: {{ $series['color'] }}">
                  <strong
                    data-counter-target="{{ $series['total'] }}"
                    data-counter-prefix="{{ ($series['format'] ?? 'number') === 'currency' ? '&#8377;' : '' }}"
                    data-counter-suffix="{{ ($series['format'] ?? 'number') === 'percent' ? '%' : '' }}"
                    data-counter-decimals="{{ ($series['format'] ?? 'number') === 'percent' ? 1 : 0 }}"
                    data-counter-separator="true"
                  >{!! ($series['format'] ?? 'number') === 'currency' ? '&#8377;' : '' !!}0{{ ($series['format'] ?? 'number') === 'percent' ? '%' : '' }}</strong>
                </span>
              @endforeach
            </div>
          @endif
        </div>
        <strong
          data-counter-target="{{ $card['value'] }}"
          data-counter-prefix="{!! $counterPrefix !!}"
          data-counter-suffix="{{ $counterSuffix }}"
          data-counter-decimals="{{ $counterDecimals }}"
          data-counter-separator="true"
        >{!! $counterPrefix !!}0{{ $counterSuffix }}</strong>
        @if (! $isDepartmentListCard)
          <div class="student-combo-card" style="--bar-count: {{ max(1, count($cardGraphSeries)) }}">
            <div class="student-combo-visual">
              <div class="student-combo-scale" aria-hidden="true">
                @foreach ([100, 80, 60, 40, 20, 0] as $tick)
                  <span>{!! $formatDashboardValue(($cardGraphMax * $tick) / 100, $cardGraphSeries[0]['format'] ?? 'number') !!}</span>
                @endforeach
              </div>
              <div class="student-combo-grid-lines" aria-hidden="true">
                @foreach ([100, 80, 60, 40, 20, 0] as $tick)
                  <i style="top: {{ 100 - $tick }}%"></i>
                @endforeach
              </div>
              <div class="student-combo-bars">
                @foreach ($cardGraphSeries as $series)
                  @php
                    $barHeight = $series['total'] > 0 ? max(12, ($series['total'] / $cardGraphMax) * 100) : 0;
                    $seriesValue = $formatDashboardValue($series['total'], $series['format']);
                  @endphp
                  <div class="student-combo-bar-item" title="{{ $series['name'] }}: {{ strip_tags($seriesValue) }}">
                    <i data-live-height="{{ $barHeight }}" style="--dept-color: {{ $series['color'] }}; height: 0"></i>
                    <small>{{ $series['label'] }}</small>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="student-combo-legend">
              @foreach ($cardGraphSeries as $series)
                <span><i style="background: {{ $series['color'] }}"></i>{{ $series['name'] }}</span>
              @endforeach
            </div>
          </div>
        @endif
        <div class="super-admin-card-details {{ $isDepartmentListCard ? 'is-department-list' : '' }}">
          @foreach ($sparkPoints as $point)
            @php
              $detailValue = ($point['format'] ?? 'number') === 'currency'
                ? '&#8377;'.number_format($point['value'], 0)
                : number_format($point['value'], 0);
              $detailWidth = $point['value'] > 0 ? ($point['value'] / $sparkMax) * 100 : 0;
            @endphp
            @if ($isDepartmentListCard)
              <div class="super-admin-department-list-row">
                <span>{{ $point['label'] }}</span>
              </div>
            @else
              <div class="super-admin-card-detail-row">
                <span>{{ $point['label'] }}</span>
                <div><i data-live-width="{{ $detailWidth }}" style="width: 0"></i></div>
                <strong>{!! $detailValue !!}</strong>
              </div>
            @endif
          @endforeach
        </div>
        <div class="portal-stat-note">{{ $card['note'] }}</div>
      </article>
    @endforeach
  </section>

  <section class="portal-grid-2 super-admin-dashboard-panels">
    <article class="portal-card super-admin-table-card">
      <div class="portal-card-head">
        <div><h2>Recent Students</h2><p>Latest admission records.</p></div>
        <a class="super-admin-table-link" href="{{ route(($adminRoutePrefix ?? 'admin').'.students.index') }}">View all</a>
      </div>
      <table class="portal-table super-admin-data-table">
        <thead><tr><th>Student</th><th>Campus</th><th>Course</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
          @forelse ($recentStudents as $student)
            <tr>
              <td>
                <div class="super-admin-table-person">
                  <span>{{ strtoupper(substr($student->user->name, 0, 1)) }}</span>
                  <div>
                    <strong>{{ $student->user->name }}</strong>
                    <small>{{ $student->registration_number }}</small>
                  </div>
                </div>
              </td>
              <td><span class="super-admin-table-muted">{{ $student->campus->name }}</span></td>
              <td><strong class="super-admin-table-main">{{ $student->course->name }}</strong></td>
              <td><span class="super-admin-table-badge">{{ ucfirst($student->status) }}</span></td>
              <td><time>{{ $student->admission_date?->format('d M Y') }}</time></td>
            </tr>
          @empty
            <tr><td class="super-admin-empty-row" colspan="5">No students found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </article>
    <article class="portal-card super-admin-table-card">
      <div class="portal-card-head">
        <div><h2>Recent Payments</h2><p>Latest fee collection activity.</p></div>
        <a class="super-admin-table-link" href="{{ route(($adminRoutePrefix ?? 'admin').'.fees.index') }}">View all</a>
      </div>
      <table class="portal-table super-admin-data-table">
        <thead><tr><th>Student</th><th>Amount</th><th>Method</th><th>Date</th></tr></thead>
        <tbody>
          @forelse ($recentPayments as $payment)
            <tr>
              <td>
                <div class="super-admin-table-person">
                  <span>{{ strtoupper(substr($payment->fee->student->user->name, 0, 1)) }}</span>
                  <div>
                    <strong>{{ $payment->fee->student->user->name }}</strong>
                    <small>{{ $payment->fee->fee_type }}</small>
                  </div>
                </div>
              </td>
              <td><strong class="super-admin-table-amount">&#8377;{{ number_format($payment->amount, 2) }}</strong></td>
              <td><span class="super-admin-table-badge">{{ $payment->payment_method }}</span></td>
              <td><time>{{ $payment->payment_date?->format('d M Y') }}</time></td>
            </tr>
          @empty
            <tr><td class="super-admin-empty-row" colspan="4">No payments found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </article>
  </section>
@endsection
