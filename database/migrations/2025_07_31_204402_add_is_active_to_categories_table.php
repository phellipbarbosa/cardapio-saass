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
        Schema::table('categories', function (Blueprint $table) {
            // Adiciona a coluna is_active como booleano, com valor padrão true
            // Verifica se a coluna já existe para evitar erros em migrações repetidas
            if (!Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('image_path'); 
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Remove a coluna is_active
            if (Schema::hasColumn('categories', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};