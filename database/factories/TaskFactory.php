<?php

namespace Database\Factories;

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
            'name' => fake()->text(25),
            'status' => 'to-do',
            'description' => fake()->text(150),
            'deadline' => fake()->dateTimeBetween('now', '+1 week'),
        ];
    }

}
