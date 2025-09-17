<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Task') }}: {{ $task->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('tasks.show', ['board' => $board, 'task' => $task]) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Task
                </a>
                <a href="{{ route('boards.show', $board) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Board
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tasks.update', ['board' => $board, 'task' => $task]) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Task Title -->
                        <div>
                            <x-input-label for="title" :value="__('Task Title')" />
                            <x-text-input id="title" 
                                         class="block mt-1 w-full" 
                                         type="text" 
                                         name="title" 
                                         :value="old('title', $task->title)" 
                                         required 
                                         autofocus 
                                         placeholder="Enter task title" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Task Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" 
                                    name="description" 
                                    rows="4"
                                    class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"
                                    placeholder="Enter task description">{{ old('description', $task->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Priority -->
                        <div>
                            <x-input-label for="priority" :value="__('Priority')" />
                            <select id="priority" 
                                    name="priority" 
                                    class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <!-- Column -->
                        <div>
                            <x-input-label for="column_id" :value="__('Column')" />
                            <select id="column_id" 
                                    name="column_id" 
                                    class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                @foreach($board->columns as $column)
                                    <option value="{{ $column->id }}" {{ old('column_id', $task->column_id) == $column->id ? 'selected' : '' }}>
                                        {{ $column->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('column_id')" class="mt-2" />
                        </div>

                        <!-- Assignee -->
                        <div>
                            <x-input-label for="assignee_id" :value="__('Assignee')" />
                            <select id="assignee_id" 
                                    name="assignee_id" 
                                    class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">Unassigned</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}" {{ old('assignee_id', $task->assignee_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('assignee_id')" class="mt-2" />
                        </div>

                        <!-- Due Date -->
                        <div>
                            <x-input-label for="due_date" :value="__('Due Date')" />
                            <x-text-input id="due_date" 
                                         class="block mt-1 w-full" 
                                         type="datetime-local" 
                                         name="due_date" 
                                         :value="old('due_date', $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '')" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>

                        <!-- Position -->
                        <div>
                            <x-input-label for="position" :value="__('Position')" />
                            <x-text-input id="position" 
                                         class="block mt-1 w-full" 
                                         type="number" 
                                         name="position" 
                                         :value="old('position', $task->position)" 
                                         min="1" />
                            <p class="mt-1 text-sm text-gray-600">
                                Position within the column (1 = top, higher numbers = lower)
                            </p>
                            <x-input-error :messages="$errors->get('position')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('tasks.show', ['board' => $board, 'task' => $task]) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-200">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Task') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
