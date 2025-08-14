<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('type'); // e.g., 'temperature', 'pressure', 'humidity'
            $table->string('model_number')->nullable();
            $table->string('serial_number')->unique();
            $table->foreignUuid('unit_id')->constrained('units')->cascadeOnDelete();
            $table->json('specifications')->nullable();
            $table->dateTime('last_calibration_date')->nullable();
            $table->dateTime('next_calibration_date')->nullable();
            $table->string('status')->default('active');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
