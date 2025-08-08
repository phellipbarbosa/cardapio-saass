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
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a coluna slug, que deve ser única e pode ser nula inicialmente
            // Verifica se a coluna já existe para evitar erros em migrações repetidas
            if (!Schema::hasColumn('users', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove a coluna slug
            if (Schema::hasColumn('users', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};