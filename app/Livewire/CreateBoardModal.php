<?php

namespace App\Livewire;

use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateBoardModal extends Component
{
    public $showModal = false;

    public $name = '';

    public $slug = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:boards,slug',
    ];

    protected $listeners = [
        'openCreateBoardModal' => 'openModal',
    ];

    public function openModal()
    {
        $this->showModal = true;
        $this->reset(['name', 'slug']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'slug']);
        $this->resetErrorBag();
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function createBoard()
    {
        $this->validate();

        $board = Board::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'user_id' => Auth::id(),
        ]);

        // Create default columns
        $defaultColumns = [
            ['name' => 'Backlog', 'position' => 1, 'wip_limit' => null],
            ['name' => 'In Progress', 'position' => 2, 'wip_limit' => 3],
            ['name' => 'Review', 'position' => 3, 'wip_limit' => 2],
            ['name' => 'Done', 'position' => 4, 'wip_limit' => null],
        ];

        foreach ($defaultColumns as $columnData) {
            $board->columns()->create($columnData);
        }

        $this->closeModal();
        $this->dispatch('boardCreated');

        // Redirect to the new board
        return redirect()->route('boards.show', $board->slug);
    }

    public function render()
    {
        return view('livewire.create-board-modal');
    }
}
