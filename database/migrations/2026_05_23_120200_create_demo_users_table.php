<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demo_users', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('request_id')->unique()->constrained('demo_requests')->cascadeOnDelete();
            $table->string('username')->unique();
            $table->string('password');
            $table->dateTime('expiry_date')->index();
            $table->string('status', 20)->default('Active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_users');
    }
};
