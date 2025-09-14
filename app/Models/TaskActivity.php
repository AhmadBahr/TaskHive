<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskActivity extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'note',
        'task_id',
        'from_column_id',
        'to_column_id',
    ];

    /**
     * Get the task that owns the activity.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the column that the task moved from.
     */
    public function fromColumn(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'from_column_id');
    }

    /**
     * Get the column that the task moved to.
     */
    public function toColumn(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'to_column_id');
    }
}
