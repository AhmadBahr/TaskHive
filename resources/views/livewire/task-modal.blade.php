<div>
    <!-- Modal Backdrop -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         style="display: none;">
        
        <!-- Modal Content -->
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $task ? 'Edit Task' : 'Create New Task' }}
                    </h3>
                    <button wire:click="closeModal" 
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="saveTask" class="space-y-6">
                    <!-- Task Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Task Title *
                        </label>
                        <input type="text" 
                               id="title"
                               wire:model="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-300 @enderror"
                               placeholder="Enter task title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Task Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description"
                                  wire:model="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-300 @enderror"
                                  placeholder="Enter task description"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority and Column Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                Priority *
                            </label>
                            <select id="priority"
                                    wire:model="priority" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('priority') border-red-300 @enderror">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Column -->
                        <div>
                            <label for="column_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Column *
                            </label>
                            <select id="column_id"
                                    wire:model="column_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('column_id') border-red-300 @enderror">
                                @foreach($columns as $column)
                                    <option value="{{ $column->id }}">{{ $column->name }}</option>
                                @endforeach
                            </select>
                            @error('column_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Assignee and Due Date Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Assignee -->
                        <div>
                            <label for="assignee_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Assignee
                            </label>
                            <select id="assignee_id"
                                    wire:model="assignee_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('assignee_id') border-red-300 @enderror">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('assignee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Due Date
                            </label>
                            <input type="date" 
                                   id="due_date"
                                   wire:model="due_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('due_date') border-red-300 @enderror">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Priority Preview -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-700">Priority:</span>
                        <span class="priority-badge priority-{{ $priority }}">
                            {{ ucfirst($priority) }}
                        </span>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <div>
                            @if($task)
                                <button type="button" 
                                        wire:click="deleteTask"
                                        wire:confirm="Are you sure you want to delete this task? This action cannot be undone."
                                        class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                    Delete Task
                                </button>
                            @endif
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="button" 
                                    wire:click="closeModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-200">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-200">
                                {{ $task ? 'Update Task' : 'Create Task' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>