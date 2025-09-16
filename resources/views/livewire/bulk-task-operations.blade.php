<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6" 
     x-data="{ showBulkActions: false }"
     x-show="showBulkActions || {{ count($selectedTasks) > 0 ? 'true' : 'false' }}"
     x-transition>
    
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-4">
            <h3 class="text-lg font-semibold text-gray-900">Bulk Operations</h3>
            <span class="text-sm text-gray-600">
                {{ count($selectedTasks) }} task(s) selected
            </span>
        </div>
        <button wire:click="resetBulkOperation" 
                class="text-sm text-gray-600 hover:text-gray-700">
            Clear Selection
        </button>
    </div>

    @if(count($selectedTasks) > 0)
        <div class="space-y-4">
            <!-- Bulk Action Selection -->
            <div>
                <label for="bulkAction" class="block text-sm font-medium text-gray-700 mb-2">
                    Select Action
                </label>
                <select id="bulkAction"
                        wire:model="bulkAction" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Choose an action...</option>
                    <option value="assign">Assign to User</option>
                    <option value="move">Move to Column</option>
                    <option value="priority">Update Priority</option>
                    <option value="delete">Delete Tasks</option>
                </select>
                @error('bulkAction')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Assignee Selection -->
            @if($bulkAction === 'assign')
                <div>
                    <label for="bulkAssignee" class="block text-sm font-medium text-gray-700 mb-2">
                        Assign to User
                    </label>
                    <select id="bulkAssignee"
                            wire:model="bulkAssignee" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select assignee...</option>
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                        @endforeach
                    </select>
                    @error('bulkAssignee')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Column Selection -->
            @if($bulkAction === 'move')
                <div>
                    <label for="bulkColumn" class="block text-sm font-medium text-gray-700 mb-2">
                        Move to Column
                    </label>
                    <select id="bulkColumn"
                            wire:model="bulkColumn" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select column...</option>
                        @foreach($columns as $column)
                            <option value="{{ $column['id'] }}">{{ $column['name'] }}</option>
                        @endforeach
                    </select>
                    @error('bulkColumn')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Priority Selection -->
            @if($bulkAction === 'priority')
                <div>
                    <label for="bulkPriority" class="block text-sm font-medium text-gray-700 mb-2">
                        Update Priority
                    </label>
                    <select id="bulkPriority"
                            wire:model="bulkPriority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select priority...</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                    @error('bulkPriority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                <button wire:click="executeBulkAction"
                        class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-200">
                    Execute Action
                </button>
                
                @if($bulkAction === 'delete')
                    <button wire:click="executeBulkAction"
                            wire:confirm="Are you sure you want to delete {{ count($selectedTasks) }} task(s)? This action cannot be undone."
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200">
                        Confirm Delete
                    </button>
                @endif
            </div>
        </div>
    @endif

    <!-- Task Selection Controls -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" 
                           wire:model="selectAll" 
                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Select All Tasks</span>
                </label>
            </div>
            <button @click="showBulkActions = !showBulkActions"
                    class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                <span x-text="showBulkActions ? 'Hide Bulk Actions' : 'Show Bulk Actions'"></span>
            </button>
        </div>
    </div>
</div>