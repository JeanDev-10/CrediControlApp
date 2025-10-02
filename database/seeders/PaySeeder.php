<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Pay;
use Illuminate\Database\Seeder;

class PaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pay::factory(50)->create([
            'debt_id' => rand(1, 100), // 👈 lo asociamos directamente aquí
        ])->each(function ($pay) {
            // Crear entre 1 y 3 imágenes para cada pago
            Image::factory(rand(1, 3))->create([
                'pay_id' => $pay->id,
            ]);
        });

    }
}
