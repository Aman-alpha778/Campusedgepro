<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use App\Services\ActivityLogService;
use App\Services\FeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeeController extends Controller
{
    public function __construct(private FeeService $fees, private ActivityLogService $logs) {}

    public function index(Request $request): View
    {
        return view('admin.fees.index', [
            'fees' => $this->fees->list($request),
            'students' => Student::with('user')->latest()->take(200)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $fee = Fee::create($request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'fee_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
            'status' => ['required', 'in:pending,partial,paid,waived'],
        ]));
        $this->logs->record('create', 'Fee', "Created {$fee->fee_type} fee", $request);

        return back()->with('admin_success', 'Fee created successfully.');
    }

    public function payment(Request $request, Fee $fee): RedirectResponse
    {
        $payment = $this->fees->recordPayment($fee, $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'max:80'],
            'transaction_id' => ['required', 'string', 'max:120', 'unique:payments,transaction_id'],
            'payment_date' => ['required', 'date'],
        ]));
        $this->logs->record('create', 'Payment', "Recorded payment {$payment->transaction_id}", $request);

        return back()->with('admin_success', 'Payment recorded successfully.');
    }

    public function receipt(Fee $fee): View
    {
        return view('admin.fees.receipt', ['fee' => $fee->load(['student.user', 'payments'])]);
    }

    public function destroy(Fee $fee): RedirectResponse
    {
        $fee->delete();
        $this->logs->record('delete', 'Fee', "Deleted fee {$fee->id}");

        return back()->with('admin_success', 'Fee deleted successfully.');
    }
}
