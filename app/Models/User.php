<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the boards owned by the user.
     */
    public function boards()
    {
        return $this->hasMany(\App\Models\Board::class);
    }

    /**
     * Get the tasks assigned to the user.
     */
    public function assignedTasks()
    {
        return $this->hasMany(\App\Models\Task::class, 'assignee_id');
    }

    /**
     * Get all tasks related to the user (from their boards).
     */
    public function tasks()
    {
        return $this->hasManyThrough(\App\Models\Task::class, \App\Models\Board::class);
    }

    /**
     * Get completed tasks count.
     */
    public function getCompletedTasksCount(): int
    {
        return \App\Models\Task::whereHas('board', function ($query) {
            $query->where('user_id', $this->id);
        })->whereHas('column', function ($query) {
            $query->where('name', 'Done');
        })->count();
    }

    /**
     * Get in progress tasks count.
     */
    public function getInProgressTasksCount(): int
    {
        return \App\Models\Task::whereHas('board', function ($query) {
            $query->where('user_id', $this->id);
        })->whereHas('column', function ($query) {
            $query->where('name', 'In Progress');
        })->count();
    }
}
