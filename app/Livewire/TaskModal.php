<?php

namespace App\Livewire;

use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskModal extends Component
{
    public $showModal = false;

    public $task = null;

    public $board = null;

    public $column = null;

    // Form fields
    public $title = '';

    public $description = '';

    public $priority = 'medium';

    public $assignee_id = '';

    public $due_date = '';

    public $column_id = '';

    public $users = [];

    public $columns = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'required|in:low,medium,high,urgent',
        'assignee_id' => 'nullable|exists:users,id',
        'due_date' => 'nullable|date|after:today',
        'column_id' => 'required|exists:columns,id',
    ];

    protected $listeners = [
        'openTaskModal' => 'openModal',
        'openCreateTaskModal' => 'openCreateModal',
    ];

    public function mount()
    {
        $this->users = User::all();
    }

    public function openModal($taskId)
    {
        $this->task = Task::with(['assignee', 'column'])->findOrFail($taskId);
        $this->board = $this->task->board;
        $this->loadFormData();
        $this->showModal = true;
    }

    public function openCreateModal()
    {
        $this->task = null;
        $this->board = Board::where('user_id', Auth::id())->first();
        if (! $this->board) {
            $this->addError('board', 'No board found. Please create a board first.');

            return;
        }
        $this->loadFormData();
        $this->showModal = true;
    }

    public function loadFormData()
    {
        if ($this->task) {
            $this->title = $this->task->title;
            $this->description = $this->task->description;
            $this->priority = $this->task->priority;
            $this->assignee_id = $this->task->assignee_id;
            $this->due_date = $this->task->due_date ? $this->task->due_date->format('Y-m-d') : '';
            $this->column_id = $this->task->column_id;
        } else {
            $this->title = '';
            $this->description = '';
            $this->priority = 'medium';
            $this->assignee_id = '';
            $this->due_date = '';
            $this->column_id = $this->board->columns()->first()?->id ?? '';
        }

        $this->columns = $this->board->columns()->orderBy('position')->get();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['task', 'board', 'column', 'title', 'description', 'priority', 'assignee_id', 'due_date', 'column_id']);
        $this->resetErrorBag();
    }

    public function saveTask()
    {
        $this->validate();

        // Check WIP limit for new tasks or when changing columns
        if (! $this->task || $this->task->column_id !== $this->column_id) {
            $column = Column::findOrFail($this->column_id);
            if ($column->wip_limit) {
                $currentTaskCount = $column->tasks()->count();

                // If updating existing task and moving to different column, don't count the current task
                if ($this->task && $this->task->column_id !== $this->column_id) {
                    $currentTaskCount = max(0, $currentTaskCount - 1);
                }

                if ($currentTaskCount >= $column->wip_limit) {
                    $this->addError('column_id', "Cannot add task to '{$column->name}' - WIP limit of {$column->wip_limit} reached.");

                    return;
                }
            }
        }

        if ($this->task) {
            // Update existing task
            $this->task->update([
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->priority,
                'assignee_id' => $this->assignee_id ?: null,
                'due_date' => $this->due_date ?: null,
                'column_id' => $this->column_id,
            ]);

            $this->dispatch('taskUpdated');
        } else {
            // Create new task
            $task = Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->priority,
                'assignee_id' => $this->assignee_id ?: null,
                'due_date' => $this->due_date ?: null,
                'column_id' => $this->column_id,
                'board_id' => $this->board->id,
                'position' => $this->getNextPosition($this->column_id),
            ]);

            $this->dispatch('taskCreated');
        }

        $this->closeModal();
    }

    public function deleteTask()
    {
        if ($this->task) {
            $this->task->delete();
            $this->dispatch('taskDeleted');
            $this->closeModal();
        }
    }

    private function getNextPosition($columnId)
    {
        $maxPosition = Task::where('column_id', $columnId)->max('position') ?? 0;

        return $maxPosition + 1;
    }

    public function render()
    {
        return view('livewire.task-modal');
    }
}
