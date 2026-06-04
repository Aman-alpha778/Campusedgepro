<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table): void {
            if (! Schema::hasColumn('departments', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('code');
            }
            if (! Schema::hasColumn('departments', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('departments', 'email')) {
                $table->string('email')->nullable()->after('description');
            }
            if (! Schema::hasColumn('departments', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (! Schema::hasColumn('departments', 'location')) {
                $table->string('location')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('departments', 'established_year')) {
                $table->unsignedSmallInteger('established_year')->nullable()->after('hod_id');
            }
            if (! Schema::hasColumn('departments', 'total_intake')) {
                $table->unsignedInteger('total_intake')->default(0)->after('established_year');
            }
            if (! Schema::hasColumn('departments', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('departments', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            }
        });

        DB::table('departments')
            ->whereNull('slug')
            ->orderBy('id')
            ->get(['id', 'name', 'code'])
            ->each(function ($department): void {
                DB::table('departments')
                    ->where('id', $department->id)
                    ->update(['slug' => str($department->name.'-'.$department->code)->slug()]);
            });

        if (Schema::hasColumn('departments', 'campus_id')) {
            try {
                Schema::table('departments', fn (Blueprint $table) => $table->dropForeign(['campus_id']));
            } catch (Throwable) {
                // Constraint may not exist in some local databases.
            }
            Schema::table('departments', fn (Blueprint $table) => $table->dropColumn('campus_id'));
        }

        Schema::table('settings', function (Blueprint $table): void {
            foreach (['college_name', 'logo', 'email', 'phone', 'address', 'website'] as $column) {
                if (! Schema::hasColumn('settings', $column)) {
                    $table->text($column)->nullable();
                }
            }
        });

        DB::table('settings')->updateOrInsert(
            ['key' => 'institution'],
            [
                'value' => null,
                'college_name' => 'CampusEdge Institute',
                'logo' => '/assets/logoas.png',
                'email' => 'admin@campusedge.test',
                'phone' => '+91 90000 00001',
                'address' => 'Single Institution Campus, India',
                'website' => 'https://campusedge.test',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        if (! Schema::hasTable('department_hod_history')) {
            Schema::create('department_hod_history', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('department_id')->constrained()->cascadeOnDelete();
                $table->foreignId('old_hod')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('new_hod')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (! Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('department_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('status')->default('active')->index();
                $table->timestamps();
            });
        }

        Schema::table('faculty', function (Blueprint $table): void {
            if (! Schema::hasColumn('faculty', 'designation')) {
                $table->string('designation')->nullable()->after('employee_id');
            }
        });

        Schema::table('students', function (Blueprint $table): void {
            if (! Schema::hasColumn('students', 'semester')) {
                $table->unsignedTinyInteger('semester')->nullable()->after('roll_number');
            }
        });

        Schema::table('courses', function (Blueprint $table): void {
            if (! Schema::hasColumn('courses', 'semester_count')) {
                $table->unsignedTinyInteger('semester_count')->nullable()->after('duration');
            }
        });

        DB::table('courses')->whereNull('semester_count')->update(['semester_count' => DB::raw('total_semesters')]);
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('department_hod_history');
    }
};
