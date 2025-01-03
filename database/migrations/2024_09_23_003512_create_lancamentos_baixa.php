<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lancamentos_baixa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lancamento');
            $table->unsignedBigInteger('id_contaBancaria');
            $table->decimal('valor', 10, 2)->default('0.00');
            $table->decimal('juros', 10, 2)->default('0.00');
            $table->decimal('multa', 10, 2)->default('0.00');
            $table->decimal('desconto', 10, 2)->default('0.00');
            $table->string('doc')->nullable();
            $table->string('anexo')->nullable();
            $table->timestamps();
            $table->foreign(columns: 'id_lancamento')->references('id')->on('lancamentos');
            $table->foreign(columns: 'id_contaBancaria')->references('id')->on('contas_banco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamentos_baixa');
    }
};
