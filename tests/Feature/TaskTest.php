<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create(['user_id' => $user->id]);
        $column = Column::factory()->create(['board_id' => $board->id]);

        $response = $this->actingAs($user)->post("/boards/{$board->slug}/tasks", [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'priority' => 'medium',
            'column_id' => $column->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'priority' => 'medium',
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);
    }

    public function test_user_can_update_task()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create(['user_id' => $user->id]);
        $column = Column::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        $response = $this->actingAs($user)->patch("/boards/{$board->slug}/tasks/{$task->id}", [
            'title' => 'Updated Task Title',
            'description' => 'Updated Description',
            'priority' => 'high',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'description' => 'Updated Description',
            'priority' => 'high',
        ]);
    }

    public function test_user_can_move_task_between_columns()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create(['user_id' => $user->id]);
        $fromColumn = Column::factory()->create(['board_id' => $board->id]);
        $toColumn = Column::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $fromColumn->id,
        ]);

        $response = $this->actingAs($user)->patch("/boards/{$board->slug}/tasks/{$task->id}/move", [
            'column_id' => $toColumn->id,
            'note' => 'Moving task for testing',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'column_id' => $toColumn->id,
        ]);
    }

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create(['user_id' => $user->id]);
        $column = Column::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        $response = $this->actingAs($user)->delete("/boards/{$board->slug}/tasks/{$task->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_wip_limit_enforcement()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create(['user_id' => $user->id]);
        $column = Column::factory()->create([
            'board_id' => $board->id,
            'wip_limit' => 2,
        ]);

        // Create 2 tasks (at limit)
        Task::factory()->count(2)->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        // Try to create a third task
        $response = $this->actingAs($user)->post("/boards/{$board->slug}/tasks", [
            'title' => 'Third Task',
            'description' => 'Should fail',
            'priority' => 'medium',
            'column_id' => $column->id,
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('tasks', [
            'title' => 'Third Task',
        ]);
    }
}
