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
            // Verifica se a coluna já existe para evitar erros em migrações repetidas
            if (!Schema::hasColumn('users', 'restaurant_name')) { 
                $table->string('restaurant_name')->nullable()->after('name'); // <--- ADICIONADO ->nullable()
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verifica se a coluna existe antes de tentar removê-la
            if (Schema::hasColumn('users', 'restaurant_name')) { 
                $table->dropColumn('restaurant_name');
            }
        });
    }
};