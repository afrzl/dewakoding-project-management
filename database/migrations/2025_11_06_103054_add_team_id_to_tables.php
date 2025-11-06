<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add team_id to projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('team_id')->default(1)->after('id')->constrained()->cascadeOnDelete();
        });

        // Add team_id to tickets table
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('team_id')->default(1)->after('id')->constrained()->cascadeOnDelete();
        });

        // Add team_id to ticket_statuses table
        Schema::table('ticket_statuses', function (Blueprint $table) {
            $table->foreignId('team_id')->default(1)->after('id')->constrained()->cascadeOnDelete();
        });

        // Add team_id to ticket_priorities table
        Schema::table('ticket_priorities', function (Blueprint $table) {
            $table->foreignId('team_id')->default(1)->after('id')->constrained()->cascadeOnDelete();
        });

        // Add team_id to ticket_comments table
        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->foreignId('team_id')->default(1)->after('id')->constrained()->cascadeOnDelete();
        });

        // Add team_id to notifications table
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('team_id')->default(1)->after('id')->constrained()->cascadeOnDelete();
        });

        // Add team_id to epics table if exists
        if (Schema::hasTable('epics')) {
            Schema::table('epics', function (Blueprint $table) {
                $table->foreignId('team_id')->default(1)->after('id')->constrained()->cascadeOnDelete();
            });
        }

        // Add team_id to project_notes table if exists
        if (Schema::hasTable('project_notes')) {
            Schema::table('project_notes', function (Blueprint $table) {
                $table->foreignId('team_id')->default(1)->after('id')->constrained()->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });

        Schema::table('ticket_statuses', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });

        Schema::table('ticket_priorities', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });

        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });

        if (Schema::hasTable('epics')) {
            Schema::table('epics', function (Blueprint $table) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            });
        }

        if (Schema::hasTable('project_notes')) {
            Schema::table('project_notes', function (Blueprint $table) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            });
        }
    }
};
