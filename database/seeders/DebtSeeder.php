<?php

namespace Database\Seeders;

use App\Models\Debt;
use Illuminate\Database\Seeder;

class DebtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Debt::factory()->count(30)->create([
            'user_id' => 1,
        ])->each(function ($debt) {
            $debt->update([
                'contact_id' => rand(1, 20),
            ]);
        });
        Debt::factory()->count(30)->create([
            'user_id' => 2,
        ])->each(function ($debt) {
            $debt->update([
                'contact_id' => rand(1, 20),
            ]);
        });

        /* Debt::factory()->count(30)->create([
            "user_id" => 2
        ]); */
    }
}
