<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop unique constraint yang lama menggunakan raw SQL dengan try-catch
        try {
            DB::statement('ALTER TABLE ticket_priorities DROP INDEX ticket_priorities_name_unique');
        } catch (\Exception $e) {
            // Index mungkin sudah di-drop atau nama berbeda, lanjutkan saja
        }
        
        Schema::table('ticket_priorities', function (Blueprint $table) {
            // Buat unique constraint baru yang kombinasi name + team_id
            $table->unique(['name', 'team_id'], 'ticket_priorities_name_team_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_priorities', function (Blueprint $table) {
            // Drop unique constraint kombinasi
            $table->dropUnique('ticket_priorities_name_team_unique');
            
            // Kembalikan unique constraint name saja
            $table->unique('name');
        });
    }
};
