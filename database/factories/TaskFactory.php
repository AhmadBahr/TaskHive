<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Column;
use App\Models\User;
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
            'title' => fake()->sentence(3),
            'description' => fake()->optional(0.7)->paragraph(),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'position' => 0,
            'due_date' => fake()->optional(0.3)->dateTimeBetween('now', '+1 month'),
            'board_id' => Board::factory(),
            'column_id' => Column::factory(),
            'assignee_id' => fake()->optional(0.4)->randomElement(User::pluck('id')),
        ];
    }
}
