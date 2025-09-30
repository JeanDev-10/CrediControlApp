<?php

namespace Database\Factories;

use App\Models\Debt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pay>
 */
class PayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->randomFloat(2, 5, 500),
            'date' => $this->faker->date(),
            'debt_id' => Debt::factory(),
        ];
    }
}
