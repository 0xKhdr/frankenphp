<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('unit_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUuid('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('role')->default('member');
            $table->json('permissions')->nullable();
            $table->timestamps();

            // Ensure each user can only be added to a unit once
            $table->unique(['unit_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_user');
    }
};
