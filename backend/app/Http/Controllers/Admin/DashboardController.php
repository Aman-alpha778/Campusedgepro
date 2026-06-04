<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\DemoRequest;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        if (request()->routeIs('admin.dashboard') || request()->is('admin/dashboard')) {
            return view('admin.owner-dashboard', [
                'stats' => [
                    'total' => DemoRequest::count(),
                    'pending' => DemoRequest::where('status', 'Pending')->count(),
                    'approved' => DemoRequest::where('status', 'Approved')->count(),
                    'rejected' => DemoRequest::where('status', 'Rejected')->count(),
                ],
                'recentRequests' => DemoRequest::latest()->take(6)->get(),
                'adminUsers' => User::where('is_admin', true)->latest()->get(),
            ]);
        }

        $today = now()->toDateString();
        $attendanceTotal = Attendance::whereDate('attendance_date', $today)->count();
        $attendancePresent = Attendance::whereDate('attendance_date', $today)->where('status', 'present')->count();
        $totalRevenue = (float) Payment::sum('amount');
        $pendingFees = (float) Fee::whereIn('status', ['pending', 'partial'])->sum('amount');
        $attendancePercent = $attendanceTotal ? round(($attendancePresent / $attendanceTotal) * 100) : 0;

        return view('admin.dashboard', [
            'cards' => [
                ['label' => 'Total Students', 'value' => Student::count(), 'note' => 'Registered student profiles', 'tone' => 'blue', 'details' => $this->countByStatus(Student::query())],
                ['label' => 'Total Faculty', 'value' => Faculty::count(), 'note' => 'Faculty profiles', 'tone' => 'emerald', 'details' => $this->countByStatus(Faculty::query())],
                ['label' => 'Total Courses', 'value' => Course::count(), 'note' => 'Published course records', 'tone' => 'violet', 'details' => $this->countByStatus(Course::query())],
                ['label' => 'Total Departments', 'value' => Department::count(), 'note' => 'Academic departments', 'tone' => 'amber', 'details' => $this->countByStatus(Department::query())],
                ['label' => 'Total Revenue', 'value' => $totalRevenue, 'note' => 'Collected payments', 'tone' => 'indigo', 'format' => 'currency', 'decimals' => 2, 'details' => $this->paymentMethods()],
                ['label' => 'Pending Fees', 'value' => $pendingFees, 'note' => 'Open receivables', 'tone' => 'rose', 'format' => 'currency', 'decimals' => 2, 'details' => $this->feeStatusTotals()],
                ['label' => "Today's Attendance", 'value' => $attendancePercent, 'note' => 'Present percentage today', 'tone' => 'cyan', 'suffix' => '%', 'details' => $this->todayAttendanceStatus()],
            ],
            'admissionChart' => $this->monthly(Student::query(), 'admission_date'),
            'feeChart' => $this->monthly(Payment::query(), 'payment_date', 'amount'),
            'attendanceChart' => $this->attendanceChart(),
            'recentStudents' => Student::with(['user', 'campus', 'course'])->latest()->take(6)->get(),
            'recentPayments' => Payment::with('fee.student.user')->latest()->take(6)->get(),
        ]);
    }

    private function monthly($query, string $dateColumn, ?string $sumColumn = null): array
    {
        $start = now()->subMonths(11)->startOfMonth();
        $rows = $query->whereDate($dateColumn, '>=', $start)->get()
            ->groupBy(fn ($row) => Carbon::parse($row->{$dateColumn})->format('Y-m'))
            ->map(fn ($group) => $sumColumn ? (float) $group->sum($sumColumn) : (float) $group->count());

        return collect(range(0, 11))->map(function (int $offset) use ($start, $rows): array {
            $date = $start->copy()->addMonths($offset);
            $key = $date->format('Y-m');

            return [
                'label' => $date->format('M'),
                'value' => (float) ($rows[$key] ?? 0),
            ];
        })->all();
    }

    private function countByStatus($query): array
    {
        return $query->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row): array => [
                'label' => ucfirst((string) $row->status),
                'value' => (float) $row->total,
                'format' => 'number',
            ])
            ->values()
            ->all();
    }

    private function paymentMethods(): array
    {
        return Payment::query()
            ->selectRaw('payment_method as label, sum(amount) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->limit(4)
            ->get()
            ->map(fn ($row): array => [
                'label' => (string) $row->label,
                'value' => (float) $row->total,
                'format' => 'currency',
            ])
            ->values()
            ->all();
    }

    private function feeStatusTotals(): array
    {
        return Fee::query()
            ->whereIn('status', ['pending', 'partial'])
            ->selectRaw('status, sum(amount) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row): array => [
                'label' => ucfirst((string) $row->status),
                'value' => (float) $row->total,
                'format' => 'currency',
            ])
            ->values()
            ->all();
    }

    private function todayAttendanceStatus(): array
    {
        return Attendance::query()
            ->whereDate('attendance_date', now()->toDateString())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row): array => [
                'label' => ucfirst((string) $row->status),
                'value' => (float) $row->total,
                'format' => 'number',
            ])
            ->values()
            ->all();
    }

    private function attendanceChart(): array
    {
        $rows = Attendance::query()
            ->whereDate('attendance_date', '>=', now()->subDays(11)->toDateString())
            ->get()
            ->groupBy(fn ($row) => Carbon::parse($row->attendance_date)->toDateString())
            ->map(function ($group): float {
                $total = $group->count();

                return $total ? ($group->where('status', 'present')->count() / $total) * 100 : 0;
            });

        return collect(range(11, 0))->map(function (int $daysBack) use ($rows): array {
            $date = now()->subDays($daysBack);
            $key = $date->toDateString();

            return ['label' => $date->format('d M'), 'value' => round((float) ($rows[$key] ?? 0), 2)];
        })->values()->all();
    }
}
