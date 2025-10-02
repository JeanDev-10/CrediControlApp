<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Debt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Debt>
 */
class DebtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateStart = $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d');

        return [
            'quantity' => $this->faker->randomFloat(2, 20, 2000),
            'description' => $this->faker->sentence(3),
            'date_start' => $dateStart,
            'status' => $this->faker->randomElement(['pendiente', 'pagada']),
            'contact_id' => Contact::factory(),
            'user_id' => User::factory(),
        ];
    }
}
