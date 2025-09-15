<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo users
        $users = [
            User::factory()->create([
                'name' => 'Demo User',
                'email' => 'demo@taskhive.com',
            ]),
            User::factory()->create([
                'name' => 'John Doe',
                'email' => 'john@taskhive.com',
            ]),
            User::factory()->create([
                'name' => 'Jane Smith',
                'email' => 'jane@taskhive.com',
            ]),
        ];

        // Create multiple boards with default columns
        $boards = [
            [
                'name' => 'Task Hive Development',
                'slug' => 'task-hive-dev',
                'user_id' => $users[0]->id,
                'columns' => [
                    ['name' => 'Backlog', 'position' => 1, 'wip_limit' => null],
                    ['name' => 'In Progress', 'position' => 2, 'wip_limit' => 3],
                    ['name' => 'Review', 'position' => 3, 'wip_limit' => 2],
                    ['name' => 'Done', 'position' => 4, 'wip_limit' => null],
                ],
                'tasks' => [
                    [
                        'title' => 'Set up project structure',
                        'description' => 'Initialize the Laravel project with proper folder structure and configuration.',
                        'priority' => 'high',
                        'column_index' => 0, // Backlog
                    ],
                    [
                        'title' => 'Design database schema',
                        'description' => 'Create migrations for boards, columns, tasks, and activities.',
                        'priority' => 'high',
                        'column_index' => 0, // Backlog
                    ],
                    [
                        'title' => 'Implement authentication',
                        'description' => 'Set up Laravel Breeze with user registration and login.',
                        'priority' => 'medium',
                        'column_index' => 1, // In Progress
                    ],
                    [
                        'title' => 'Create task management API',
                        'description' => 'Build RESTful endpoints for CRUD operations on tasks.',
                        'priority' => 'medium',
                        'column_index' => 1, // In Progress
                    ],
                    [
                        'title' => 'Add drag and drop functionality',
                        'description' => 'Implement frontend drag and drop for moving tasks between columns.',
                        'priority' => 'low',
                        'column_index' => 2, // Review
                    ],
                    [
                        'title' => 'Write documentation',
                        'description' => 'Create comprehensive API documentation and user guide.',
                        'priority' => 'low',
                        'column_index' => 3, // Done
                    ],
                ],
            ],
            [
                'name' => 'Marketing Campaign',
                'slug' => 'marketing-campaign',
                'user_id' => $users[1]->id,
                'columns' => [
                    ['name' => 'Ideas', 'position' => 1, 'wip_limit' => null],
                    ['name' => 'Planning', 'position' => 2, 'wip_limit' => 5],
                    ['name' => 'Execution', 'position' => 3, 'wip_limit' => 3],
                    ['name' => 'Review', 'position' => 4, 'wip_limit' => 2],
                    ['name' => 'Published', 'position' => 5, 'wip_limit' => null],
                ],
                'tasks' => [
                    [
                        'title' => 'Social media strategy',
                        'description' => 'Develop comprehensive social media marketing strategy.',
                        'priority' => 'high',
                        'column_index' => 0, // Ideas
                    ],
                    [
                        'title' => 'Content calendar',
                        'description' => 'Create monthly content calendar for all platforms.',
                        'priority' => 'medium',
                        'column_index' => 1, // Planning
                    ],
                    [
                        'title' => 'Video production',
                        'description' => 'Produce promotional videos for the campaign.',
                        'priority' => 'high',
                        'column_index' => 2, // Execution
                    ],
                ],
            ],
            [
                'name' => 'Personal Tasks',
                'slug' => 'personal-tasks',
                'user_id' => $users[2]->id,
                'columns' => [
                    ['name' => 'To Do', 'position' => 1, 'wip_limit' => null],
                    ['name' => 'Doing', 'position' => 2, 'wip_limit' => 2],
                    ['name' => 'Done', 'position' => 3, 'wip_limit' => null],
                ],
                'tasks' => [
                    [
                        'title' => 'Grocery shopping',
                        'description' => 'Buy ingredients for weekend cooking.',
                        'priority' => 'medium',
                        'column_index' => 0, // To Do
                    ],
                    [
                        'title' => 'Read new book',
                        'description' => 'Finish reading the latest tech book.',
                        'priority' => 'low',
                        'column_index' => 1, // Doing
                    ],
                    [
                        'title' => 'Exercise routine',
                        'description' => 'Complete daily workout routine.',
                        'priority' => 'high',
                        'column_index' => 2, // Done
                    ],
                ],
            ],
        ];

        // Create boards, columns, and tasks
        foreach ($boards as $boardData) {
            $board = Board::factory()->create([
                'name' => $boardData['name'],
                'slug' => $boardData['slug'],
                'user_id' => $boardData['user_id'],
            ]);

            // Create columns for this board
            $createdColumns = [];
            foreach ($boardData['columns'] as $columnData) {
                $createdColumns[] = Column::factory()->create([
                    'name' => $columnData['name'],
                    'position' => $columnData['position'],
                    'wip_limit' => $columnData['wip_limit'],
                    'board_id' => $board->id,
                ]);
            }

            // Create tasks for this board
            foreach ($boardData['tasks'] as $index => $taskData) {
                Task::factory()->create([
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'priority' => $taskData['priority'],
                    'position' => $index + 1,
                    'board_id' => $board->id,
                    'column_id' => $createdColumns[$taskData['column_index']]->id,
                    'assignee_id' => $boardData['user_id'],
                ]);
            }
        }

        // Create a default board template that can be used for new users
        $this->createDefaultBoardTemplate();
    }

    /**
     * Create a default board template with standard columns
     */
    private function createDefaultBoardTemplate(): void
    {
        // This could be used to create a template board that new users can copy
        // For now, we'll just log that the default template is available
        \Log::info('Default board template created with standard columns: Backlog, In Progress, Review, Done');
    }
}
