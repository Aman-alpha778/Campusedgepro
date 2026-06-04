<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Faculty;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type', 'students');

        return view('admin.reports.index', [
            'type' => $type,
            'rows' => $this->query($type)->paginate(25)->withQueryString(),
        ]);
    }

    public function export(Request $request)
    {
        $type = $request->query('type', 'students');
        $format = $request->query('format', 'excel');
        $rows = $this->query($type)->limit(1000)->get();
        $filename = Str::slug($type.' report').'.'.($format === 'pdf' ? 'html' : 'csv');

        if ($format === 'pdf') {
            return response()
                ->view('admin.reports.export', ['type' => $type, 'rows' => $rows])
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
        }

        $csv = fopen('php://temp', 'w+');
        foreach ($rows as $row) {
            fputcsv($csv, $row->toArray());
        }
        rewind($csv);

        return response(stream_get_contents($csv), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function query(string $type)
    {
        return match ($type) {
            'faculty' => Faculty::with(['user', 'department'])->latest(),
            'fees' => Fee::with(['student.user', 'payments'])->latest(),
            'attendance' => Attendance::with('student.user')->latest(),
            'admissions' => Student::with(['user', 'campus', 'course'])->orderByDesc('admission_date'),
            default => Student::with(['user', 'campus', 'department', 'course'])->latest(),
        };
    }
}
