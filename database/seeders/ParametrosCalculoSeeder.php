<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParametrosCalculo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParametrosCalculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usando o modelo Eloquent para inserir os dados na tabela parametros_calculo
        ParametrosCalculo::create([
            'descricao' => 'Juros',
            'indice' => 0.03,
            'P_V' => 'P',
            'aplicacao' => 'ao dia',
        ]);

        ParametrosCalculo::create([
            'descricao' => 'Multa',
            'indice' => 1.00,
            'P_V' => 'P',
            'aplicacao' => 'valor da parcela/lancamento',
        ]);

        ParametrosCalculo::create([
            'descricao' => 'Descontos',
            'indice' => 5.00,
            'P_V' => 'P',
            'aplicacao' => 'valor pago antes do vencimento',
        ]);
    }
}
