<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('symbol')->nullable();
            $table->text('description')->nullable();

            // Additional columns
            $table->string('type'); // e.g., 'length', 'weight', 'volume', etc.
            $table->string('si_unit')->nullable(); // Base SI unit if applicable
            $table->decimal('conversion_factor', 20, 10)->default(1); // Conversion factor to base unit
            $table->string('dimension')->nullable(); // Physical dimension (e.g., L, M, T for length, mass, time)
            $table->string('system')->default('metric'); // metric, imperial, etc.
            $table->boolean('is_base_unit')->default(false);
            $table->string('category')->nullable(); // General category
            $table->string('unit_system')->nullable(); // Specific system (e.g., SI, CGS)
            $table->string('unit_group')->nullable(); // Group within system
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable(); // For any additional data

            // Relations
            $table->foreignUuid('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignUuid('updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};
