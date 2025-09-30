<?php

namespace Database\Seeders;

use App\Models\Debt;
use App\Models\Image;
use App\Models\Pay;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pay::factory(50)->create()->each(function ($pay) {
            $debt = Debt::factory()->create([
                'user_id' => 1,
            ]);
            Image::factory(rand(1, 3))->create([
                'pay_id' => $pay->id,
            ]);
            $pay->debt()->associate($debt)->save();
        });

    }
}
