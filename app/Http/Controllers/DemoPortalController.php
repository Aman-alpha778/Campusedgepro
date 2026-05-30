<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DemoPortalController extends Controller
{
    public function dashboard(): View
    {
        return view('demo.dashboard', [
            'kpis' => [
                ['label' => 'Total Students', 'value' => '5,248', 'trend' => '+12.4%', 'tone' => 'blue'],
                ['label' => 'Total Faculty', 'value' => '286', 'trend' => '+18 new', 'tone' => 'green'],
                ['label' => 'Departments', 'value' => '18', 'trend' => '10 active', 'tone' => 'amber'],
                ['label' => 'Courses', 'value' => '42', 'trend' => '15 featured', 'tone' => 'violet'],
                ['label' => 'Revenue', 'value' => '₹2.8 Cr', 'trend' => '+8.7%', 'tone' => 'red'],
            ],
            'roles' => [
                ['name' => 'Demo Admin', 'purpose' => 'Experience full ERP functionality.', 'access' => ['All Modules']],
                ['name' => 'Demo Principal', 'purpose' => 'Leadership reporting and oversight.', 'access' => ['Dashboard', 'Reports', 'Analytics', 'Attendance Reports', 'Fee Reports', 'Student Reports']],
                ['name' => 'Demo Teacher', 'purpose' => 'Daily academic workflows.', 'access' => ['Attendance', 'Assignments', 'Marks Entry', 'Timetable', 'Student Profiles']],
                ['name' => 'Demo Student', 'purpose' => 'Student self-service experience.', 'access' => ['Dashboard', 'Assignments', 'Results', 'Attendance', 'Fee Records']],
                ['name' => 'Demo Parent', 'purpose' => 'Guardian visibility and alerts.', 'access' => ['Attendance', 'Fee Status', 'Exam Results', 'Notifications']],
            ],
            'charts' => [
                'Admissions Trend' => [
                    ['label' => 'Jan', 'value' => 42],
                    ['label' => 'Feb', 'value' => 56],
                    ['label' => 'Mar', 'value' => 68],
                    ['label' => 'Apr', 'value' => 78],
                    ['label' => 'May', 'value' => 92],
                    ['label' => 'Jun', 'value' => 84],
                ],
                'Revenue Trend' => [
                    ['label' => 'Jan', 'value' => 48],
                    ['label' => 'Feb', 'value' => 62],
                    ['label' => 'Mar', 'value' => 73],
                    ['label' => 'Apr', 'value' => 69],
                    ['label' => 'May', 'value' => 88],
                    ['label' => 'Jun', 'value' => 96],
                ],
                'Attendance Trend' => [
                    ['label' => 'Mon', 'value' => 94],
                    ['label' => 'Tue', 'value' => 91],
                    ['label' => 'Wed', 'value' => 96],
                    ['label' => 'Thu', 'value' => 93],
                    ['label' => 'Fri', 'value' => 95],
                    ['label' => 'Sat', 'value' => 89],
                ],
                'Grade Distribution' => [
                    ['label' => 'A', 'value' => 34],
                    ['label' => 'B', 'value' => 28],
                    ['label' => 'C', 'value' => 21],
                    ['label' => 'D', 'value' => 12],
                    ['label' => 'E', 'value' => 5],
                ],
            ],
            'demoData' => [
                ['label' => 'Students', 'value' => '500+'],
                ['label' => 'Teachers', 'value' => '50+'],
                ['label' => 'Attendance Records', 'value' => '20,000+'],
                ['label' => 'Fee Transactions', 'value' => '2,000+'],
                ['label' => 'Exam Cycles', 'value' => '3'],
                ['label' => 'Assignments', 'value' => '50+'],
            ],
        ]);
    }

    public function module(string $module): View
    {
        $catalog = $this->moduleCatalog();

        abort_unless(array_key_exists($module, $catalog), 404);

        return view('demo.module', [
            'moduleKey' => $module,
            'module' => $catalog[$module],
        ]);
    }

    protected function moduleCatalog(): array
    {
        return [
            'admissions' => [
                'title' => 'Admission Management',
                'description' => 'Convert applications into verified enrollments with document tracking and approval flow.',
                'metrics' => [['Applications', '348'], ['Verified', '214'], ['Approved', '176']],
                'workflow' => ['Application', 'Verification', 'Approval', 'Enrollment'],
                'form' => ['Name', 'DOB', 'Gender', 'Email', 'Mobile', 'Father Name', 'Mother Name', 'Contact Number', 'Photo', 'Aadhaar', 'Marksheet'],
                'headers' => ['Application ID', 'Applicant', 'Course', 'Stage', 'Documents'],
                'rows' => [
                    ['APP-2401', 'Aman Sharma', 'B.Tech', 'Verification', '8/10'],
                    ['APP-2402', 'Priya Mehta', 'MBA', 'Approval', '10/10'],
                    ['APP-2403', 'Rohan Das', 'BCA', 'Application', '5/10'],
                ],
            ],
            'students' => [
                'title' => 'Student Management',
                'description' => 'Manage profiles, academic details, contact information, promotion, and transfers.',
                'metrics' => [['Active Students', '5,248'], ['Transfers', '24'], ['Promotions', '1,138']],
                'form' => ['Student ID', 'Admission Number', 'Roll Number', 'Department', 'Course', 'Semester', 'Address', 'Email', 'Phone'],
                'actions' => ['Create Student', 'Edit Student', 'View Student', 'Promote Student', 'Transfer Student'],
                'headers' => ['Student ID', 'Name', 'Course', 'Semester', 'Status'],
                'rows' => [
                    ['STU-1001', 'Aarav Sharma', 'B.Tech CSE', '4', 'Active'],
                    ['STU-1002', 'Isha Nair', 'BBA', '2', 'Active'],
                    ['STU-1003', 'Rahul Verma', 'MBA', '1', 'Pending Docs'],
                ],
            ],
            'academics' => [
                'title' => 'Academic Management',
                'description' => 'Organize departments, courses, subjects, subject codes, and credits.',
                'metrics' => [['Departments', '18'], ['Courses', '42'], ['Subjects', '186']],
                'panels' => [
                    ['Engineering', 'B.Tech, MCA, Computer Science'],
                    ['Management', 'BBA, MBA, Business Analytics'],
                    ['Commerce', 'B.Com, Accounting, Taxation'],
                    ['Arts', 'English, Economics, Psychology'],
                ],
                'headers' => ['Course', 'Subject', 'Code', 'Credits', 'Department'],
                'rows' => [
                    ['BCA', 'Database Systems', 'BCA-204', '4', 'Computer Science'],
                    ['BBA', 'Marketing Strategy', 'BBA-112', '3', 'Management'],
                    ['MBA', 'Financial Analytics', 'MBA-302', '4', 'Management'],
                ],
            ],
            'attendance' => [
                'title' => 'Attendance Management',
                'description' => 'Mark present, absent, leave, and late entries with daily, monthly, and student-wise reports.',
                'metrics' => [['Today', '94%'], ['Absent', '126'], ['Late', '42']],
                'workflow' => ['Select Class', 'Select Subject', 'Mark Attendance', 'Save'],
                'headers' => ['Department', 'Present', 'Absent', 'Leave', 'Trend'],
                'rows' => [
                    ['Engineering', '1,142', '46', '18', 'Up 2.1%'],
                    ['Management', '488', '21', '6', 'Steady'],
                    ['Commerce', '571', '19', '9', 'Up 1.3%'],
                ],
            ],
            'timetable' => [
                'title' => 'Timetable Management',
                'description' => 'Create weekly schedules with teacher and room allocation.',
                'metrics' => [['Weekly Slots', '312'], ['Rooms', '64'], ['Conflicts', '0']],
                'actions' => ['Create Timetable', 'Teacher Allocation', 'Room Allocation', 'Weekly Schedule'],
                'headers' => ['Day', '09:00', '10:00', '11:00', 'Room'],
                'rows' => [
                    ['Monday', 'Mathematics', 'Programming', 'Database', 'CSE-204'],
                    ['Tuesday', 'Accounting', 'Economics', 'Business Law', 'MG-102'],
                    ['Wednesday', 'Statistics', 'Java Lab', 'Data Structures', 'LAB-3'],
                ],
            ],
            'exams' => [
                'title' => 'Examination Module',
                'description' => 'Create exams, enter marks, generate results, calculate grades, and publish student views.',
                'metrics' => [['Exam Cycles', '3'], ['Results Ready', '92%'], ['Top Rank', '98.4%']],
                'actions' => ['Create Exam', 'Marks Entry', 'Result Generation', 'Grade Calculation', 'Student Result View'],
                'headers' => ['Student', 'Subject Marks', 'Percentage', 'Grade', 'Rank'],
                'rows' => [
                    ['Aarav Sharma', '448/500', '89.6%', 'A', '12'],
                    ['Isha Nair', '421/500', '84.2%', 'A', '24'],
                    ['Rahul Verma', '392/500', '78.4%', 'B', '41'],
                ],
            ],
            'assignments' => [
                'title' => 'Assignment Module',
                'description' => 'Teachers create assignments, upload documents, set deadlines, and review submissions.',
                'metrics' => [['Open', '50+'], ['Submitted', '1,846'], ['Feedback Due', '128']],
                'actions' => ['Create Assignment', 'Upload Documents', 'Set Deadlines', 'Submit Assignment', 'View Feedback'],
                'headers' => ['Assignment', 'Class', 'Deadline', 'Submitted', 'Feedback'],
                'rows' => [
                    ['Database ER Diagram', 'BCA 2nd Sem', '04 Jun', '82%', 'In Review'],
                    ['Marketing Case Study', 'BBA 1st Sem', '07 Jun', '74%', 'Pending'],
                    ['Java Lab Record', 'MCA 1st Sem', '10 Jun', '91%', 'Published'],
                ],
            ],
            'fees' => [
                'title' => 'Fee Management',
                'description' => 'Assign fees, receive payments, generate receipts, and monitor outstanding dues.',
                'metrics' => [['Collected', '₹2.8 Cr'], ['Outstanding', '₹38 L'], ['Receipts', '2,000+']],
                'actions' => ['Assign Fees', 'Receive Payment', 'Generate Receipt', 'View Outstanding Dues'],
                'panels' => [['Tuition Fee', 'Cash, UPI, Card, Net Banking'], ['Hostel Fee', 'Monthly and semester billing'], ['Transport Fee', 'Route-wise collection'], ['Examination Fee', 'Exam cycle billing']],
                'headers' => ['Category', 'Collected', 'Outstanding', 'Mode', 'Status'],
                'rows' => [
                    ['Tuition', '₹1.9 Cr', '₹22 L', 'UPI/Card', 'On Track'],
                    ['Hostel', '₹48 L', '₹7 L', 'Net Banking', 'Watchlist'],
                    ['Transport', '₹31 L', '₹4 L', 'Cash/UPI', 'On Track'],
                ],
            ],
            'staff' => [
                'title' => 'Staff Management',
                'description' => 'Maintain employee information, designations, departments, joining dates, and directory records.',
                'metrics' => [['Employees', '286'], ['Departments', '18'], ['New Joiners', '12']],
                'actions' => ['Add Employee', 'Edit Employee', 'Employee Directory'],
                'headers' => ['Employee', 'Designation', 'Department', 'Joining Date', 'Status'],
                'rows' => [
                    ['Dr. Meera Pillai', 'Professor', 'Engineering', '12 Jul 2021', 'Active'],
                    ['Sandeep Rao', 'Associate Professor', 'Management', '03 Aug 2020', 'Active'],
                    ['Nikita Joshi', 'Assistant Professor', 'Commerce', '18 Jan 2023', 'Active'],
                ],
            ],
            'payroll' => [
                'title' => 'Payroll Module',
                'description' => 'Generate payroll, download payslips, and review salary history across 12 months.',
                'metrics' => [['Monthly Payroll', '₹42 L'], ['Payslips', '286'], ['Deductions', '₹3.8 L']],
                'actions' => ['Generate Payroll', 'Download Payslip', 'Salary History'],
                'headers' => ['Employee', 'Basic Salary', 'Allowances', 'Deductions', 'Net Pay'],
                'rows' => [
                    ['Dr. Meera Pillai', '₹82,000', '₹18,000', '₹8,400', '₹91,600'],
                    ['Sandeep Rao', '₹68,000', '₹14,000', '₹6,900', '₹75,100'],
                    ['Nikita Joshi', '₹48,000', '₹9,000', '₹4,200', '₹52,800'],
                ],
            ],
            'notifications' => [
                'title' => 'Notification Center',
                'description' => 'Send email, SMS, and in-app notifications for attendance, fees, results, and holidays.',
                'metrics' => [['Today Sent', '1,248'], ['Unread', '312'], ['Channels', '3']],
                'actions' => ['Attendance Alert', 'Fee Reminder', 'Result Published', 'Holiday Notice'],
                'headers' => ['Type', 'Channel', 'Audience', 'Status', 'Sent'],
                'rows' => [
                    ['Attendance Alert', 'SMS + App', 'Parents', 'Delivered', '09:20 AM'],
                    ['Fee Reminder', 'Email', 'Students', 'Scheduled', '04:00 PM'],
                    ['Holiday Notice', 'In-App', 'All Users', 'Draft', 'Pending'],
                ],
            ],
            'reports' => [
                'title' => 'Reports & Analytics',
                'description' => 'Review enrollment, attendance, academic performance, fee collection, revenue, payroll, and departments.',
                'metrics' => [['Reports', '32'], ['Dashboards', '8'], ['Exports', 'Disabled']],
                'panels' => [['Student Reports', 'Enrollment, attendance, academic performance'], ['Finance Reports', 'Fee collection, outstanding dues, revenue analytics'], ['Staff Reports', 'Attendance, payroll, department performance']],
                'headers' => ['Report', 'Owner', 'Updated', 'Audience', 'Availability'],
                'rows' => [
                    ['Admissions Funnel', 'Admissions Team', 'Today', 'Management', 'Preview Only'],
                    ['Fee Recovery Summary', 'Finance Team', 'Today', 'Accounts', 'Preview Only'],
                    ['Attendance Insights', 'Academic Office', 'Today', 'Department Heads', 'Preview Only'],
                ],
            ],
        ];
    }
}
