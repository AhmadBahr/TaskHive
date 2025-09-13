<?php

namespace App\Livewire;

use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BoardsList extends Component
{
    public $boards = [];

    protected $listeners = [
        'boardCreated' => 'refreshBoards',
        'boardDeleted' => 'refreshBoards',
    ];

    public function mount()
    {
        $this->refreshBoards();
    }

    public function refreshBoards()
    {
        $this->boards = Board::where('user_id', Auth::id())
            ->withCount(['tasks', 'columns'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function deleteBoard($boardId)
    {
        $board = Board::findOrFail($boardId);

        if ($board->user_id !== Auth::id()) {
            $this->addError('board', 'You do not have permission to delete this board.');

            return;
        }

        $board->delete();
        $this->refreshBoards();
        $this->dispatch('boardDeleted');
    }

    public function render()
    {
        return view('livewire.boards-list');
    }
}
