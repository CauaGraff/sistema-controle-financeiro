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
        Schema::create('categorias_de_contas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->unsignedBigInteger('id_empresa'); // Referência à empresa
            $table->unsignedBigInteger('id_categoria_pai')->nullable(); // Referência à categoria pai (para grupos)
            $table->timestamps();
            $table->foreign('id_empresa')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('id_categoria_pai')->references('id')->on('categorias_de_contas')->onDelete('cascade'); // Auto-referência para categoria pai
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias_de_contas');
    }
};
