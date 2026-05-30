<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DemoPortalController extends Controller
{
    public function dashboard(): View
    {
        return view('demo.dashboard', [
            'workspaces' => $this->workspaces(),
            'demoStats' => [
                ['label' => 'Students', 'value' => '5,000+'],
                ['label' => 'Faculty Members', 'value' => '250+'],
                ['label' => 'Departments', 'value' => '20+'],
                ['label' => 'Courses', 'value' => '50+'],
                ['label' => 'Attendance Records', 'value' => '100,000+'],
                ['label' => 'Fee Transactions', 'value' => '20,000+'],
                ['label' => 'Assignments', 'value' => '1,000+'],
                ['label' => 'Payroll Records', 'value' => '24 Months'],
            ],
        ]);
    }

    public function workspace(string $workspace): View
    {
        $workspaceData = $this->workspaceOrFail($workspace);

        return view('demo.workspace', [
            'workspaceKey' => $workspace,
            'workspace' => $workspaceData,
            'currentModule' => 'Dashboard',
        ]);
    }

    public function workspaceModule(string $workspace, string $module): View|RedirectResponse
    {
        $workspaceData = $this->workspaceOrFail($workspace);
        $moduleData = collect($workspaceData['modules'])->firstWhere('key', $module);

        if (! $moduleData) {
            return redirect()->route('demo.workspace', $workspace);
        }

        return view('demo.module', [
            'workspaceKey' => $workspace,
            'workspace' => $workspaceData,
            'moduleKey' => $module,
            'module' => $moduleData,
            'currentModule' => $moduleData['label'],
        ]);
    }

    public function module(string $module): RedirectResponse
    {
        $legacyMap = [
            'students' => 'students',
            'attendance' => 'attendance',
            'fees' => 'fees',
            'faculty' => 'staff',
            'reports' => 'reports',
        ];

        return redirect()->route('demo.workspace.module', [
            'workspace' => 'super-admin',
            'module' => $legacyMap[$module] ?? 'dashboard',
        ]);
    }

    protected function workspaceOrFail(string $workspace): array
    {
        $workspaces = $this->workspaces();

        abort_unless(array_key_exists($workspace, $workspaces), 404);

        return $workspaces[$workspace];
    }

    protected function workspaces(): array
    {
        return [
            'super-admin' => [
                'name' => 'Super Admin Experience',
                'shortName' => 'Super Admin',
                'objective' => 'Demonstrate complete ERP management capabilities.',
                'description' => 'Experience complete institution management and administrative control.',
                'action' => 'Launch Super Admin Workspace',
                'accent' => 'blue',
                'highlights' => ['Institution Dashboard', 'Admissions', 'Student Management', 'Academic Management', 'Fee Management', 'Staff Management', 'Payroll', 'Reports & Analytics'],
                'widgets' => [
                    ['label' => 'Total Students', 'value' => '5,248', 'note' => '+12% this cycle'],
                    ['label' => 'Total Faculty', 'value' => '286', 'note' => '18 departments'],
                    ['label' => 'Total Departments', 'value' => '20', 'note' => '50+ courses'],
                    ['label' => 'Revenue Overview', 'value' => 'Rs 2.8 Cr', 'note' => '84% collected'],
                    ['label' => 'Attendance Overview', 'value' => '94.6%', 'note' => 'Today'],
                ],
                'modules' => $this->moduleSet(['admissions', 'students', 'academics', 'attendance', 'fees', 'staff', 'payroll', 'reports', 'analytics']),
            ],
            'principal' => [
                'name' => 'Principal Experience',
                'shortName' => 'Principal',
                'objective' => 'Demonstrate institution-level oversight.',
                'description' => 'Experience institutional oversight and performance monitoring.',
                'action' => 'Launch Principal Workspace',
                'accent' => 'green',
                'highlights' => ['Institutional Dashboard', 'Attendance Monitoring', 'Department Analytics', 'Academic Reports', 'Revenue Reports', 'Faculty Monitoring'],
                'widgets' => [
                    ['label' => 'Attendance Trends', 'value' => '93.8%', 'note' => '+2.4% monthly'],
                    ['label' => 'Academic Performance', 'value' => '86%', 'note' => 'Grade A/B'],
                    ['label' => 'Department Performance', 'value' => '18/20', 'note' => 'On target'],
                    ['label' => 'Fee Collection Overview', 'value' => 'Rs 2.1 Cr', 'note' => 'This quarter'],
                ],
                'modules' => $this->moduleSet(['attendance-monitoring', 'faculty-management', 'academic-reports', 'performance-analytics']),
            ],
            'teacher' => [
                'name' => 'Teacher Experience',
                'shortName' => 'Teacher',
                'objective' => 'Demonstrate classroom operations.',
                'description' => 'Experience day-to-day academic operations.',
                'action' => 'Launch Teacher Workspace',
                'accent' => 'amber',
                'highlights' => ['Attendance Management', 'Assignment Creation', 'Exam Marks Entry', 'Timetable Management', 'Student Performance Tracking'],
                'widgets' => [
                    ['label' => "Today's Classes", 'value' => '5', 'note' => '2 completed'],
                    ['label' => 'Pending Assignments', 'value' => '38', 'note' => 'Need review'],
                    ['label' => 'Attendance Tasks', 'value' => '3', 'note' => 'Due today'],
                    ['label' => 'Exam Activities', 'value' => '2', 'note' => 'Marks entry'],
                ],
                'modules' => $this->moduleSet(['attendance', 'assignments', 'exams', 'timetable', 'student-performance']),
            ],
            'student' => [
                'name' => 'Student Experience',
                'shortName' => 'Student',
                'objective' => 'Demonstrate student self-service functionality.',
                'description' => 'Experience the student self-service portal.',
                'action' => 'Launch Student Workspace',
                'accent' => 'violet',
                'highlights' => ['Attendance Tracking', 'Assignment Submission', 'Result Viewing', 'Fee Status', 'Study Materials'],
                'widgets' => [
                    ['label' => 'Attendance Percentage', 'value' => '92%', 'note' => 'Semester average'],
                    ['label' => 'Pending Assignments', 'value' => '4', 'note' => '2 due this week'],
                    ['label' => 'Recent Results', 'value' => 'A', 'note' => 'Database Systems'],
                    ['label' => 'Fee Status', 'value' => 'Clear', 'note' => 'No dues'],
                ],
                'modules' => $this->moduleSet(['attendance', 'assignments', 'results', 'fees', 'study-material']),
            ],
            'parent' => [
                'name' => 'Parent Experience',
                'shortName' => 'Parent',
                'objective' => 'Demonstrate student monitoring capabilities.',
                'description' => 'Experience parental engagement and student monitoring.',
                'action' => 'Launch Parent Workspace',
                'accent' => 'rose',
                'highlights' => ['Child Attendance', 'Fee Status', 'Academic Performance', 'Notifications', 'Examination Updates'],
                'widgets' => [
                    ['label' => 'Child Attendance', 'value' => '91%', 'note' => 'This month'],
                    ['label' => 'Upcoming Exams', 'value' => '3', 'note' => 'Next 14 days'],
                    ['label' => 'Fee Status', 'value' => 'Rs 0', 'note' => 'Outstanding'],
                    ['label' => 'Academic Performance', 'value' => 'A-', 'note' => 'Current grade'],
                ],
                'modules' => $this->moduleSet(['attendance', 'fees', 'results', 'notifications']),
            ],
            'accountant' => [
                'name' => 'Accountant Experience',
                'shortName' => 'Accountant',
                'objective' => 'Demonstrate financial operations.',
                'description' => 'Experience financial operations and fee management.',
                'action' => 'Launch Accountant Workspace',
                'accent' => 'cyan',
                'highlights' => ['Fee Collection', 'Transaction Management', 'Receipt Generation', 'Revenue Reports', 'Outstanding Dues'],
                'widgets' => [
                    ['label' => 'Total Revenue', 'value' => 'Rs 2.8 Cr', 'note' => 'Current year'],
                    ['label' => "Today's Collection", 'value' => 'Rs 8.4 L', 'note' => '214 receipts'],
                    ['label' => 'Outstanding Dues', 'value' => 'Rs 38 L', 'note' => 'Watchlist'],
                    ['label' => 'Payment Trends', 'value' => '+11%', 'note' => 'UPI growth'],
                ],
                'modules' => $this->moduleSet(['fee-collection', 'transactions', 'receipts', 'reports']),
            ],
            'hr-manager' => [
                'name' => 'HR Manager Experience',
                'shortName' => 'HR Manager',
                'objective' => 'Demonstrate workforce management.',
                'description' => 'Experience employee and workforce management.',
                'action' => 'Launch HR Workspace',
                'accent' => 'slate',
                'highlights' => ['Employee Records', 'Leave Management', 'Attendance Monitoring', 'Payroll Overview', 'Staff Performance'],
                'widgets' => [
                    ['label' => 'Total Employees', 'value' => '286', 'note' => '250+ faculty/staff'],
                    ['label' => 'Attendance Rate', 'value' => '96%', 'note' => 'Staff today'],
                    ['label' => 'Leave Requests', 'value' => '12', 'note' => 'Pending review'],
                    ['label' => 'Payroll Status', 'value' => 'Ready', 'note' => 'May cycle'],
                ],
                'modules' => $this->moduleSet(['employees', 'attendance', 'leave-management', 'payroll']),
            ],
        ];
    }

    protected function moduleSet(array $keys): array
    {
        $catalog = [
            'admissions' => ['label' => 'Admissions', 'description' => 'Application verification, approval, and enrollment pipeline.', 'records' => [['APP-2401', 'Aman Sharma', 'B.Tech', 'Verification'], ['APP-2402', 'Priya Mehta', 'MBA', 'Approved']]],
            'students' => ['label' => 'Students', 'description' => 'Student profiles, enrollments, promotions, and transfers.', 'records' => [['STU-1001', 'Aarav Sharma', 'B.Tech CSE', 'Active'], ['STU-1002', 'Isha Nair', 'BBA', 'Active']]],
            'academics' => ['label' => 'Academics', 'description' => 'Departments, courses, subjects, credits, and curriculum mapping.', 'records' => [['BCA', 'Database Systems', '4 Credits', 'Computer Science'], ['MBA', 'Financial Analytics', '4 Credits', 'Management']]],
            'attendance' => ['label' => 'Attendance', 'description' => 'Present, absent, leave, and late tracking with daily reports.', 'records' => [['BCA 2nd Sem', '92%', '4 Absent', 'Saved'], ['MBA 1st Sem', '95%', '2 Absent', 'Saved']]],
            'attendance-monitoring' => ['label' => 'Attendance Monitoring', 'description' => 'Institution-wide attendance trends and department comparisons.', 'records' => [['Engineering', '94%', '+2.1%', 'Healthy'], ['Management', '91%', 'Steady', 'Watch']]],
            'fees' => ['label' => 'Fees', 'description' => 'Fee status, dues, receipts, and category-wise payment tracking.', 'records' => [['Tuition', 'Paid', 'Rs 0 Due', 'Receipt Ready'], ['Transport', 'Pending', 'Rs 8,000 Due', 'Reminder Sent']]],
            'staff' => ['label' => 'Staff', 'description' => 'Employee directory, designation, departments, and joining details.', 'records' => [['Dr. Meera Pillai', 'Professor', 'Engineering', 'Active'], ['Sandeep Rao', 'Associate Professor', 'Management', 'Active']]],
            'payroll' => ['label' => 'Payroll', 'description' => 'Salary components, payslips, deductions, and payroll status.', 'records' => [['May Payroll', 'Rs 42 L', 'Ready', 'Preview'], ['Deductions', 'Rs 3.8 L', 'Calculated', 'Preview']]],
            'reports' => ['label' => 'Reports', 'description' => 'Role-specific student, finance, academic, and staff reports.', 'records' => [['Attendance Report', 'Today', 'Principal', 'Preview'], ['Revenue Analytics', 'Monthly', 'Finance', 'Preview']]],
            'analytics' => ['label' => 'Analytics', 'description' => 'Institution dashboards, performance trends, and decision views.', 'records' => [['Admissions Trend', '+18%', 'Monthly', 'Up'], ['Revenue Trend', '+11%', 'Quarterly', 'Up']]],
            'faculty-management' => ['label' => 'Faculty Management', 'description' => 'Faculty workload, attendance, and department monitoring.', 'records' => [['Engineering', '86 Faculty', '18 hrs/week', 'Balanced'], ['Management', '42 Faculty', '16 hrs/week', 'Balanced']]],
            'academic-reports' => ['label' => 'Academic Reports', 'description' => 'Academic performance, grade distribution, and department progress.', 'records' => [['B.Tech CSE', '87%', 'Grade A/B', 'Strong'], ['BBA', '82%', 'Grade A/B', 'Stable']]],
            'performance-analytics' => ['label' => 'Performance Analytics', 'description' => 'Department performance and institutional trend analysis.', 'records' => [['Engineering', '92 Score', 'Top'], ['Commerce', '84 Score', 'Improving']]],
            'assignments' => ['label' => 'Assignments', 'description' => 'Assignment creation, submission, deadlines, and feedback.', 'records' => [['Database ER Diagram', '82% Submitted', '04 Jun', 'Review'], ['Marketing Case Study', '74% Submitted', '07 Jun', 'Pending']]],
            'exams' => ['label' => 'Exams', 'description' => 'Exam activities, marks entry, grades, results, and ranks.', 'records' => [['Mid Term', 'Marks Entry', 'Open', 'Due Today'], ['Semester Exam', 'Scheduled', '12 Jun', 'Ready']]],
            'timetable' => ['label' => 'Timetable', 'description' => 'Weekly class schedules, teacher allocation, and room allocation.', 'records' => [['Monday', '09:00 Mathematics', '10:00 Programming', 'CSE-204'], ['Tuesday', '09:00 Accounting', '10:00 Economics', 'MG-102']]],
            'student-performance' => ['label' => 'Student Performance', 'description' => 'Student-wise grade, attendance, and assignment performance.', 'records' => [['Aarav Sharma', '89.6%', '92% Attendance', 'A'], ['Isha Nair', '84.2%', '95% Attendance', 'A']]],
            'results' => ['label' => 'Results', 'description' => 'Published exam results, percentage, grade, and rank views.', 'records' => [['Database Systems', '89/100', 'A', 'Rank 12'], ['Mathematics', '84/100', 'A', 'Rank 18']]],
            'study-material' => ['label' => 'Study Material', 'description' => 'Course documents, notes, references, and learning resources.', 'records' => [['DBMS Notes', 'PDF', 'Uploaded', 'Open'], ['Java Lab Manual', 'PDF', 'Updated', 'Open']]],
            'notifications' => ['label' => 'Notifications', 'description' => 'Attendance alerts, fee reminders, result updates, and notices.', 'records' => [['Attendance Alert', 'Parent', 'Delivered', '09:20 AM'], ['Holiday Notice', 'All Users', 'Draft', 'Pending']]],
            'fee-collection' => ['label' => 'Fee Collection', 'description' => 'Collect payments, review categories, and monitor collection progress.', 'records' => [['Tuition Fee', 'Rs 1.9 Cr', '84%', 'On Track'], ['Hostel Fee', 'Rs 48 L', '76%', 'Watch']]],
            'transactions' => ['label' => 'Transactions', 'description' => 'Payment modes, UPI/Card/Cash entries, and transaction audit trail.', 'records' => [['TXN-9001', 'UPI', 'Rs 42,000', 'Success'], ['TXN-9002', 'Card', 'Rs 28,000', 'Success']]],
            'receipts' => ['label' => 'Receipts', 'description' => 'Receipt generation, receipt lookup, and payment acknowledgement.', 'records' => [['RCT-1001', 'Aarav Sharma', 'Rs 42,000', 'Generated'], ['RCT-1002', 'Isha Nair', 'Rs 28,000', 'Generated']]],
            'employees' => ['label' => 'Employees', 'description' => 'Employee records, departments, designation, and workforce directory.', 'records' => [['EMP-301', 'Dr. Meera Pillai', 'Professor', 'Active'], ['EMP-302', 'Sandeep Rao', 'Associate Professor', 'Active']]],
            'leave-management' => ['label' => 'Leave Management', 'description' => 'Leave requests, approvals, balances, and HR review queues.', 'records' => [['Dr. Meera Pillai', 'Casual Leave', '2 Days', 'Pending'], ['Nikita Joshi', 'Sick Leave', '1 Day', 'Approved']]],
        ];

        return collect($keys)
            ->map(fn (string $key): array => ['key' => $key] + $catalog[$key])
            ->all();
    }
}
