<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('boards.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('boards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:boards,slug'],
        ]);

        $board = auth()->user()->boards()->create($validated);

        return redirect()->route('boards.show', $board)
            ->with('success', 'Board created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board): View
    {
        $this->authorize('view', $board);

        return view('boards.show', compact('board'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board): View
    {
        $this->authorize('update', $board);

        return view('boards.edit', compact('board'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board): RedirectResponse
    {
        $this->authorize('update', $board);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('boards', 'slug')->ignore($board->id)],
        ]);

        $board->update($validated);

        return redirect()->route('boards.show', $board)
            ->with('success', 'Board updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board): RedirectResponse
    {
        $this->authorize('delete', $board);

        $boardName = $board->name;
        $taskCount = $board->tasks()->count();
        $columnCount = $board->columns()->count();

        $board->delete();

        $message = "Board '{$boardName}' deleted successfully.";
        if ($taskCount > 0 || $columnCount > 0) {
            $message .= " Also deleted {$taskCount} task(s) and {$columnCount} column(s).";
        }

        return redirect()->route('boards.index')
            ->with('success', $message);
    }
}
