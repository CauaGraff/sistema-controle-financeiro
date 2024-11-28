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
        Schema::create('parametros_calculo', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('indice');
            $table->char('P_V');
            $table->string('aplicacao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_calculo');
    }
};
