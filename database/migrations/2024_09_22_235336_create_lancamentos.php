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
            $table->unsignedBigInteger('id_plano_contas');
            $table->unsignedBigInteger('id_empresa');
            $table->timestamps();
            $table->foreign('id_plano_contas')->references('id')->on('plano_de_contas');
            $table->foreign('id_empresa')->references('id')->on('empresas');
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
