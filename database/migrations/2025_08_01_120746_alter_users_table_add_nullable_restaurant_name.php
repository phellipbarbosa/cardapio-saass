<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verifica se a coluna existe antes de tentar alterá-la
            if (Schema::hasColumn('users', 'restaurant_name')) {
                $table->string('restaurant_name')->nullable()->change(); // Altera para nullable
            } else {
                // Se a coluna não existir, adicione-a como nullable
                $table->string('restaurant_name')->nullable()->after('name'); // Ou onde for mais lógico
            }
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Se a coluna foi adicionada nesta migration, remova-a
            if (Schema::hasColumn('users', 'restaurant_name')) {
                $table->dropColumn('restaurant_name');
            }
        });
    }
};