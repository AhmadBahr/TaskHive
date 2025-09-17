<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $task->title }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    in {{ $board->name }} → {{ $task->column->name }}
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('boards.show', $board) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Board
                </a>
                <a href="{{ route('tasks.edit', ['board' => $board, 'task' => $task]) }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Task Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Task Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Task Details</h3>
                                <span class="priority-badge priority-{{ $task->priority }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                            
                            @if($task->description)
                                <div class="prose max-w-none">
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No description provided.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Task Activity -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity</h3>
                            <div class="space-y-4">
                                @forelse($task->activities as $activity)
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">{{ $activity->note }}</p>
                                            <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 italic">No activity yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Sidebar -->
                <div class="space-y-6">
                    <!-- Task Properties -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Properties</h3>
                            <div class="space-y-4">
                                <!-- Assignee -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Assignee</label>
                                    @if($task->assignee)
                                        <div class="mt-1 flex items-center">
                                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-primary-600">
                                                    {{ substr($task->assignee->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="ml-2 text-sm text-gray-900">{{ $task->assignee->name }}</span>
                                        </div>
                                    @else
                                        <p class="mt-1 text-sm text-gray-500">Unassigned</p>
                                    @endif
                                </div>

                                <!-- Due Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                                    @if($task->due_date)
                                        <div class="mt-1 flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-900">{{ $task->due_date->format('M j, Y') }}</span>
                                            @if($task->due_date->isPast())
                                                <span class="ml-2 text-xs text-red-600 font-medium">Overdue</span>
                                            @endif
                                        </div>
                                    @else
                                        <p class="mt-1 text-sm text-gray-500">No due date set</p>
                                    @endif
                                </div>

                                <!-- Column -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Column</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $task->column->name }}</p>
                                </div>

                                <!-- Position -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Position</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $task->position }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Task Metadata -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Created:</span>
                                    <span class="text-gray-900">{{ $task->created_at->format('M j, Y g:i A') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Updated:</span>
                                    <span class="text-gray-900">{{ $task->updated_at->format('M j, Y g:i A') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Task ID:</span>
                                    <span class="text-gray-900 font-mono text-xs">{{ $task->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
