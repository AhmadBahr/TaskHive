<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Boards indexes
        if (! Schema::hasIndex('boards', 'boards_user_id_created_at_index')) {
            Schema::table('boards', function (Blueprint $table) {
                $table->index(['user_id', 'created_at']);
            });
        }

        if (! Schema::hasIndex('boards', 'boards_slug_index')) {
            Schema::table('boards', function (Blueprint $table) {
                $table->index('slug');
            });
        }

        // Columns indexes
        if (! Schema::hasIndex('columns', 'columns_board_id_position_index')) {
            Schema::table('columns', function (Blueprint $table) {
                $table->index(['board_id', 'position']);
            });
        }

        // Tasks indexes
        if (! Schema::hasIndex('tasks', 'tasks_board_id_column_id_position_index')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index(['board_id', 'column_id', 'position']);
            });
        }

        if (! Schema::hasIndex('tasks', 'tasks_assignee_id_created_at_index')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index(['assignee_id', 'created_at']);
            });
        }

        if (! Schema::hasIndex('tasks', 'tasks_due_date_created_at_index')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index(['due_date', 'created_at']);
            });
        }

        if (! Schema::hasIndex('tasks', 'tasks_priority_created_at_index')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index(['priority', 'created_at']);
            });
        }

        if (! Schema::hasIndex('tasks', 'tasks_column_id_position_index')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index(['column_id', 'position']);
            });
        }

        // Task activities indexes
        if (! Schema::hasIndex('task_activities', 'task_activities_task_id_created_at_index')) {
            Schema::table('task_activities', function (Blueprint $table) {
                $table->index(['task_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['slug']);
        });

        Schema::table('columns', function (Blueprint $table) {
            $table->dropIndex(['board_id', 'position']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['board_id', 'column_id', 'position']);
            $table->dropIndex(['assignee_id', 'created_at']);
            $table->dropIndex(['due_date', 'created_at']);
            $table->dropIndex(['priority', 'created_at']);
            $table->dropIndex(['column_id', 'position']);
        });

        Schema::table('task_activities', function (Blueprint $table) {
            $table->dropIndex(['task_id', 'created_at']);
        });
    }
};
