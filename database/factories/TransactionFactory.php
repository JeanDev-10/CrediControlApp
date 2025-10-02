<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(10),
            'type' => $this->faker->randomElement(['ingreso', 'egreso', 'actualizacion']),
            'quantity' => $this->faker->randomFloat(2, 1, 1000),
            'previus_quantity' => $this->faker->randomFloat(2, 1, 1000),
            'after_quantity' => $this->faker->randomFloat(2, 1, 1000),
            'user_id' => User::factory(), // Relaciona con un usuario de prueba
        ];
    }
}
