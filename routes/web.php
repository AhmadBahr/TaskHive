<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'message' => 'Task Hive Backend is Running Successfully! 🎉',
        'status' => 'success',
        'database' => 'PostgreSQL Connected',
        'features' => [
            'Laravel 11 Framework',
            'PostgreSQL Database with UUIDs',
            'Laravel Breeze Authentication',
            'RESTful API Endpoints',
            'Eloquent Models with Relationships',
            'Authorization Policies',
            'Database Migrations & Seeders',
            'Livewire Components',
            'Alpine.js Integration',
            'Tailwind CSS Styling',
        ],
        'endpoints' => [
            'GET /api/boards' => 'List all boards',
            'POST /api/boards' => 'Create new board',
            'GET /api/boards/{id}' => 'Get specific board',
            'PUT /api/boards/{id}' => 'Update board',
            'DELETE /api/boards/{id}' => 'Delete board',
            'POST /api/boards/{id}/columns' => 'Create column',
            'POST /api/boards/{id}/tasks' => 'Create task',
            'PATCH /api/boards/{id}/tasks/{task}/move' => 'Move task between columns',
        ],
    ]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Board routes
    Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');
    Route::get('/boards/create', [BoardController::class, 'create'])->name('boards.create');
    Route::post('/boards', [BoardController::class, 'store'])->name('boards.store');
    Route::get('/boards/{board:slug}', [BoardController::class, 'show'])->name('boards.show');
    Route::get('/boards/{board:slug}/edit', [BoardController::class, 'edit'])->name('boards.edit');
    Route::patch('/boards/{board}', [BoardController::class, 'update'])->name('boards.update');
    Route::delete('/boards/{board}', [BoardController::class, 'destroy'])->name('boards.destroy');

    // Column routes (nested under boards)
    Route::post('/boards/{board:slug}/columns', [ColumnController::class, 'store'])->name('columns.store');
    Route::patch('/boards/{board:slug}/columns/{column}', [ColumnController::class, 'update'])->name('columns.update');
    Route::delete('/boards/{board:slug}/columns/{column}', [ColumnController::class, 'destroy'])->name('columns.destroy');
    Route::patch('/boards/{board:slug}/columns/positions', [ColumnController::class, 'updatePositions'])->name('columns.positions');

    // Task routes (nested under boards)
    Route::post('/boards/{board:slug}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/boards/{board:slug}/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::patch('/boards/{board:slug}/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/boards/{board:slug}/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/boards/{board:slug}/tasks/{task}/move', [TaskController::class, 'move'])->name('tasks.move');
    Route::patch('/boards/{board:slug}/columns/{column}/tasks/positions', [TaskController::class, 'updatePositions'])->name('tasks.positions');
});

require __DIR__.'/auth.php';
