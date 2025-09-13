<?php

namespace App\Livewire;

use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BoardKanban extends Component
{
    public Board $board;

    public $columns = [];

    public $tasks = [];

    public $users = [];

    protected $listeners = [
        'taskMoved' => 'refreshData',
        'taskCreated' => 'refreshData',
        'taskUpdated' => 'refreshData',
        'taskDeleted' => 'refreshData',
    ];

    public function mount(Board $board)
    {
        $this->board = $board;
        $this->loadData();
    }

    public function loadData()
    {
        $this->columns = $this->board->columns()->orderBy('position')->get();
        $tasks = $this->board->tasks()->with(['assignee', 'column'])->get();
        $this->tasks = $tasks->groupBy('column_id');
        $this->users = User::all()->toArray();
    }

    public function moveTask($taskId, $newColumnId, $newPosition = null)
    {
        $task = Task::findOrFail($taskId);
        $oldColumnId = $task->column_id;

        // Check if user can manage this task
        if ($task->board->user_id !== Auth::id()) {
            $this->addError('task', 'You do not have permission to move this task.');

            return;
        }

        $newColumn = Column::findOrFail($newColumnId);

        // Check if new column belongs to the same board
        if ($newColumn->board_id !== $this->board->id) {
            $this->addError('task', 'Cannot move task to a different board.');

            return;
        }

        // Check WIP limit enforcement
        if ($newColumn->wip_limit) {
            $currentTaskCount = ($this->tasks[$newColumnId] ?? collect())->count();

            // If moving within the same column, don't count the current task
            if ($oldColumnId === $newColumnId) {
                $currentTaskCount = max(0, $currentTaskCount - 1);
            }

            if ($currentTaskCount >= $newColumn->wip_limit) {
                $this->addError('task', "Cannot move task to '{$newColumn->name}' - WIP limit of {$newColumn->wip_limit} reached.");

                return;
            }
        }

        // Update task position and column
        $task->update([
            'column_id' => $newColumnId,
            'position' => $newPosition ?? $this->getNextPosition($newColumnId),
        ]);

        // Log activity
        $oldColumn = $this->columns->firstWhere('id', $oldColumnId);
        TaskActivity::create([
            'task_id' => $task->id,
            'from_column_id' => $oldColumnId,
            'to_column_id' => $newColumnId,
            'note' => "Task moved from {$oldColumn->name} to {$newColumn->name}",
        ]);

        $this->refreshData();
        $this->dispatch('taskMoved');
    }

    public function refreshData()
    {
        $this->loadData();
    }

    private function getNextPosition($columnId)
    {
        $maxPosition = Task::where('column_id', $columnId)->max('position') ?? 0;

        return $maxPosition + 1;
    }

    public function getTasksForColumn($columnId)
    {
        $tasks = $this->tasks[$columnId] ?? collect();

        return $tasks->sortBy('position');
    }

    public function getWipStatus($column)
    {
        if (! $column->wip_limit) {
            return null;
        }

        $taskCount = ($this->tasks[$column->id] ?? collect())->count();

        if ($taskCount >= $column->wip_limit) {
            return 'exceeded';
        } elseif ($taskCount >= $column->wip_limit * 0.8) {
            return 'warning';
        }

        return 'normal';
    }

    public function render()
    {
        return view('livewire.board-kanban');
    }
}
