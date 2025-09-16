<div class="space-y-6" x-data="boardKanban">
    <!-- Board Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $board->name }}</h1>
                <p class="text-gray-600">{{ $board->slug }}</p>
            </div>
            <div class="text-sm text-gray-500">
                Created {{ $board->created_at->diffForHumans() }}
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" 
         role="main" 
         aria-label="Kanban board with task columns">
        @foreach($columns as $column)
            <div class="kanban-column" 
                 x-data="{ 
                     columnId: {{ $column->id }},
                     wipStatus: '{{ $this->getWipStatus($column) }}'
                 }"
                 @drop="handleDrop(columnId)"
                 @dragover.prevent
                 @dragenter.prevent
                 role="region"
                 aria-label="{{ $column->name }} column"
                 tabindex="0">
                
                <!-- Column Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-900">{{ $column->name }}</h3>
                    <div class="flex items-center space-x-2">
                        @if($column->wip_limit)
                            <span class="wip-limit {{ $this->getWipStatus($column) === 'exceeded' ? 'exceeded' : ($this->getWipStatus($column) === 'warning' ? 'warning' : '') }}">
                                {{ $this->getTasksForColumn($column->id)->count() }}/{{ $column->wip_limit }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-500">
                            {{ $this->getTasksForColumn($column->id)->count() }} tasks
                        </span>
                    </div>
                </div>

                <!-- Tasks -->
                <div class="space-y-3" 
                     x-sortable="{
                         group: 'tasks',
                         animation: 200,
                         ghostClass: 'opacity-50',
                         chosenClass: 'transform rotate-2',
                         onEnd: function(evt) {
                             if (evt.from !== evt.to) {
                                 $wire.moveTask(evt.item.dataset.taskId, columnId, evt.newIndex + 1);
                             }
                         }
                     }">
                    @foreach($this->getTasksForColumn($column->id) as $task)
                        <div class="kanban-task" 
                             data-task-id="{{ $task->id }}"
                             draggable="true"
                             @dragstart="startDrag({{ $task->id }}, {{ $column->id }})"
                             @dragend="endDrag()"
                             @click="Livewire.emit('openTaskModal', {{ $task->id }})"
                             x-data="{ task: @js($task->toArray()) }"
                             role="button"
                             tabindex="0"
                             aria-label="Task: {{ $task->title }}, Priority: {{ $task->priority }}{{ $task->assignee ? ', Assigned to: ' . $task->assignee->name : '' }}{{ $task->due_date ? ', Due: ' . $task->due_date->format('M j, Y') : '' }}"
                             @keydown.enter="Livewire.emit('openTaskModal', {{ $task->id }})"
                             @keydown.space.prevent="Livewire.emit('openTaskModal', {{ $task->id }})">
                            
                            <!-- Task Header -->
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-start space-x-2 flex-1">
                                    <input type="checkbox" 
                                           wire:click.stop="$dispatch('toggleTask', {{ $task->id }})"
                                           class="mt-1 rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <h4 class="font-medium text-gray-900 text-sm line-clamp-2 flex-1">{{ $task->title }}</h4>
                                </div>
                                <span class="priority-badge priority-{{ $task->priority }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>

                            <!-- Task Description -->
                            @if($task->description)
                                <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $task->description }}</p>
                            @endif

                            <!-- Task Footer -->
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <div class="flex items-center space-x-2">
                                    @if($task->assignee)
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-medium text-primary-600">
                                                    {{ substr($task->assignee->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="ml-1">{{ $task->assignee->name }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($task->due_date)
                                    <div class="flex items-center {{ $task->due_date->isPast() ? 'text-red-600 font-medium' : '' }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M j') }}
                                        @if($task->due_date->isPast())
                                            <span class="ml-1 text-red-600">Overdue</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Empty State -->
                    @if($this->getTasksForColumn($column->id)->isEmpty())
                        <div class="text-center py-8 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <p class="text-sm">No tasks yet</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>