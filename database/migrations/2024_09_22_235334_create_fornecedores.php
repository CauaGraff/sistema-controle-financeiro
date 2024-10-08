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
        Schema::create('favorecido', function (Blueprint $table) {
            $table->id();
            $table->string("cnpj_cpf");
            $table->string("telefone");
            $table->string("email");
            $table->char("cep", 8);
            $table->char("uf");
            $table->string("cidade");
            $table->string("bairro");
            $table->string("rua");
            $table->text("complemento");
            $table->enum('tipo', ['F', 'C']); // Crédito ou débito
            $table->unsignedBigInteger('id_empresa');
            $table->foreign('id_empresa')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornecedores');
    }
};
