<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    // Board API routes
    Route::apiResource('boards', BoardController::class)->names([
        'index' => 'api.boards.index',
        'store' => 'api.boards.store',
        'show' => 'api.boards.show',
        'update' => 'api.boards.update',
        'destroy' => 'api.boards.destroy',
    ]);

    // Column API routes (nested under boards)
    Route::post('/boards/{board}/columns', [ColumnController::class, 'store'])->name('api.columns.store');
    Route::patch('/boards/{board}/columns/{column}', [ColumnController::class, 'update'])->name('api.columns.update');
    Route::delete('/boards/{board}/columns/{column}', [ColumnController::class, 'destroy'])->name('api.columns.destroy');
    Route::patch('/boards/{board}/columns/positions', [ColumnController::class, 'updatePositions'])->name('api.columns.positions');

    // Task API routes (nested under boards)
    Route::post('/boards/{board}/tasks', [TaskController::class, 'store'])->name('api.tasks.store');
    Route::get('/boards/{board}/tasks/{task}', [TaskController::class, 'show'])->name('api.tasks.show');
    Route::patch('/boards/{board}/tasks/{task}', [TaskController::class, 'update'])->name('api.tasks.update');
    Route::delete('/boards/{board}/tasks/{task}', [TaskController::class, 'destroy'])->name('api.tasks.destroy');
    Route::patch('/boards/{board}/tasks/{task}/move', [TaskController::class, 'move'])->name('api.tasks.move');
    Route::patch('/boards/{board}/columns/{column}/tasks/positions', [TaskController::class, 'updatePositions'])->name('api.tasks.positions');
});
