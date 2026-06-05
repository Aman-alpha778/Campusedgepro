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
use Illuminate\Support\Facades\DB;
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

        @set_time_limit(90);

        $stats = $this->dashboardStats();
        $attendancePercent = $stats->attendance_total ? round(($stats->attendance_present / $stats->attendance_total) * 100) : 0;
        $studentCombo = $this->studentDepartmentCombo();
        $facultyCombo = $this->facultyDepartmentCombo();
        $courseCombo = $this->courseDepartmentCombo();
        $departmentCombo = $this->departmentCombo();

        return view('admin.dashboard', [
            'cards' => [
                ['label' => 'Total Students', 'value' => (float) $stats->students_count, 'note' => 'Department-wise student strength and growth', 'tone' => 'blue', 'details' => $studentCombo['details'], 'comboChart' => $studentCombo['chart']],
                ['label' => 'Total Faculty', 'value' => (float) $stats->faculty_count, 'note' => 'Department-wise faculty strength', 'tone' => 'emerald', 'details' => $facultyCombo['details'], 'comboChart' => $facultyCombo['chart']],
                ['label' => 'Total Courses', 'value' => (float) $stats->courses_count, 'note' => 'Department-wise course distribution', 'tone' => 'violet', 'details' => $courseCombo['details'], 'comboChart' => $courseCombo['chart']],
                ['label' => 'Total Departments', 'value' => (float) $stats->departments_count, 'note' => 'Active academic departments', 'tone' => 'amber', 'details' => $departmentCombo['details'], 'comboChart' => $departmentCombo['chart']],
                ['label' => "Today's Attendance", 'value' => $attendancePercent, 'note' => 'Present percentage today', 'tone' => 'cyan', 'suffix' => '%', 'details' => $this->todayAttendanceStatus()],
            ],
            'admissionChart' => $this->monthlyAdmissions(),
            'feeChart' => $this->monthlyPayments(),
            'attendanceChart' => $this->attendanceChart(),
            'recentStudents' => Student::with(['user', 'campus', 'course'])->latest()->take(6)->get(),
            'recentPayments' => Payment::with('fee.student.user')->latest()->take(6)->get(),
        ]);
    }

    private function dashboardStats(): object
    {
        return DB::selectOne("
            select
                (select count(*) from students where deleted_at is null) as students_count,
                (select count(*) from faculty where deleted_at is null) as faculty_count,
                (select count(*) from courses where deleted_at is null) as courses_count,
                (select count(*) from departments where deleted_at is null) as departments_count,
                (select coalesce(sum(amount), 0) from payments) as total_revenue,
                (select coalesce(sum(amount), 0) from fees where deleted_at is null and status in ('pending', 'partial')) as pending_fees,
                (select count(*) from attendances where attendance_date = ?) as attendance_total,
                (select count(*) from attendances where attendance_date = ? and status = 'present') as attendance_present
        ", [now()->toDateString(), now()->toDateString()]);
    }

    private function monthlyAdmissions(): array
    {
        $start = now()->subMonths(11)->startOfMonth();
        $rows = DB::table('students')
            ->selectRaw("to_char(admission_date, 'YYYY-MM') as month_key, count(*) as total")
            ->whereNull('deleted_at')
            ->whereDate('admission_date', '>=', $start)
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        return collect(range(0, 11))->map(function (int $offset) use ($start, $rows): array {
            $date = $start->copy()->addMonths($offset);
            $key = $date->format('Y-m');

            return [
                'label' => $date->format('M'),
                'value' => (float) ($rows[$key] ?? 0),
            ];
        })->all();
    }

    private function monthlyPayments(): array
    {
        $start = now()->subMonths(11)->startOfMonth();
        $rows = DB::table('payments')
            ->selectRaw("to_char(payment_date, 'YYYY-MM') as month_key, coalesce(sum(amount), 0) as total")
            ->whereDate('payment_date', '>=', $start)
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

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

    private function studentDepartmentCombo(): array
    {
        $start = now()->subMonths(5)->startOfMonth();
        $months = collect(range(0, 5))->map(function (int $offset) use ($start): array {
            $date = $start->copy()->addMonths($offset);

            return ['key' => $date->format('Y-m'), 'label' => $date->format('M')];
        });
        $colors = ['#6258d4', '#0ea5a8', '#f59e0b', '#ef476f', '#2563eb'];

        $departments = DB::table('departments')
            ->leftJoin('students', function ($join): void {
                $join->on('students.department_id', '=', 'departments.id')->whereNull('students.deleted_at');
            })
            ->whereNull('departments.deleted_at')
            ->selectRaw('departments.id, departments.name, departments.code, count(students.id) as students_count')
            ->groupBy('departments.id', 'departments.name', 'departments.code')
            ->orderByDesc('students_count')
            ->limit(6)
            ->get()
            ->filter(fn ($department) => (int) $department->students_count > 0)
            ->values();

        $departmentIds = $departments->pluck('id');
        $monthlyRows = $departmentIds->isEmpty()
            ? collect()
            : DB::table('students')
                ->selectRaw("department_id, to_char(admission_date, 'YYYY-MM') as month_key, count(*) as total")
                ->whereNull('deleted_at')
                ->whereIn('department_id', $departmentIds)
                ->whereDate('admission_date', '>=', $start)
                ->groupBy('department_id', 'month_key')
                ->get()
                ->groupBy('department_id')
                ->map(fn ($rows) => $rows->pluck('total', 'month_key'));

        $series = $departments->values()->map(function ($department, int $index) use ($months, $monthlyRows, $colors): array {
            $departmentMonthlyRows = $monthlyRows[$department->id] ?? collect();
            $running = max(0, (int) $department->students_count - (int) collect($departmentMonthlyRows)->sum());
            $points = $months->map(function (array $month) use (&$running, $departmentMonthlyRows): float {
                $running += (int) ($departmentMonthlyRows[$month['key']] ?? 0);

                return (float) $running;
            })->values();

            return [
                'label' => $department->code,
                'name' => $department->name,
                'total' => (float) $department->students_count,
                'color' => $colors[$index % count($colors)],
                'points' => $points->all(),
            ];
        })->all();

        return [
            'details' => $departments->map(fn ($department): array => [
                'label' => $department->name,
                'value' => (float) $department->students_count,
                'format' => 'number',
                'name' => $department->name,
            ])->all(),
            'chart' => [
                'labels' => $months->pluck('label')->all(),
                'series' => $series,
                'max' => max(1, collect($series)->flatMap(fn ($item) => $item['points'])->max() ?? 1),
                'barMax' => max(1, collect($series)->max('total') ?? 1),
            ],
        ];
    }

    private function facultyDepartmentCombo(): array
    {
        $colors = ['#059669', '#0ea5a8', '#f59e0b', '#ef476f', '#2563eb', '#665fb6'];

        $departments = DB::table('departments')
            ->leftJoin('faculty', function ($join): void {
                $join->on('faculty.department_id', '=', 'departments.id')->whereNull('faculty.deleted_at');
            })
            ->whereNull('departments.deleted_at')
            ->selectRaw('departments.id, departments.name, departments.code, count(faculty.id) as faculty_count')
            ->groupBy('departments.id', 'departments.name', 'departments.code')
            ->orderByDesc('faculty_count')
            ->limit(6)
            ->get()
            ->filter(fn ($department) => (int) $department->faculty_count > 0)
            ->values();

        $series = $departments->values()->map(fn ($department, int $index): array => [
            'label' => $department->code,
            'name' => $department->name,
            'total' => (float) $department->faculty_count,
            'color' => $colors[$index % count($colors)],
            'points' => [(float) $department->faculty_count],
        ])->all();

        return [
            'details' => $departments->map(fn ($department): array => [
                'label' => $department->name,
                'value' => (float) $department->faculty_count,
                'format' => 'number',
                'name' => $department->name,
            ])->all(),
            'chart' => [
                'labels' => ['Faculty'],
                'series' => $series,
                'max' => max(1, collect($series)->max('total') ?? 1),
                'barMax' => max(1, collect($series)->max('total') ?? 1),
            ],
        ];
    }

    private function courseDepartmentCombo(): array
    {
        $colors = ['#7c3aed', '#0ea5a8', '#f59e0b', '#ef476f', '#2563eb', '#059669'];

        $departments = DB::table('departments')
            ->leftJoin('courses', function ($join): void {
                $join->on('courses.department_id', '=', 'departments.id')->whereNull('courses.deleted_at');
            })
            ->whereNull('departments.deleted_at')
            ->selectRaw('departments.id, departments.name, departments.code, count(courses.id) as courses_count')
            ->groupBy('departments.id', 'departments.name', 'departments.code')
            ->orderByDesc('courses_count')
            ->limit(6)
            ->get()
            ->filter(fn ($department) => (int) $department->courses_count > 0)
            ->values();

        return $this->departmentSeriesResponse($departments, 'courses_count', $colors);
    }

    private function departmentCombo(): array
    {
        $colors = ['#f59e0b', '#0ea5a8', '#7c3aed', '#ef476f', '#2563eb', '#059669'];

        $departments = DB::table('departments')
            ->whereNull('deleted_at')
            ->selectRaw('id, name, code, 1 as departments_count')
            ->orderBy('name')
            ->limit(6)
            ->get()
            ->values();

        return $this->departmentSeriesResponse($departments, 'departments_count', $colors);
    }

    private function departmentSeriesResponse($departments, string $countColumn, array $colors): array
    {
        $series = $departments->values()->map(fn ($department, int $index): array => [
            'label' => $department->code,
            'name' => $department->name,
            'total' => (float) $department->{$countColumn},
            'color' => $colors[$index % count($colors)],
            'points' => [(float) $department->{$countColumn}],
        ])->all();

        return [
            'details' => $departments->map(fn ($department): array => [
                'label' => $department->name,
                'value' => (float) $department->{$countColumn},
                'format' => 'number',
                'name' => $department->name,
            ])->all(),
            'chart' => [
                'labels' => ['Departments'],
                'series' => $series,
                'max' => max(1, collect($series)->max('total') ?? 1),
                'barMax' => max(1, collect($series)->max('total') ?? 1),
            ],
        ];
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
        $rows = DB::table('attendances')
            ->selectRaw("attendance_date, count(*) as total, sum(case when status = 'present' then 1 else 0 end) as present_total")
            ->whereDate('attendance_date', '>=', now()->subDays(11)->toDateString())
            ->groupBy('attendance_date')
            ->get()
            ->mapWithKeys(fn ($row) => [
                Carbon::parse($row->attendance_date)->toDateString() => $row->total ? ((int) $row->present_total / (int) $row->total) * 100 : 0,
            ]);

        return collect(range(11, 0))->map(function (int $daysBack) use ($rows): array {
            $date = now()->subDays($daysBack);
            $key = $date->toDateString();

            return ['label' => $date->format('d M'), 'value' => round((float) ($rows[$key] ?? 0), 2)];
        })->values()->all();
    }
}
