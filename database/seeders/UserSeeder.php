<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insert([
            [
                'id' => 1,
                'name' => 'Corpel ADM',
                'email' => 'corpel@adm.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 1,
                'id_escritorio' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Bertotto ADM',
                'email' => 'bertotto@adm.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 1,
                'id_escritorio' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'JP ADM',
                'email' => 'jp@adm.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 1,
                'id_escritorio' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'name' => 'Mocaplan ADM',
                'email' => 'mocaplan@adm.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 1,
                'id_escritorio' => 4,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Clientes de teste para cada escritório
            [
                'id' => 5,
                'name' => 'Cliente Corpel',
                'email' => 'cliente.corpel@teste.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 3,
                'id_escritorio' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'name' => 'Cliente Bertotto',
                'email' => 'cliente.bertotto@teste.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 3,
                'id_escritorio' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 7,
                'name' => 'Cliente JP',
                'email' => 'cliente.jp@teste.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 3,
                'id_escritorio' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'name' => 'Cliente Mocaplan',
                'email' => 'cliente.mocaplan@teste.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 3,
                'id_escritorio' => 4,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
