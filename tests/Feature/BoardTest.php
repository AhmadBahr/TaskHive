<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_board()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/boards', [
            'name' => 'Test Board',
            'slug' => 'test-board',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('boards', [
            'name' => 'Test Board',
            'slug' => 'test-board',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_view_their_boards()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/boards');

        $response->assertStatus(200);
        $response->assertSee($board->name);
    }

    public function test_user_cannot_view_other_users_boards()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $board = Board::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get("/boards/{$board->slug}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_their_board()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->patch("/boards/{$board->slug}", [
            'name' => 'Updated Board Name',
            'slug' => 'updated-board',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('boards', [
            'id' => $board->id,
            'name' => 'Updated Board Name',
            'slug' => 'updated-board',
        ]);
    }

    public function test_user_can_delete_their_board()
    {
        $user = User::factory()->create();
        $board = Board::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/boards/{$board->slug}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('boards', ['id' => $board->id]);
    }
}
