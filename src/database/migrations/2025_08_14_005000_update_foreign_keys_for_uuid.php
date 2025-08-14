<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Update sessions table to reference users.uuid
        Schema::table('sessions', function (Blueprint $table) {
            // Drop existing user_id column if it exists
            if (Schema::hasColumn('sessions', 'user_id')) {
                $table->dropColumn('user_id');
            }
            
            // Add user_uuid column if it doesn't exist
            if (!Schema::hasColumn('sessions', 'user_uuid')) {
                $table->uuid('user_uuid')->nullable()->after('id');
                $table->index('user_uuid');
            }
        });

        // 2. Update unit_user table to use UUIDs
        Schema::table('unit_user', function (Blueprint $table) {
            // Drop existing foreign key constraints
            if (Schema::hasColumn('unit_user', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->renameColumn('user_id', 'user_uuid');
            }
            
            if (Schema::hasColumn('unit_user', 'unit_id')) {
                $table->dropForeign(['unit_id']);
                $table->renameColumn('unit_id', 'unit_uuid');
            }
            
            // Add new foreign key constraints
            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users')
                ->cascadeOnDelete();
                
            $table->foreign('unit_uuid')
                ->references('id')
                ->on('units')
                ->cascadeOnDelete();
        });

        // 3. Update units table created_by and updated_by
        Schema::table('units', function (Blueprint $table) {
            // Drop existing foreign key constraints if they exist
            if (Schema::hasColumn('units', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->renameColumn('created_by', 'created_by_uuid');
            }
            
            if (Schema::hasColumn('units', 'updated_by')) {
                $table->dropForeign(['updated_by']);
                $table->renameColumn('updated_by', 'updated_by_uuid');
            }
            
            // Add new foreign key constraints
            $table->foreign('created_by_uuid')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by_uuid')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();
        });

        // 4. Update drivers table unit_id
        Schema::table('drivers', function (Blueprint $table) {
            if (Schema::hasColumn('drivers', 'unit_id')) {
                $table->dropForeign(['unit_id']);
                $table->renameColumn('unit_id', 'unit_uuid');
                
                $table->foreign('unit_uuid')
                    ->references('id')
                    ->on('units')
                    ->nullOnDelete();
            }
        });

        // 5. Update sensors table unit_id
        Schema::table('sensors', function (Blueprint $table) {
            if (Schema::hasColumn('sensors', 'unit_id')) {
                $table->dropForeign(['unit_id']);
                $table->renameColumn('unit_id', 'unit_uuid');
                
                $table->foreign('unit_uuid')
                    ->references('id')
                    ->on('units')
                    ->cascadeOnDelete();
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Note: Rolling back this migration is complex and may result in data loss
        // It's recommended to restore from a backup instead
        
        Schema::disableForeignKeyConstraints();

        // 5. Revert sensors table
        Schema::table('sensors', function (Blueprint $table) {
            $table->dropForeign(['unit_uuid']);
            $table->renameColumn('unit_uuid', 'unit_id');
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->cascadeOnDelete();
        });

        // 4. Revert drivers table
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['unit_uuid']);
            $table->renameColumn('unit_uuid', 'unit_id');
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->nullOnDelete();
        });

        // 3. Revert units table
        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['created_by_uuid']);
            $table->dropForeign(['updated_by_uuid']);
            
            $table->renameColumn('created_by_uuid', 'created_by');
            $table->renameColumn('updated_by_uuid', 'updated_by');
            
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        // 2. Revert unit_user table
        Schema::table('unit_user', function (Blueprint $table) {
            $table->dropForeign(['user_uuid']);
            $table->dropForeign(['unit_uuid']);
            
            $table->renameColumn('user_uuid', 'user_id');
            $table->renameColumn('unit_uuid', 'unit_id');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
                
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->cascadeOnDelete();
        });

        // 1. Revert sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex('sessions_user_uuid_index');
            $table->dropColumn('user_uuid');
            $table->foreignId('user_id')->nullable()->after('id');
        });

        Schema::enableForeignKeyConstraints();
    }
};
