<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Deal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'deal_id' => Deal::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'completed' => $this->faker->boolean(30),
        ];
    }
}
