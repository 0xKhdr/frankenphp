<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Temporarily disable foreign key constraints
        Schema::disableForeignKeyConstraints();

        // 1. Add a new UUID column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable();
        });

        // 2. Update existing users with UUIDs
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['uuid' => (string) Illuminate\Support\Str::uuid()]);
        }

        // 3. Make the uuid column not nullable and unique
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });

        // 4. Update the sessions table to use uuid
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->uuid('user_uuid')->nullable()->after('id');
            
            // Add index for better performance
            $table->index('user_uuid');
        });

        // 5. Update the unit_user table to use uuid
        Schema::table('unit_user', function (Blueprint $table) {
            // Drop existing foreign key constraints
            $table->dropForeign(['user_id']);
            
            // Rename the column to indicate it will store UUIDs
            $table->renameColumn('user_id', 'user_uuid');
            
            // Add new foreign key constraint
            $table->foreign('user_uuid')
                ->references('uuid')
                ->on('users')
                ->cascadeOnDelete();
        });

        // 6. Update the units table to use uuid for created_by and updated_by
        Schema::table('units', function (Blueprint $table) {
            // Drop existing foreign key constraints
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            // Rename columns to indicate they store UUIDs
            $table->renameColumn('created_by', 'created_by_uuid');
            $table->renameColumn('updated_by', 'updated_by_uuid');
            
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

        // 7. Update the drivers table to use uuid for unit_id
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            
            // Rename the column to indicate it stores UUIDs
            $table->renameColumn('unit_id', 'unit_uuid');
            
            // Add new foreign key constraint
            $table->foreign('unit_uuid')
                ->references('id')
                ->on('units')
                ->nullOnDelete();
        });

        // 8. Update the sensors table to use uuid for unit_id
        Schema::table('sensors', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            
            // Rename the column to indicate it stores UUIDs
            $table->renameColumn('unit_id', 'unit_uuid');
            
            // Add new foreign key constraint
            $table->foreign('unit_uuid')
                ->references('id')
                ->on('units')
                ->cascadeOnDelete();
        });

        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Note: Rolling back this migration is complex and may result in data loss
        // It's recommended to restore from a backup instead
        
        Schema::disableForeignKeyConstraints();
        
        // Reverse the changes in reverse order
        
        // 8. Revert sensors table
        Schema::table('sensors', function (Blueprint $table) {
            $table->dropForeign(['unit_uuid']);
            $table->renameColumn('unit_uuid', 'unit_id');
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->cascadeOnDelete();
        });
        
        // 7. Revert drivers table
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['unit_uuid']);
            $table->renameColumn('unit_uuid', 'unit_id');
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->nullOnDelete();
        });
        
        // 6. Revert units table
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
        
        // 5. Revert unit_user table
        Schema::table('unit_user', function (Blueprint $table) {
            $table->dropForeign(['user_uuid']);
            $table->renameColumn('user_uuid', 'user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
        
        // 4. Revert sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex('sessions_user_uuid_index');
            $table->dropColumn('user_uuid');
            $table->foreignId('user_id')->nullable()->after('id');
        });
        
        // 1-3. Revert users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        
        Schema::enableForeignKeyConstraints();
    }
};
