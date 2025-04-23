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
        // Empresas para cada cliente
        DB::table('empresas')->insert([
            [
                'nome' => 'Empresa Cliente Corpel',
                'cnpj_cpf' => '12345678000101',
                'cep' => '12345000',
                'cidade' => 'Cidade Corpel',
                'bairro' => 'Bairro Corpel',
                'rua' => 'Rua Corpel 123',
                'id_escritorio' => 2,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nome' => 'Empresa Cliente Bertotto',
                'cnpj_cpf' => '12345678000102',
                'cep' => '12345001',
                'cidade' => 'Cidade Bertotto',
                'bairro' => 'Bairro Bertotto',
                'rua' => 'Rua Bertotto 456',
                'id_escritorio' => 1,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nome' => 'Empresa Cliente JP',
                'cnpj_cpf' => '12345678000103',
                'cep' => '12345002',
                'cidade' => 'Cidade JP',
                'bairro' => 'Bairro JP',
                'rua' => 'Rua JP 789',
                'id_escritorio' => 3,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nome' => 'Empresa Cliente Mocaplan',
                'cnpj_cpf' => '12345678000104',
                'cep' => '12345003',
                'cidade' => 'Cidade Mocaplan',
                'bairro' => 'Bairro Mocaplan',
                'rua' => 'Rua Mocaplan 101',
                'id_escritorio' => 4,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Relacionamento entre usuários e empresas
        DB::table('users_empresas')->insert([
            [
                'id_user' => 5, // Cliente Corpel
                'id_empresas' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_user' => 6, // Cliente Bertotto
                'id_empresas' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_user' => 7, // Cliente JP
                'id_empresas' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_user' => 8, // Cliente Mocaplan
                'id_empresas' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
