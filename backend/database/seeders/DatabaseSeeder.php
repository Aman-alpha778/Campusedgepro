<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Course;
use App\Models\Department;
use App\Models\Fee;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $password = Hash::make('password');

        $permissions = collect([
            ['Dashboard View', 'dashboard.view', 'Dashboard'],
            ['Campus Create', 'campus.create', 'Campus Management'],
            ['Campus View', 'campus.view', 'Campus Management'],
            ['Campus Update', 'campus.update', 'Campus Management'],
            ['Campus Delete', 'campus.delete', 'Campus Management'],
            ['Student Create', 'student.create', 'Student Management'],
            ['Student View', 'student.view', 'Student Management'],
            ['Student Update', 'student.update', 'Student Management'],
            ['Student Delete', 'student.delete', 'Student Management'],
            ['Faculty Create', 'faculty.create', 'Faculty Management'],
            ['Faculty View', 'faculty.view', 'Faculty Management'],
            ['Faculty Update', 'faculty.update', 'Faculty Management'],
            ['Faculty Delete', 'faculty.delete', 'Faculty Management'],
            ['Department View', 'department.view', 'Department Management'],
            ['Department Create', 'department.create', 'Department Management'],
            ['Department Update', 'department.update', 'Department Management'],
            ['Department Delete', 'department.delete', 'Department Management'],
            ['Department Assign HOD', 'department.assign_hod', 'Department Management'],
            ['Department Export', 'department.export', 'Department Management'],
            ['Fee Manage', 'fee.manage', 'Finance'],
            ['Payment View', 'payment.view', 'Finance'],
            ['Report View', 'report.view', 'Reports'],
            ['Report Export', 'report.export', 'Reports'],
        ])->map(fn ($item) => Permission::updateOrCreate(
            ['name' => $item[1]],
            ['guard_name' => 'web', 'group' => $item[2], 'description' => $item[0]]
        ));

        $roles = collect(['Super Admin', 'Campus Admin', 'Admission Officer', 'Accountant', 'Faculty', 'Student'])
            ->mapWithKeys(fn ($name) => [$name => Role::updateOrCreate(['name' => $name], ['guard_name' => 'web'])]);
        $roles['Super Admin']->permissions()->sync($permissions->pluck('id'));
        $roles['Campus Admin']->permissions()->sync($permissions->whereIn('name', ['dashboard.view', 'campus.view', 'student.view', 'faculty.view', 'report.view'])->pluck('id'));
        $roles['Admission Officer']->permissions()->sync($permissions->whereIn('name', ['dashboard.view', 'student.create', 'student.view', 'student.update'])->pluck('id'));
        $roles['Accountant']->permissions()->sync($permissions->whereIn('name', ['dashboard.view', 'fee.manage', 'payment.view', 'report.view'])->pluck('id'));
        $roles['Faculty']->permissions()->sync($permissions->whereIn('name', ['dashboard.view', 'student.view'])->pluck('id'));
        $roles['Student']->permissions()->sync($permissions->whereIn('name', ['dashboard.view'])->pluck('id'));

        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@campusedge.test'],
            ['name' => 'Super Admin', 'phone' => '+91 90000 00001', 'password' => $password, 'status' => 'active', 'is_admin' => true]
        );
        $superAdmin->syncRoles([$roles['Super Admin']->id]);

        $this->fillCampuses($now);
        $this->fillDepartments($now);
        $this->fillCourses($now);
        $this->fillFaculty($roles['Faculty']->id, $password, $now);
        $this->fillStudents($roles['Student']->id, $password, $now);
        $this->fillFees($now);
        $this->fillPayments($now);
        $this->fillAttendances($now);
        $this->fillNotices($superAdmin->id, $now);
        $this->fillSettings();
    }

    private function fillCampuses(Carbon $now): void
    {
        $missing = 5 - DB::table('campuses')->count();

        if ($missing <= 0) {
            return;
        }

        DB::table('campuses')->insert(collect(range(1, $missing))->map(fn ($index) => [
            'name' => "CampusEdge Campus {$index}",
            'code' => 'CMP'.strtoupper(Str::random(8)),
            'email' => 'campus'.Str::lower(Str::random(8)).'@campusedge.test',
            'phone' => '+91 90000 '.str_pad((string) $index, 5, '0', STR_PAD_LEFT),
            'address' => "Knowledge Park Block {$index}",
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'India',
            'logo' => null,
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());
    }

    private function fillDepartments(Carbon $now): void
    {
        DB::transaction(function () use ($now): void {
            $departmentIds = $this->syncIndianCollegeDepartments($now);
            $oldDepartmentIds = DB::table('departments')
                ->whereNotIn('id', $departmentIds)
                ->pluck('id');

            if ($oldDepartmentIds->isEmpty()) {
                $this->assignDepartmentHods($departmentIds);
                return;
            }

            $this->redistributeDepartmentRecords('courses', $oldDepartmentIds, $departmentIds);
            $this->redistributeDepartmentRecords('faculty', $oldDepartmentIds, $departmentIds);
            $this->redistributeDepartmentRecords('subjects', $oldDepartmentIds, $departmentIds);

            DB::statement('update students set department_id = courses.department_id from courses where students.course_id = courses.id');
            DB::table('department_hod_history')->whereIn('department_id', $oldDepartmentIds)->delete();
            DB::table('departments')->whereIn('id', $oldDepartmentIds)->delete();
            $this->assignDepartmentHods($departmentIds);
        });
    }

    private function syncIndianCollegeDepartments(Carbon $now): array
    {
        $departments = collect([
            ['Computer Science Engineering', 'CSE', 'Advanced computing, AI, software engineering, and data systems.', 'cse', 'Block A - First Floor', 1998, 180],
            ['Information Technology', 'IT', 'Information systems, cloud platforms, cybersecurity, and enterprise applications.', 'it', 'Block A - Second Floor', 2001, 120],
            ['Electronics and Communication Engineering', 'ECE', 'Embedded systems, VLSI, communication networks, and signal processing.', 'ece', 'Block B - First Floor', 2003, 120],
            ['Mechanical Engineering', 'ME', 'Design, manufacturing, thermal engineering, robotics, and industrial systems.', 'me', 'Workshop Block', 1996, 120],
            ['Business Administration', 'MBA', 'Management studies, finance, marketing, HR, operations, and entrepreneurship.', 'mba', 'Management Block', 2008, 90],
            ['Pharmaceutical Sciences', 'BPHARM', 'Pharmaceutics, pharmacology, medicinal chemistry, and clinical pharmacy.', 'pharmacy', 'Pharmacy Block', 2012, 100],
        ]);

        return $departments->map(function (array $department) use ($now): int {
            [$name, $code, $description, $emailPrefix, $location, $year, $intake] = $department;

            $model = Department::withTrashed()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $description,
                    'email' => "{$emailPrefix}.department@campusedge.test",
                    'phone' => '+91 80 '.fake()->numerify('#### ####'),
                    'location' => $location,
                    'established_year' => $year,
                    'total_intake' => $intake,
                    'status' => 'active',
                    'updated_at' => $now,
                ]
            );

            if ($model->trashed()) {
                $model->restore();
            }

            return $model->id;
        })->all();
    }

    private function redistributeDepartmentRecords(string $table, $oldDepartmentIds, array $targetDepartmentIds): void
    {
        DB::table($table)
            ->whereIn('department_id', $oldDepartmentIds)
            ->orderBy('id')
            ->pluck('id')
            ->values()
            ->each(function ($id, int $index) use ($table, $targetDepartmentIds): void {
                DB::table($table)
                    ->where('id', $id)
                    ->update(['department_id' => $targetDepartmentIds[$index % count($targetDepartmentIds)]]);
            });
    }

    private function assignDepartmentHods(array $departmentIds): void
    {
        collect($departmentIds)->each(function (int $departmentId): void {
            $hodUserId = DB::table('faculty')
                ->where('department_id', $departmentId)
                ->whereNull('deleted_at')
                ->orderBy('id')
                ->value('user_id');

            DB::table('departments')
                ->where('id', $departmentId)
                ->update(['hod_id' => $hodUserId]);
        });
    }

    private function fillCourses(Carbon $now): void
    {
        $missing = 20 - DB::table('courses')->count();

        if ($missing <= 0) {
            return;
        }

        $departmentIds = Department::pluck('id');
        DB::table('courses')->insert(collect(range(1, $missing))->map(fn ($index) => [
            'department_id' => $departmentIds->random(),
            'name' => fake()->unique()->words(3, true),
            'code' => 'CRS'.strtoupper(Str::random(8)),
            'duration' => fake()->randomElement(['2 Years', '3 Years', '4 Years']),
            'semester_count' => fake()->randomElement([4, 6, 8]),
            'total_semesters' => fake()->randomElement([4, 6, 8]),
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());
    }

    private function fillFaculty(int $roleId, string $password, Carbon $now): void
    {
        $missing = 50 - DB::table('faculty')->count();

        if ($missing <= 0) {
            return;
        }

        $departmentIds = Department::pluck('id');
        $emails = collect(range(1, $missing))->map(fn () => 'faculty.'.Str::lower(Str::random(12)).'@campusedge.test');
        DB::table('users')->insert($emails->map(fn ($email) => [
            'name' => fake()->name(),
            'email' => $email,
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => $now,
            'password' => $password,
            'status' => 'active',
            'is_admin' => false,
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());

        $users = User::whereIn('email', $emails)->pluck('id', 'email');
        DB::table('model_has_roles')->insert($users->values()->map(fn ($userId) => [
            'role_id' => $roleId,
            'model_type' => User::class,
            'model_id' => $userId,
        ])->all());
        DB::table('faculty')->insert($users->values()->map(fn ($userId) => [
            'user_id' => $userId,
            'department_id' => $departmentIds->random(),
            'employee_id' => 'FAC'.strtoupper(Str::random(8)),
            'designation' => fake()->randomElement(['Professor', 'Associate Professor', 'Assistant Professor', 'Lecturer', 'HOD']),
            'qualification' => fake()->randomElement(['M.Tech', 'Ph.D', 'MBA', 'M.Sc', 'M.Com']),
            'experience' => fake()->numberBetween(1, 20),
            'joining_date' => fake()->dateTimeBetween('-8 years', '-1 month')->format('Y-m-d'),
            'salary' => fake()->numberBetween(35000, 150000),
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());

        Department::whereNull('hod_id')->get()->each(function (Department $department): void {
            $hodUserId = DB::table('faculty')->where('department_id', $department->id)->value('user_id');
            $department->update(['hod_id' => $hodUserId]);
        });
    }

    private function fillStudents(int $roleId, string $password, Carbon $now): void
    {
        $missing = 500 - DB::table('students')->count();

        if ($missing <= 0) {
            return;
        }

        $courses = Course::with('department')->get();
        $emails = collect(range(1, $missing))->map(fn () => 'student.'.Str::lower(Str::random(12)).'@campusedge.test');
        DB::table('users')->insert($emails->map(fn ($email) => [
            'name' => fake()->name(),
            'email' => $email,
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => $now,
            'password' => $password,
            'status' => 'active',
            'is_admin' => false,
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());

        $users = User::whereIn('email', $emails)->pluck('id', 'email');
        DB::table('model_has_roles')->insert($users->values()->map(fn ($userId) => [
            'role_id' => $roleId,
            'model_type' => User::class,
            'model_id' => $userId,
        ])->all());
        DB::table('students')->insert($users->values()->map(function ($userId) use ($courses, $now): array {
            $course = $courses->random();

            return [
                'user_id' => $userId,
                'campus_id' => Campus::query()->value('id'),
                'department_id' => $course->department_id,
                'course_id' => $course->id,
                'roll_number' => 'ROLL'.strtoupper(Str::random(8)),
                'semester' => fake()->numberBetween(1, max(1, (int) $course->total_semesters)),
                'registration_number' => 'REG'.strtoupper(Str::random(8)),
                'dob' => fake()->dateTimeBetween('-24 years', '-17 years')->format('Y-m-d'),
                'gender' => fake()->randomElement(['Male', 'Female', 'Other']),
                'address' => fake()->address(),
                'guardian_name' => fake()->name(),
                'guardian_phone' => fake()->phoneNumber(),
                'admission_date' => fake()->dateTimeBetween('-18 months', 'now')->format('Y-m-d'),
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all());
    }

    private function fillFees(Carbon $now): void
    {
        $missing = 100 - DB::table('fees')->count();

        if ($missing <= 0) {
            return;
        }

        $studentIds = DB::table('students')->pluck('id');
        DB::table('fees')->insert(collect(range(1, $missing))->map(fn () => [
            'student_id' => $studentIds->random(),
            'fee_type' => fake()->randomElement(['Tuition Fee', 'Library Fee', 'Hostel Fee', 'Exam Fee', 'Transport Fee']),
            'amount' => fake()->numberBetween(5000, 90000),
            'due_date' => fake()->dateTimeBetween('-2 months', '+4 months')->format('Y-m-d'),
            'status' => fake()->randomElement(['pending', 'partial', 'paid']),
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());
    }

    private function fillPayments(Carbon $now): void
    {
        $missing = 100 - DB::table('payments')->count();

        if ($missing <= 0) {
            return;
        }

        $fees = Fee::all();
        DB::table('payments')->insert(collect(range(1, $missing))->map(function () use ($fees, $now): array {
            $fee = $fees->random();

            return [
                'fee_id' => $fee->id,
                'amount' => min(fake()->numberBetween(1000, 50000), (float) $fee->amount),
                'payment_method' => fake()->randomElement(['UPI', 'Card', 'Bank Transfer', 'Cash']),
                'transaction_id' => 'TXN'.strtoupper(Str::random(16)),
                'payment_date' => fake()->dateTimeBetween('-12 months', 'now')->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all());

        Fee::withSum('payments', 'amount')->get()->each(function (Fee $fee): void {
            $fee->update(['status' => ($fee->payments_sum_amount ?? 0) >= $fee->amount ? 'paid' : 'partial']);
        });
    }

    private function fillAttendances(Carbon $now): void
    {
        if (DB::table('attendances')->count() >= 2400) {
            return;
        }

        $rows = DB::table('students')->limit(200)->pluck('id')->flatMap(fn ($studentId) => collect(range(0, 11))->map(fn ($daysBack) => [
            'student_id' => $studentId,
            'attendance_date' => now()->subDays($daysBack)->toDateString(),
            'status' => fake()->randomElement(['present', 'present', 'present', 'absent']),
            'created_at' => $now,
            'updated_at' => $now,
        ]))->all();

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('attendances')->insertOrIgnore($chunk);
        }
    }

    private function fillNotices(int $userId, Carbon $now): void
    {
        $missing = 20 - DB::table('notices')->count();

        if ($missing <= 0) {
            return;
        }

        DB::table('notices')->insert(collect(range(1, $missing))->map(fn () => [
            'title' => fake()->sentence(5),
            'description' => fake()->paragraphs(2, true),
            'created_by' => $userId,
            'publish_date' => fake()->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'status' => fake()->randomElement(['draft', 'published', 'published']),
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());
    }

    private function fillSettings(): void
    {
        Setting::updateOrCreate(
            ['key' => 'institution'],
            [
                'value' => null,
                'college_name' => 'CampusEdge Demo University',
                'logo' => '/assets/logoas.png',
                'email' => 'admin@campusedge.test',
                'phone' => '+91 90000 00000',
                'address' => 'Knowledge Park, Bengaluru, Karnataka',
                'website' => 'https://campusedge.test',
            ]
        );

        collect([
            'college_name' => 'CampusEdge Demo University',
            'logo' => '/assets/logoas.png',
            'email' => 'admin@campusedge.test',
            'phone' => '+91 90000 00000',
            'address' => 'Knowledge Park, Bengaluru, Karnataka',
            'theme' => 'default',
        ])->each(fn ($value, $key) => Setting::updateOrCreate(['key' => $key], ['value' => $value]));
    }
}
