<?php

namespace App\Livewire;

use App\Models\Board;
use App\Models\User;
use Livewire\Component;

class TaskSearch extends Component
{
    public $board;

    public $search = '';

    public $priority = '';

    public $assignee = '';

    public $column = '';

    public $overdue = false;

    public $users = [];

    public $columns = [];

    public $filteredTasks;

    protected $listeners = [
        'taskCreated' => 'refreshSearch',
        'taskUpdated' => 'refreshSearch',
        'taskDeleted' => 'refreshSearch',
    ];

    public function mount(Board $board)
    {
        $this->board = $board;
        $this->loadData();
        $this->performSearch();
    }

    public function loadData()
    {
        $this->users = User::all()->toArray();
        $this->columns = $this->board->columns()->orderBy('position')->get()->toArray();
    }

    public function updatedSearch()
    {
        $this->performSearch();
    }

    public function updatedPriority()
    {
        $this->performSearch();
    }

    public function updatedAssignee()
    {
        $this->performSearch();
    }

    public function updatedColumn()
    {
        $this->performSearch();
    }

    public function updatedOverdue()
    {
        $this->performSearch();
    }

    public function performSearch()
    {
        $query = $this->board->tasks()->with(['assignee', 'column']);

        // Search by title and description
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        // Filter by priority
        if ($this->priority) {
            $query->where('priority', $this->priority);
        }

        // Filter by assignee
        if ($this->assignee) {
            $query->where('assignee_id', $this->assignee);
        }

        // Filter by column
        if ($this->column) {
            $query->where('column_id', $this->column);
        }

        // Filter by overdue tasks
        if ($this->overdue) {
            $query->where('due_date', '<', now());
        }

        $this->filteredTasks = $query->orderBy('created_at', 'desc')->get();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->priority = '';
        $this->assignee = '';
        $this->column = '';
        $this->overdue = false;
        $this->performSearch();
    }

    public function refreshSearch()
    {
        $this->loadData();
        $this->performSearch();
    }

    public function render()
    {
        return view('livewire.task-search');
    }
}
