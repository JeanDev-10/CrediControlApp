<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::factory()->count(20)->create([
            'user_id' => 1, // por ejemplo, el usuario admin
        ]);
        Contact::factory()->count(20)->create([
            'user_id' => 2, // por ejemplo, el usuario admin
        ]);
    }
}
