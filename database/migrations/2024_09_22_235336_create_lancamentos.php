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
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->id();
            $table->string('descrição');
            $table->decimal('valor', 10, 2);
            $table->enum('tipo', ['R', 'P']); // Crédito ou débito
            $table->date('data_venc');
            $table->string('anexo');
            $table->unsignedBigInteger('id_categoria');
            $table->unsignedBigInteger('id_empresa');
            $table->unsignedBigInteger('id_favorecido');
            $table->timestamps();
            $table->foreign('id_categoria')->references('id')->on('categorias_de_contas');
            $table->foreign('id_empresa')->references('id')->on('empresas');
            $table->foreign('id_favorecido')->references('id')->on('favorecido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamentos');
    }
};
