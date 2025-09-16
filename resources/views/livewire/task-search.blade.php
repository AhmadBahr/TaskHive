<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Search & Filter Tasks</h3>
        <button wire:click="clearFilters" 
                class="text-sm text-primary-600 hover:text-primary-700 font-medium">
            Clear All Filters
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Search Input -->
        <div class="lg:col-span-2">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                Search Tasks
            </label>
            <input type="text" 
                   id="search"
                   wire:model.live="search" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                   placeholder="Search by title or description...">
        </div>

        <!-- Priority Filter -->
        <div>
            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                Priority
            </label>
            <select id="priority"
                    wire:model.live="priority" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Priorities</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
            </select>
        </div>

        <!-- Assignee Filter -->
        <div>
            <label for="assignee" class="block text-sm font-medium text-gray-700 mb-2">
                Assignee
            </label>
            <select id="assignee"
                    wire:model.live="assignee" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Assignees</option>
                <option value="">Unassigned</option>
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                @endforeach
            </select>
        </div>

        <!-- Column Filter -->
        <div>
            <label for="column" class="block text-sm font-medium text-gray-700 mb-2">
                Column
            </label>
            <select id="column"
                    wire:model.live="column" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Columns</option>
                @foreach($columns as $column)
                    <option value="{{ $column['id'] }}">{{ $column['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Overdue Filter -->
    <div class="mt-4">
        <label class="flex items-center">
            <input type="checkbox" 
                   wire:model.live="overdue" 
                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
            <span class="ml-2 text-sm text-gray-700">Show only overdue tasks</span>
        </label>
    </div>

    <!-- Results Count -->
    <div class="mt-4 text-sm text-gray-600">
        Found {{ $filteredTasks->count() }} task(s)
    </div>

    <!-- Search Results -->
    @if($filteredTasks->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
            <div class="p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Search Results</h4>
                <div class="space-y-3">
                    @foreach($filteredTasks as $task)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <h5 class="font-medium text-gray-900">{{ $task->title }}</h5>
                                    <span class="priority-badge priority-{{ $task->priority }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                    @if($task->due_date && $task->due_date->isPast())
                                        <span class="text-xs text-red-600 font-medium bg-red-100 px-2 py-1 rounded-full">
                                            Overdue
                                        </span>
                                    @endif
                                </div>
                                @if($task->description)
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-1">{{ $task->description }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <span>Column: {{ $task->column->name }}</span>
                                    @if($task->assignee)
                                        <span>Assignee: {{ $task->assignee->name }}</span>
                                    @else
                                        <span>Unassigned</span>
                                    @endif
                                    @if($task->due_date)
                                        <span>Due: {{ $task->due_date->format('M j, Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="$dispatch('openTaskModal', {{ $task->id }})"
                                        class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    View
                                </button>
                                <a href="{{ route('tasks.show', ['board' => $board, 'task' => $task]) }}"
                                   class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                                    Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif($search || $priority || $assignee || $column || $overdue)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center mt-6">
            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h4 class="text-lg font-medium text-gray-900 mb-2">No tasks found</h4>
            <p class="text-gray-600">Try adjusting your search criteria or clear the filters.</p>
        </div>
    @endif
</div>