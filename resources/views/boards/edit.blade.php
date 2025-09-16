<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Board') }}: {{ $board->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('boards.show', $board) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Board
                </a>
                <a href="{{ route('boards.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Boards
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('boards.update', $board) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Board Name -->
                        <div>
                            <x-input-label for="name" :value="__('Board Name')" />
                            <x-text-input id="name" 
                                         class="block mt-1 w-full" 
                                         type="text" 
                                         name="name" 
                                         :value="old('name', $board->name)" 
                                         required 
                                         autofocus 
                                         placeholder="Enter board name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Board Slug -->
                        <div>
                            <x-input-label for="slug" :value="__('Board Slug')" />
                            <x-text-input id="slug" 
                                         class="block mt-1 w-full" 
                                         type="text" 
                                         name="slug" 
                                         :value="old('slug', $board->slug)" 
                                         placeholder="board-slug" />
                            <p class="mt-1 text-sm text-gray-600">
                                Used in the URL. Changing this will change the board's URL.
                            </p>
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <!-- Board Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Board Information</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <span class="font-medium">Created:</span> {{ $board->created_at->format('M j, Y') }}
                                </div>
                                <div>
                                    <span class="font-medium">Last Updated:</span> {{ $board->updated_at->format('M j, Y') }}
                                </div>
                                <div>
                                    <span class="font-medium">Columns:</span> {{ $board->columns()->count() }}
                                </div>
                                <div>
                                    <span class="font-medium">Tasks:</span> {{ $board->tasks()->count() }}
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between">
                            <form method="POST" action="{{ route('boards.destroy', $board) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this board? This action cannot be undone and will delete all tasks and columns.')"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                    Delete Board
                                </button>
                            </form>
                            
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('boards.show', $board) }}" 
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-200">
                                    Cancel
                                </a>
                                <x-primary-button>
                                    {{ __('Update Board') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-') // Replace multiple hyphens with single
                .trim('-'); // Remove leading/trailing hyphens
            
            document.getElementById('slug').value = slug;
        });
    </script>
</x-app-layout>
