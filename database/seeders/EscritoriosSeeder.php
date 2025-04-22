<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EscritoriosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("escritorios")->insert([
            [
                "name" => "Contábil Bertotto",
                "cnpj" => "04861347000180",
                "cep" => "89503079",
                'rua' => "Rua Pinheiro Machado",
                'bairro' => "Paraíso",
                'cidade' => "Caçador",
                'uf' => "SC",
                'complemento' => "Nº 211",
                'obs' => "",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "name" => "CORPEL CONTABILIDADE",
                "cnpj" => "83222323000157",
                "cep" => "89700055",
                'rua' => "Rua Marechal Deodoro",
                'bairro' => "Centro",
                'cidade' => "Concórdia",
                'uf' => "SC",
                'complemento' => "Edifício Dom Afonso, 1º andar",
                'obs' => "",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "name" => "JP Contábil",
                "cnpj" => "77892883000116",
                "cep" => "89642000",
                'rua' => "Av. Irmãos Piccoli",
                'bairro' => "Centro",
                'cidade' => "Tangará",
                'uf' => "SC",
                'complemento' => "Nº 480",
                'obs' => "",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "name" => "Mocaplan",
                "cnpj" => "03017342000102",
                "cep" => "89600000",
                'rua' => "R. Santos Dumont",
                'bairro' => "Tobias",
                'cidade' => "Joaçaba",
                'uf' => "SC",
                'complemento' => "Nº 220 ",
                'obs' => "",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
