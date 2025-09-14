<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Column extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'wip_limit',
        'position',
        'board_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'wip_limit' => 'integer',
        'position' => 'integer',
    ];

    /**
     * Get the board that owns the column.
     */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * Get the tasks for the column.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('position');
    }

    /**
     * Get the task activities where this column is the source.
     */
    public function fromActivities(): HasMany
    {
        return $this->hasMany(TaskActivity::class, 'from_column_id');
    }

    /**
     * Get the task activities where this column is the destination.
     */
    public function toActivities(): HasMany
    {
        return $this->hasMany(TaskActivity::class, 'to_column_id');
    }
}
