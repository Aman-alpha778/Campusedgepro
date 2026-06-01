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
                'objective' => 'Real-time institution command center.',
                'description' => 'Control admissions, students, academics, attendance, finance, HR, payroll, communication, access, audit logs, and institutional settings from one polished workspace.',
                'action' => 'Launch Super Admin Workspace',
                'accent' => 'blue',
                'highlights' => ['Live Institution Overview', 'Admission Workflow', 'Student Lifecycle', 'Academic Setup', 'Finance Control', 'Attendance Alerts', 'HR & Payroll', 'Audit & Settings'],
                'widgets' => [
                    ['label' => 'Total Students', 'value' => '5,248', 'note' => '412 new admissions'],
                    ['label' => 'Active Faculty', 'value' => '186', 'note' => '24 pending leaves'],
                    ['label' => 'Collected Fees', 'value' => 'Rs 2.36 Cr', 'note' => 'Rs 44 L outstanding'],
                    ['label' => 'Student Attendance', 'value' => '91.8%', 'note' => '126 absent today'],
                    ['label' => 'Active Users', 'value' => '1,284', 'note' => '438 logins today'],
                    ['label' => 'Pending Tasks', 'value' => '37', 'note' => '12 require approval'],
                ],
                'dashboardGroups' => [
                    [
                        'title' => 'Students',
                        'items' => [
                            ['label' => 'Total Students', 'value' => '5,248'],
                            ['label' => 'New Admissions', 'value' => '412'],
                            ['label' => 'Active Students', 'value' => '4,982'],
                            ['label' => 'Graduated Students', 'value' => '266'],
                        ],
                    ],
                    [
                        'title' => 'Staff',
                        'items' => [
                            ['label' => 'Total Faculty', 'value' => '186'],
                            ['label' => 'Teaching Staff', 'value' => '148'],
                            ['label' => 'Non-Teaching Staff', 'value' => '74'],
                            ['label' => 'Pending Leaves', 'value' => '24'],
                        ],
                    ],
                    [
                        'title' => 'Academics',
                        'items' => [
                            ['label' => 'Departments', 'value' => '20'],
                            ['label' => 'Courses', 'value' => '54'],
                            ['label' => 'Subjects', 'value' => '318'],
                            ['label' => 'Classes', 'value' => '96'],
                        ],
                    ],
                    [
                        'title' => 'Finance',
                        'items' => [
                            ['label' => 'Total Revenue', 'value' => 'Rs 2.8 Cr'],
                            ['label' => 'Collected Fees', 'value' => 'Rs 2.36 Cr'],
                            ['label' => 'Outstanding Fees', 'value' => 'Rs 44 L'],
                            ['label' => 'Monthly Revenue', 'value' => 'Rs 31.5 L'],
                        ],
                    ],
                    [
                        'title' => 'Attendance',
                        'items' => [
                            ['label' => 'Student Attendance', 'value' => '91.8%'],
                            ['label' => 'Teacher Attendance', 'value' => '96.4%'],
                            ['label' => 'Absent Students', 'value' => '126'],
                            ['label' => 'Below 75%', 'value' => '43'],
                        ],
                    ],
                    [
                        'title' => 'System',
                        'items' => [
                            ['label' => 'Active Users', 'value' => '1,284'],
                            ['label' => "Today's Logins", 'value' => '438'],
                            ['label' => 'Notifications', 'value' => '18'],
                            ['label' => 'Pending Tasks', 'value' => '37'],
                        ],
                    ],
                ],
                'dailyWorkflow' => ['Login', 'View Dashboard', 'Review Admissions', 'Monitor Attendance', 'Check Fee Collection', 'Review Reports', 'Approve Leave Requests', 'Send Notices', 'Logout'],
                'priorityQueue' => [
                    ['label' => 'Admissions pending review', 'value' => '18', 'tone' => 'warning'],
                    ['label' => 'Fee defaulters to notify', 'value' => '43', 'tone' => 'danger'],
                    ['label' => 'Leave approvals waiting', 'value' => '24', 'tone' => 'warning'],
                    ['label' => 'Reports ready for review', 'value' => '9', 'tone' => 'success'],
                ],
                'auditTrail' => [
                    ['time' => '10:30 AM', 'event' => 'Teacher created assignment', 'actor' => 'Dr. Meera Pillai'],
                    ['time' => '11:15 AM', 'event' => 'Student paid fee', 'actor' => 'Aarav Sharma'],
                    ['time' => '01:20 PM', 'event' => 'Principal generated report', 'actor' => 'Principal Office'],
                ],
                'modules' => $this->moduleSet(['admissions', 'students', 'academics', 'faculty', 'attendance', 'examinations', 'fees', 'employees', 'payroll', 'communication', 'reports', 'users-access', 'audit-logs', 'institution-settings']),
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
            'admissions' => [
                'label' => 'Admission Management',
                'description' => 'Search, filter, review, approve, reject, assign course, and generate admission numbers.',
                'actions' => ['Search Applications', 'Filter Applications', 'Sort Applications', 'Review Application', 'Approve Admission', 'Reject Admission', 'Generate Admission Number', 'Assign Course'],
                'details' => ['Student Details', 'Parent Details', 'Uploaded Documents', 'Rejection Reason Required'],
                'workflow' => ['Pending', 'Reviewed', 'Approved', 'Student Record Created'],
                'records' => [['APP-2401', 'Aman Sharma', 'BCA Sem 1', 'Reviewed'], ['APP-2402', 'Priya Mehta', 'MBA Sem 1', 'Approved'], ['ADM2026001', 'Rohan Verma', 'Section A', 'Student Created']],
            ],
            'students' => [
                'label' => 'Student Management',
                'description' => 'Manage student creation, edits, promotions, transfers, suspensions, graduation, and alumni conversion.',
                'actions' => ['Create Student', 'Edit Student', 'Promote Student', 'Transfer Student', 'Suspend Student', 'Convert to Alumni'],
                'details' => ['Personal Details', 'Academic Details', 'Contact Details', 'Address Updates', 'Course Updates'],
                'workflow' => ['Semester 1', 'Semester 2', 'Graduated', 'Alumni'],
                'records' => [['STU-1001', 'Aarav Sharma', 'BCA Semester 1', 'Active'], ['STU-1002', 'Isha Nair', 'BBA Semester 3', 'Transfer Ready'], ['STU-1003', 'Karan Malhotra', 'MCA Semester 6', 'Graduating']],
            ],
            'academics' => [
                'label' => 'Academic Management',
                'description' => 'Configure departments, courses, curriculum, subjects, credits, semesters, and faculty assignments.',
                'actions' => ['Create Department', 'Edit Department', 'Assign Department Head', 'Create Course', 'Assign Duration', 'Define Curriculum', 'Create Subject', 'Assign Faculty'],
                'details' => ['Computer Science', 'Management', 'Commerce', 'BCA', 'MBA', 'MCA', '3 Years / 6 Semesters'],
                'workflow' => ['Department', 'Course', 'Curriculum', 'Subjects', 'Faculty'],
                'records' => [['Computer Science', 'BCA', '6 Semesters', 'Dr. Meera Pillai'], ['Management', 'MBA', '4 Semesters', 'Prof. Sandeep Rao'], ['Commerce', 'B.Com', '6 Semesters', 'Dr. Neha Shah']],
            ],
            'faculty' => [
                'label' => 'Faculty Management',
                'description' => 'Manage faculty profiles, departments, qualifications, subject allocation, class allocation, workload, attendance, and feedback.',
                'actions' => ['Add Faculty', 'Assign Subjects', 'Assign Classes', 'View Workload', 'Track Attendance', 'Review Student Feedback'],
                'details' => ['Name', 'Department', 'Qualification', 'Database Systems', 'Operating Systems', 'BCA Semester 2'],
                'workflow' => ['Profile Added', 'Subjects Assigned', 'Classes Assigned', 'Workload Reviewed'],
                'records' => [['Dr. Meera Pillai', 'Computer Science', '18 Classes/week', 'Balanced'], ['Prof. Sandeep Rao', 'Management', '14 Classes/week', 'Open Slot'], ['Nikita Joshi', 'Commerce', '16 Classes/week', 'Balanced']],
            ],
            'attendance' => [
                'label' => 'Attendance Management',
                'description' => 'Monitor student and faculty attendance daily, weekly, and monthly with below-75% alerts and late-arrival tracking.',
                'actions' => ['Monitor Attendance', 'View Daily Attendance', 'View Weekly Attendance', 'View Monthly Attendance', 'Generate Defaulter List', 'Track Late Arrivals', 'Leave Monitoring'],
                'details' => ['Student Attendance %', 'Teacher Attendance %', 'Absent Students', 'Below 75% Alerts'],
                'workflow' => ['Daily Marking', 'Alert Check', 'Defaulter List', 'Parent/Faculty Notice'],
                'records' => [['BCA Semester 2', '92%', '4 Absent', 'Saved'], ['MBA Semester 1', '95%', '2 Absent', 'Saved'], ['Below 75% List', '43 Students', 'Alert', 'Notify']],
            ],
            'attendance-monitoring' => ['label' => 'Attendance Monitoring', 'description' => 'Institution-wide attendance trends and department comparisons.', 'records' => [['Engineering', '94%', '+2.1%', 'Healthy'], ['Management', '91%', 'Steady', 'Watch']]],
            'examinations' => [
                'label' => 'Examination Management',
                'description' => 'Create examinations, define subjects, assign dates, verify marks, publish results, and generate ranks.',
                'actions' => ['Create Examination', 'Define Subjects', 'Assign Exam Dates', 'Enter Marks', 'Verify Marks', 'Publish Results', 'Generate Result Sheets', 'Generate Rank List'],
                'details' => ['Mid Term', 'Final Exam', 'Marks Entry', 'Verification', 'Publish'],
                'workflow' => ['Marks Entry', 'Verification', 'Publish'],
                'records' => [['Mid Term', 'BCA Semester 2', 'Marks Entry', 'Open'], ['Final Exam', 'MBA Semester 1', '12 Jun', 'Scheduled'], ['Rank List', 'BCA Semester 6', 'Generated', 'Ready']],
            ],
            'fees' => [
                'label' => 'Fee Management',
                'description' => 'Create fee structures, assign fees, view payments, generate receipts, track defaulters, and apply late fees.',
                'actions' => ['Create Tuition Fee', 'Create Transport Fee', 'Create Hostel Fee', 'Create Exam Fee', 'Assign Fees', 'View Payments', 'Generate Receipts', 'Track Defaulters', 'Apply Late Fees'],
                'details' => ['Collection Report', 'Outstanding Fees', 'Revenue Trends', 'Late Fee Rules'],
                'workflow' => ['Fee Structure', 'Assign Fees', 'Collect Payment', 'Receipt', 'Reports'],
                'records' => [['Tuition Fee', 'Rs 1.9 Cr', '84%', 'On Track'], ['Transport Fee', 'Rs 24 L', '72%', 'Reminder Sent'], ['Outstanding Fees', 'Rs 44 L', '43 Students', 'Follow Up']],
            ],
            'staff' => ['label' => 'Staff', 'description' => 'Employee directory, designation, departments, and joining details.', 'records' => [['Dr. Meera Pillai', 'Professor', 'Engineering', 'Active'], ['Sandeep Rao', 'Associate Professor', 'Management', 'Active']]],
            'employees' => [
                'label' => 'Employee Management',
                'description' => 'Create employees, edit records, assign departments, view attendance, and manage leave requests.',
                'actions' => ['Create Employee', 'Edit Employee', 'Assign Department', 'View Attendance', 'Manage Leave Requests'],
                'details' => ['Teaching Staff', 'Non-Teaching Staff', 'Department Assignment', 'Leave Queue'],
                'workflow' => ['Employee Created', 'Department Assigned', 'Attendance Tracked', 'Leave Reviewed'],
                'records' => [['EMP-301', 'Dr. Meera Pillai', 'Computer Science', 'Active'], ['EMP-302', 'Sandeep Rao', 'Management', 'Leave Pending'], ['EMP-411', 'Ritu Jain', 'Administration', 'Active']],
            ],
            'payroll' => [
                'label' => 'Payroll Management',
                'description' => 'Create salary structures, generate payroll, issue payslips, and review salary reports.',
                'actions' => ['Create Salary Structure', 'Generate Payroll', 'Generate Payslip', 'View Salary Reports'],
                'details' => ['Basic Salary', 'Allowances', 'Deductions', 'Net Pay', 'Monthly Cycle'],
                'workflow' => ['Salary Structure', 'Payroll Draft', 'Approval', 'Payslip Generated'],
                'records' => [['May Payroll', 'Rs 42 L', 'Ready', 'Preview'], ['Deductions', 'Rs 3.8 L', 'Calculated', 'Preview'], ['Payslips', '260 Employees', 'Generated', 'Ready']],
            ],
            'communication' => [
                'label' => 'Communication Center',
                'description' => 'Send notices, email, SMS, and push notifications to students, departments, faculty, and all users.',
                'actions' => ['Send Notice', 'Send Email', 'Send SMS', 'Send Push Notification'],
                'details' => ['All Students', 'Specific Department', 'Faculty', 'Parents', 'Emergency Broadcast'],
                'workflow' => ['Choose Recipients', 'Draft Message', 'Send Notification', 'Delivery Report'],
                'records' => [['Holiday Notice', 'All Students', 'Draft', 'Pending'], ['Fee Reminder', 'Defaulters', 'SMS', 'Scheduled'], ['Exam Update', 'BCA Department', 'Push', 'Delivered']],
            ],
            'reports' => [
                'label' => 'Report Management',
                'description' => 'Generate academic, financial, HR, attendance, payroll, department, revenue, and defaulter reports.',
                'actions' => ['Student Performance', 'Attendance Report', 'Department Report', 'Revenue Report', 'Fee Collection Report', 'Defaulter Report', 'Employee Attendance', 'Payroll Report'],
                'details' => ['Academic Reports', 'Financial Reports', 'HR Reports', 'Export Ready'],
                'workflow' => ['Select Report', 'Apply Filters', 'Preview', 'Export'],
                'records' => [['Attendance Report', 'Today', 'Principal', 'Preview'], ['Revenue Report', 'Monthly', 'Finance', 'Ready'], ['Payroll Report', 'May', 'HR', 'Preview']],
            ],
            'users-access' => [
                'label' => 'User & Access Management',
                'description' => 'Create users, assign roles, reset passwords, lock accounts, and unlock accounts.',
                'actions' => ['Create User', 'Assign Role', 'Reset Password', 'Lock Account', 'Unlock Account'],
                'details' => ['Principal', 'Teacher', 'Student', 'Parent', 'Accountant', 'HR'],
                'workflow' => ['User Created', 'Role Assigned', 'Access Verified'],
                'records' => [['USR-0192', 'Principal Office', 'Principal', 'Active'], ['USR-0481', 'Ravi Parent', 'Parent', 'Locked'], ['USR-0773', 'Accounts Team', 'Accountant', 'Active']],
            ],
            'audit-logs' => [
                'label' => 'Audit Logs',
                'description' => 'Track every important system action with actor, event, time, module, and status.',
                'actions' => ['View Logs', 'Search Activity', 'Filter By Module', 'Export Audit Trail'],
                'details' => ['Teacher Created Assignment', 'Student Paid Fee', 'Principal Generated Report'],
                'workflow' => ['Activity Captured', 'Log Indexed', 'Admin Review'],
                'records' => [['10:30 AM', 'Teacher Created Assignment', 'Academics', 'Recorded'], ['11:15 AM', 'Student Paid Fee', 'Finance', 'Recorded'], ['01:20 PM', 'Principal Generated Report', 'Reports', 'Recorded']],
            ],
            'institution-settings' => [
                'label' => 'Institution Settings',
                'description' => 'Configure academic year, semester structure, minimum attendance, grading scale, and late fee rules.',
                'actions' => ['Academic Year', 'Semester Structure', 'Minimum Attendance %', 'Grading Scale', 'Late Fee Rules'],
                'details' => ['Academic Settings', 'Attendance Settings', 'Examination Settings', 'Fee Settings'],
                'workflow' => ['Select Setting', 'Update Rules', 'Save Configuration', 'Apply Across ERP'],
                'records' => [['Academic Year', '2026-2027', 'Active', 'Configured'], ['Minimum Attendance', '75%', 'Active', 'Configured'], ['Late Fee Rule', 'Rs 100/day', 'Active', 'Configured']],
            ],
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
