@extends('admin.layouts.app', ['title' => 'Receipt'])

@section('content')
  <section class="portal-card"><div class="portal-card-head"><div><h1>Fee Receipt</h1><p>{{ $fee->student->user->name }} | {{ $fee->student->registration_number }}</p></div></div><table class="portal-table"><tr><th>Fee Type</th><td>{{ $fee->fee_type }}</td></tr><tr><th>Total</th><td>₹{{ number_format($fee->amount, 2) }}</td></tr><tr><th>Paid</th><td>₹{{ number_format($fee->payments->sum('amount'), 2) }}</td></tr><tr><th>Status</th><td>{{ $fee->status }}</td></tr></table><h2>Payments</h2><table class="portal-table"><thead><tr><th>Transaction</th><th>Method</th><th>Amount</th><th>Date</th></tr></thead><tbody>@foreach($fee->payments as $payment)<tr><td>{{ $payment->transaction_id }}</td><td>{{ $payment->payment_method }}</td><td>₹{{ number_format($payment->amount, 2) }}</td><td>{{ $payment->payment_date?->format('d M Y') }}</td></tr>@endforeach</tbody></table></section>
@endsection
