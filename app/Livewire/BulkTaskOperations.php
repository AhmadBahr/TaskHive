<?php

namespace App\Livewire;

use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Livewire\Component;

class BulkTaskOperations extends Component
{
    public Board $board;

    public $selectedTasks = [];

    public $selectAll = false;

    public $bulkAction = '';

    public $bulkAssignee = '';

    public $bulkColumn = '';

    public $bulkPriority = '';

    public $users = [];

    public $columns = [];

    protected $listeners = [
        'taskCreated' => 'refreshSelection',
        'taskUpdated' => 'refreshSelection',
        'taskDeleted' => 'refreshSelection',
        'toggleTask' => 'toggleTask',
    ];

    public function mount(Board $board)
    {
        $this->board = $board;
        $this->loadData();
    }

    public function loadData()
    {
        $this->users = User::all()->toArray();
        $this->columns = $this->board->columns()->orderBy('position')->get()->toArray();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedTasks = $this->board->tasks()->pluck('id')->toArray();
        } else {
            $this->selectedTasks = [];
        }
    }

    public function updatedSelectedTasks()
    {
        $totalTasks = $this->board->tasks()->count();
        $this->selectAll = count($this->selectedTasks) === $totalTasks;
    }

    public function toggleTask($taskId)
    {
        if (in_array($taskId, $this->selectedTasks)) {
            $this->selectedTasks = array_diff($this->selectedTasks, [$taskId]);
        } else {
            $this->selectedTasks[] = $taskId;
        }

        $this->updatedSelectedTasks();
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedTasks)) {
            $this->addError('bulkAction', 'Please select at least one task.');

            return;
        }

        $tasks = Task::whereIn('id', $this->selectedTasks)->get();

        switch ($this->bulkAction) {
            case 'assign':
                $this->bulkAssign($tasks);
                break;
            case 'move':
                $this->bulkMove($tasks);
                break;
            case 'priority':
                $this->bulkUpdatePriority($tasks);
                break;
            case 'delete':
                $this->bulkDelete($tasks);
                break;
            default:
                $this->addError('bulkAction', 'Please select a valid action.');

                return;
        }

        $this->resetBulkOperation();
        $this->dispatch('tasksUpdated');
    }

    private function bulkAssign($tasks)
    {
        if (! $this->bulkAssignee) {
            $this->addError('bulkAssignee', 'Please select an assignee.');

            return;
        }

        $assignee = User::find($this->bulkAssignee);
        if (! $assignee) {
            $this->addError('bulkAssignee', 'Invalid assignee selected.');

            return;
        }

        foreach ($tasks as $task) {
            $oldAssignee = $task->assignee ? $task->assignee->name : 'Unassigned';
            $task->update(['assignee_id' => $this->bulkAssignee]);

            TaskActivity::create([
                'task_id' => $task->id,
                'note' => "Bulk assignment: Changed from {$oldAssignee} to {$assignee->name}",
            ]);
        }

        session()->flash('success', "Assigned {$tasks->count()} task(s) to {$assignee->name}.");
    }

    private function bulkMove($tasks)
    {
        if (! $this->bulkColumn) {
            $this->addError('bulkColumn', 'Please select a column.');

            return;
        }

        $column = Column::find($this->bulkColumn);
        if (! $column || $column->board_id !== $this->board->id) {
            $this->addError('bulkColumn', 'Invalid column selected.');

            return;
        }

        // Check WIP limit
        if ($column->wip_limit) {
            $currentCount = $column->tasks()->count();
            $newCount = $currentCount + $tasks->count();

            if ($newCount > $column->wip_limit) {
                $this->addError('bulkColumn', "Cannot move {$tasks->count()} task(s) to '{$column->name}' - would exceed WIP limit of {$column->wip_limit}.");

                return;
            }
        }

        foreach ($tasks as $task) {
            $oldColumn = $task->column;
            $task->update([
                'column_id' => $this->bulkColumn,
                'position' => $this->getNextPosition($this->bulkColumn),
            ]);

            TaskActivity::create([
                'task_id' => $task->id,
                'from_column_id' => $oldColumn->id,
                'to_column_id' => $this->bulkColumn,
                'note' => "Bulk move: Moved from {$oldColumn->name} to {$column->name}",
            ]);
        }

        session()->flash('success', "Moved {$tasks->count()} task(s) to {$column->name}.");
    }

    private function bulkUpdatePriority($tasks)
    {
        if (! $this->bulkPriority) {
            $this->addError('bulkPriority', 'Please select a priority.');

            return;
        }

        foreach ($tasks as $task) {
            $oldPriority = ucfirst($task->priority);
            $task->update(['priority' => $this->bulkPriority]);

            TaskActivity::create([
                'task_id' => $task->id,
                'note' => "Bulk priority update: Changed from {$oldPriority} to ".ucfirst($this->bulkPriority),
            ]);
        }

        session()->flash('success', "Updated priority for {$tasks->count()} task(s) to ".ucfirst($this->bulkPriority).'.');
    }

    private function bulkDelete($tasks)
    {
        foreach ($tasks as $task) {
            TaskActivity::create([
                'task_id' => $task->id,
                'note' => "Bulk delete: Task '{$task->title}' deleted",
            ]);
            $task->delete();
        }

        session()->flash('success', "Deleted {$tasks->count()} task(s).");
    }

    private function getNextPosition($columnId)
    {
        $maxPosition = Task::where('column_id', $columnId)->max('position') ?? 0;

        return $maxPosition + 1;
    }

    public function resetBulkOperation()
    {
        $this->selectedTasks = [];
        $this->selectAll = false;
        $this->bulkAction = '';
        $this->bulkAssignee = '';
        $this->bulkColumn = '';
        $this->bulkPriority = '';
        $this->resetErrorBag();
    }

    public function refreshSelection()
    {
        $this->selectedTasks = [];
        $this->selectAll = false;
    }

    public function render()
    {
        return view('livewire.bulk-task-operations');
    }
}
