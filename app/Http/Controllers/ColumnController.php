<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board): RedirectResponse
    {
        $this->authorize('view', $board);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'wip_limit' => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['position'] = $board->columns()->max('position') + 1;
        $validated['board_id'] = $board->id;

        $column = Column::create($validated);

        return redirect()->route('boards.show', $board)
            ->with('success', 'Column created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board, Column $column): RedirectResponse
    {
        $this->authorize('update', $column);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'wip_limit' => ['nullable', 'integer', 'min:1'],
        ]);

        $column->update($validated);

        return redirect()->route('boards.show', $board)
            ->with('success', 'Column updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board, Column $column): RedirectResponse
    {
        $this->authorize('delete', $column);

        // Move all tasks to the first column or delete them
        $firstColumn = $board->columns()->where('id', '!=', $column->id)->orderBy('position')->first();

        if ($firstColumn) {
            $column->tasks()->update(['column_id' => $firstColumn->id]);
        } else {
            $column->tasks()->delete();
        }

        $column->delete();

        return redirect()->route('boards.show', $board)
            ->with('success', 'Column deleted successfully.');
    }

    /**
     * Update the position of columns.
     */
    public function updatePositions(Request $request, Board $board): RedirectResponse
    {
        $this->authorize('view', $board);

        $validated = $request->validate([
            'columns' => ['required', 'array'],
            'columns.*.id' => ['required', 'exists:columns,id'],
            'columns.*.position' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated['columns'] as $columnData) {
            Column::where('id', $columnData['id'])
                ->where('board_id', $board->id)
                ->update(['position' => $columnData['position']]);
        }

        return response()->json(['success' => true]);
    }
}
