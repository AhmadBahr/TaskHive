<?php

namespace Database\Factories;

use App\Models\Column;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskActivity>
 */
class TaskActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'note' => fake()->optional(0.5)->sentence(),
            'task_id' => Task::factory(),
            'from_column_id' => Column::factory(),
            'to_column_id' => Column::factory(),
        ];
    }
}
