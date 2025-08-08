<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        // Chave estrangeira para ligar o item à sua categoria
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->text('description')->nullable(); // Descrição pode ser opcional
        $table->decimal('price', 8, 2); // 8 dígitos no total, 2 depois da vírgula
        $table->string('image')->nullable(); // Caminho para a foto
        $table->boolean('is_available')->default(true); // Se está disponível ou não
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};
