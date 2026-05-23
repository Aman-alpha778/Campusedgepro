<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DemoPortalController extends Controller
{
    public function dashboard(): View
    {
        return view('demo.dashboard', [
            'kpis' => [
                ['label' => 'Active Students', 'value' => '2,418'],
                ['label' => 'Attendance Today', 'value' => '94.6%'],
                ['label' => 'Fee Collection', 'value' => '84 Lakh'],
                ['label' => 'Faculty Members', 'value' => '126'],
            ],
        ]);
    }

    public function module(string $module): View
    {
        $catalog = [
            'students' => [
                'title' => 'Students',
                'description' => 'Review admissions, profiles, and enrollment insights using demo data.',
                'headers' => ['Student ID', 'Name', 'Program', 'Year', 'Status'],
                'rows' => [
                    ['STU-1001', 'Aarav Sharma', 'B.Tech CSE', '2nd Year', 'Active'],
                    ['STU-1002', 'Isha Nair', 'BBA', '1st Year', 'Pending Docs'],
                    ['STU-1003', 'Rahul Verma', 'MBA', '1st Year', 'Active'],
                ],
            ],
            'attendance' => [
                'title' => 'Attendance',
                'description' => 'Monitor class-wise attendance snapshots prepared for the demo environment.',
                'headers' => ['Department', 'Present', 'Absent', 'Leave', 'Trend'],
                'rows' => [
                    ['Engineering', '1,142', '46', '18', 'Up 2.1%'],
                    ['Management', '488', '21', '6', 'Steady'],
                    ['Science', '571', '19', '9', 'Up 1.3%'],
                ],
            ],
            'fees' => [
                'title' => 'Fees',
                'description' => 'See receipt tracking, dues, and concession overviews with non-production sample data.',
                'headers' => ['Head', 'Collected', 'Outstanding', 'Concessions', 'Status'],
                'rows' => [
                    ['Tuition', '52 Lakh', '8.4 Lakh', '1.2 Lakh', 'On Track'],
                    ['Transport', '9.8 Lakh', '1.6 Lakh', '0.3 Lakh', 'Watchlist'],
                    ['Hostel', '14.2 Lakh', '2.1 Lakh', '0.5 Lakh', 'On Track'],
                ],
            ],
            'faculty' => [
                'title' => 'Faculty',
                'description' => 'Review workload and department staffing in a safe read-only demo workspace.',
                'headers' => ['Faculty ID', 'Name', 'Department', 'Designation', 'Workload'],
                'rows' => [
                    ['FAC-301', 'Dr. Meera Pillai', 'Engineering', 'Professor', '18 hrs/week'],
                    ['FAC-302', 'Sandeep Rao', 'Management', 'Associate Professor', '16 hrs/week'],
                    ['FAC-303', 'Nikita Joshi', 'Science', 'Assistant Professor', '15 hrs/week'],
                ],
            ],
            'reports' => [
                'title' => 'Reports',
                'description' => 'Preview executive reporting outputs while export and external integrations remain disabled in demo mode.',
                'headers' => ['Report', 'Owner', 'Updated', 'Audience', 'Availability'],
                'rows' => [
                    ['Admissions Funnel', 'Admissions Team', 'Today', 'Management', 'Preview Only'],
                    ['Fee Recovery Summary', 'Finance Team', 'Today', 'Accounts', 'Preview Only'],
                    ['Attendance Insights', 'Academic Office', 'Today', 'Department Heads', 'Preview Only'],
                ],
            ],
        ];

        abort_unless(array_key_exists($module, $catalog), 404);

        return view('demo.module', [
            'moduleKey' => $module,
            'module' => $catalog[$module],
        ]);
    }
}
