<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('empresas')->insert([
            [
                'nome' => 'Empresa Exemplo 1',
                'cnpj_cpf' => '12345678000199',
                'cep' => '12345678',
                'cidade' => 'Cidade Exemplo',
                'bairro' => 'Bairro Exemplo',
                'rua' => 'Rua Exemplo 123',
                'id_escritorio' => 2,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('users_empresas')->insert([
            [
                'id_user' => 2,
                'id_empresas' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
