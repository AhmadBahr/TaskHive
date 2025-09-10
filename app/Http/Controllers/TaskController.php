<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board): RedirectResponse
    {
        $this->authorize('view', $board);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'column_id' => ['required', 'exists:columns,id'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date', 'after:now'],
        ]);

        $column = Column::findOrFail($validated['column_id']);

        // Ensure the column belongs to the board
        if ($column->board_id !== $board->id) {
            abort(403, 'Column does not belong to this board.');
        }

        // Check WIP limit enforcement
        if ($column->wip_limit) {
            $currentTaskCount = $column->tasks()->count();
            if ($currentTaskCount >= $column->wip_limit) {
                return back()->withErrors([
                    'column_id' => "Cannot create task in '{$column->name}' - WIP limit of {$column->wip_limit} reached.",
                ]);
            }
        }

        $validated['board_id'] = $board->id;
        $validated['position'] = $column->tasks()->max('position') + 1;

        $task = Task::create($validated);

        return redirect()->route('boards.show', $board)
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board, Task $task): View
    {
        $this->authorize('view', $task);

        $task->load(['activities.fromColumn', 'activities.toColumn']);

        return view('tasks.show', compact('board', 'task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board, Task $task): View
    {
        $this->authorize('update', $task);

        return view('tasks.edit', compact('board', 'task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date', 'after:now'],
        ]);

        $task->update($validated);

        return redirect()->route('boards.show', $board)
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board, Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('boards.show', $board)
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Move a task to a different column.
     */
    public function move(Request $request, Board $board, Task $task): JsonResponse
    {
        $this->authorize('move', $task);

        $validated = $request->validate([
            'column_id' => ['required', 'exists:columns,id'],
            'position' => ['nullable', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $newColumn = Column::findOrFail($validated['column_id']);

        // Ensure the column belongs to the board
        if ($newColumn->board_id !== $board->id) {
            return response()->json(['error' => 'Column does not belong to this board.'], 403);
        }

        // Update position if provided
        if (isset($validated['position'])) {
            $task->update(['position' => $validated['position']]);
        }

        // Move to new column
        $task->moveToColumn($newColumn, $validated['note'] ?? null);

        return response()->json([
            'success' => true,
            'message' => 'Task moved successfully.',
            'task' => $task->fresh(['column', 'assignee']),
        ]);
    }

    /**
     * Update the position of tasks within a column.
     */
    public function updatePositions(Request $request, Board $board, Column $column): JsonResponse
    {
        $this->authorize('view', $board);

        $validated = $request->validate([
            'tasks' => ['required', 'array'],
            'tasks.*.id' => ['required', 'exists:tasks,id'],
            'tasks.*.position' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated['tasks'] as $taskData) {
            Task::where('id', $taskData['id'])
                ->where('column_id', $column->id)
                ->where('board_id', $board->id)
                ->update(['position' => $taskData['position']]);
        }

        return response()->json(['success' => true]);
    }
}
