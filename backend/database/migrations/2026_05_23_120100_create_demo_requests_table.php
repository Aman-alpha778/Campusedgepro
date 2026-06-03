<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demo_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('college_name');
            $table->string('admin_name');
            $table->string('email')->index();
            $table->string('phone', 50);
            $table->string('student_strength');
            $table->text('requirements')->nullable();
            $table->string('status', 20)->default('Pending')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_requests');
    }
};
