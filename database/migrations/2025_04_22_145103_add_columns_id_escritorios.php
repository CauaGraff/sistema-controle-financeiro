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
        Schema::table('empresas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_escritorio')->nullable()->after('rua'); // ou qualquer outra coluna de referência
            $table->foreign('id_escritorio')->references('id')->on('escritorios');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_escritorio')->nullable()->after('id_typeuser');
            $table->foreign('id_escritorio')->references('id')->on('escritorios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('id_escritorio');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_escritorio');
        });
    }
};
