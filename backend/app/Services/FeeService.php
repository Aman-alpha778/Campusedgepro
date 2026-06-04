<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeService
{
    public function list(Request $request): LengthAwarePaginator
    {
        return Fee::query()
            ->with(['student.user', 'payments'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), fn ($query) => $query->whereHas('student.user', fn ($user) => $user->where('name', 'like', "%{$request->search}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function recordPayment(Fee $fee, array $data): Payment
    {
        return DB::transaction(function () use ($fee, $data): Payment {
            $payment = $fee->payments()->create($data);
            $paid = $fee->payments()->sum('amount');
            $fee->update(['status' => $paid >= $fee->amount ? 'paid' : 'partial']);

            return $payment;
        });
    }
}
