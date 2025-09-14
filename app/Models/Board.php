<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Board extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'user_id',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($board) {
            if (empty($board->slug)) {
                $board->slug = Str::slug($board->name);
            }
        });

        static::updating(function ($board) {
            if ($board->isDirty('name') && empty($board->slug)) {
                $board->slug = Str::slug($board->name);
            }
        });

        static::deleting(function ($board) {
            // Delete all task activities first
            $taskIds = $board->tasks()->pluck('id');
            \App\Models\TaskActivity::whereIn('task_id', $taskIds)->delete();

            // Delete all tasks
            $board->tasks()->delete();

            // Delete all columns
            $board->columns()->delete();
        });
    }

    /**
     * Get the user that owns the board.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the columns for the board.
     */
    public function columns(): HasMany
    {
        return $this->hasMany(Column::class)->orderBy('position');
    }

    /**
     * Get the tasks for the board.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
