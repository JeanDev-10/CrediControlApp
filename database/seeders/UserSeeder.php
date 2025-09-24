<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Jean Pierre',
            "lastname" => "Rodríguez",
            'email' => "jean@hotmail.com",
            "password"=>bcrypt('jean1234'),
        ]);
        User::create([
            'name' => 'test user 2',
            "lastname" => "apellido",
            'email' => "test2@hotmail.com",
           "password"=>bcrypt('password1234'),
        ]);
    }
}
