<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lancamento_recorrencias_config', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->enum('tipo_recorrencia', ['diaria', 'semanal', 'mensal', 'anual']);
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->decimal('valor', 15, 2);
            $table->boolean('ativo')->default(true);
            $table->enum('tipo', ['P', 'R']);
            $table->unsignedBigInteger('id_categoria');
            $table->unsignedBigInteger('id_empresa');
            $table->unsignedBigInteger('id_fornecedor_cliente')->nullable();
            $table->timestamps();

            // Relacionamentos
            $table->foreign('id_categoria')->references('id')->on('categorias_de_contas');
            $table->foreign('id_empresa')->references('id')->on('empresas');
            $table->foreign('id_fornecedor_cliente')->references('id')->on('fornecedor_cliente');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lancamento_recorrencias_config');
    }
};
