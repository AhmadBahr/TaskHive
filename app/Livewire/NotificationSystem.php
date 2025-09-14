<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskActivity;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationSystem extends Component
{
    public $notifications = [];

    public $unreadCount = 0;

    protected $listeners = [
        'taskCreated' => 'checkForNotifications',
        'taskUpdated' => 'checkForNotifications',
        'taskDeleted' => 'checkForNotifications',
        'taskMoved' => 'checkForNotifications',
    ];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        // Get recent activities for tasks assigned to current user
        $userTasks = Task::where('assignee_id', Auth::id())->pluck('id');

        $this->notifications = TaskActivity::whereIn('task_id', $userTasks)
            ->where('created_at', '>=', now()->subDays(7))
            ->with(['task'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'message' => $activity->note,
                    'task_title' => $activity->task->title ?? 'Unknown Task',
                    'created_at' => $activity->created_at,
                    'type' => $this->getNotificationType($activity->note),
                ];
            })
            ->toArray();

        $this->unreadCount = count($this->notifications);
    }

    private function getNotificationType($note)
    {
        if (str_contains($note, 'assigned') || str_contains($note, 'Assignee')) {
            return 'assignment';
        } elseif (str_contains($note, 'moved') || str_contains($note, 'move')) {
            return 'movement';
        } elseif (str_contains($note, 'priority')) {
            return 'priority';
        } elseif (str_contains($note, 'due date')) {
            return 'due_date';
        } else {
            return 'general';
        }
    }

    public function checkForNotifications()
    {
        $this->loadNotifications();
    }

    public function markAsRead()
    {
        $this->unreadCount = 0;
    }

    public function clearNotifications()
    {
        $this->notifications = [];
        $this->unreadCount = 0;
    }

    public function render()
    {
        return view('livewire.notification-system');
    }
}
