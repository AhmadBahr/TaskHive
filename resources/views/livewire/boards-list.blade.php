<div class="space-y-6">
    @if($boards->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No boards yet</h3>
            <p class="text-gray-500 mb-6">Get started by creating your first Kanban board.</p>
            <button 
                onclick="Livewire.emit('openCreateBoardModal')"
                class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                Create Your First Board
            </button>
        </div>
    @else
        <!-- Boards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($boards as $board)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <!-- Board Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    <a href="{{ route('boards.show', $board->slug) }}" 
                                       class="hover:text-primary-600 transition-colors duration-200">
                                        {{ $board->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500">{{ $board->slug }}</p>
                            </div>
                            
                            <!-- Board Actions -->
                            <div class="flex items-center space-x-2">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="p-1 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 border border-gray-200">
                                        <a href="{{ route('boards.show', $board->slug) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            View Board
                                        </a>
                                        <button wire:click="deleteBoard({{ $board->id }})"
                                                wire:confirm="Are you sure you want to delete this board? This action cannot be undone."
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            Delete Board
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Board Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary-600">{{ $board->columns_count }}</div>
                                <div class="text-xs text-gray-500">Columns</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-600">{{ $board->tasks_count }}</div>
                                <div class="text-xs text-gray-500">Tasks</div>
                            </div>
                        </div>

                        <!-- Board Footer -->
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Created {{ $board->created_at->diffForHumans() }}</span>
                            <a href="{{ route('boards.show', $board->slug) }}" 
                               class="text-primary-600 hover:text-primary-700 font-medium">
                                Open →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>