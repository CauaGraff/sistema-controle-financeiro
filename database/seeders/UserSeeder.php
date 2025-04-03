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
                'name' => 'ADM',
                'email' => 'adm@adm.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'cliente teste',
                'email' => 'cliente@teste.com',
                'password' => bcrypt('masterkey'),
                'id_typeuser' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
