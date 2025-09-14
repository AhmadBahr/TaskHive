<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'position',
        'due_date',
        'board_id',
        'column_id',
        'assignee_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'position' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($task) {
            $original = $task->getOriginal();
            $changes = $task->getDirty();

            // Log assignee changes
            if (isset($changes['assignee_id']) && $changes['assignee_id'] !== $original['assignee_id']) {
                $oldAssignee = $original['assignee_id'] ? User::find($original['assignee_id'])->name : 'Unassigned';
                $newAssignee = $changes['assignee_id'] ? User::find($changes['assignee_id'])->name : 'Unassigned';

                TaskActivity::create([
                    'task_id' => $task->id,
                    'note' => "Assignee changed from {$oldAssignee} to {$newAssignee}",
                ]);
            }

            // Log priority changes
            if (isset($changes['priority']) && $changes['priority'] !== $original['priority']) {
                TaskActivity::create([
                    'task_id' => $task->id,
                    'note' => 'Priority changed from '.ucfirst($original['priority']).' to '.ucfirst($changes['priority']),
                ]);
            }

            // Log due date changes
            if (isset($changes['due_date']) && $changes['due_date'] !== $original['due_date']) {
                $oldDate = $original['due_date'] ? \Carbon\Carbon::parse($original['due_date'])->format('M j, Y') : 'No due date';
                $newDate = $changes['due_date'] ? \Carbon\Carbon::parse($changes['due_date'])->format('M j, Y') : 'No due date';

                TaskActivity::create([
                    'task_id' => $task->id,
                    'note' => "Due date changed from {$oldDate} to {$newDate}",
                ]);
            }

            // Log title changes
            if (isset($changes['title']) && $changes['title'] !== $original['title']) {
                TaskActivity::create([
                    'task_id' => $task->id,
                    'note' => "Title changed from '{$original['title']}' to '{$changes['title']}'",
                ]);
            }
        });

        static::created(function ($task) {
            TaskActivity::create([
                'task_id' => $task->id,
                'note' => "Task created: '{$task->title}'",
            ]);
        });

        static::deleting(function ($task) {
            TaskActivity::create([
                'task_id' => $task->id,
                'note' => "Task deleted: '{$task->title}'",
            ]);
        });
    }

    /**
     * Get the board that owns the task.
     */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * Get the column that owns the task.
     */
    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    /**
     * Get the user assigned to the task.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * Get the activities for the task.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(TaskActivity::class)->orderBy('created_at', 'desc');
    }

    /**
     * Move the task to a different column.
     */
    public function moveToColumn(Column $newColumn, ?string $note = null): void
    {
        $oldColumn = $this->column;

        $this->update([
            'column_id' => $newColumn->id,
            'position' => $newColumn->tasks()->max('position') + 1,
        ]);

        // Log the activity
        $this->activities()->create([
            'from_column_id' => $oldColumn->id,
            'to_column_id' => $newColumn->id,
            'note' => $note,
        ]);
    }
}
