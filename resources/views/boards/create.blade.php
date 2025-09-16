<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Board') }}
            </h2>
            <a href="{{ route('boards.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Boards
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('boards.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Board Name -->
                        <div>
                            <x-input-label for="name" :value="__('Board Name')" />
                            <x-text-input id="name" 
                                         class="block mt-1 w-full" 
                                         type="text" 
                                         name="name" 
                                         :value="old('name')" 
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
                                         :value="old('slug')" 
                                         placeholder="board-slug" />
                            <p class="mt-1 text-sm text-gray-600">
                                Leave empty to auto-generate from board name. Used in the URL.
                            </p>
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('boards.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-200">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Board') }}
                            </x-primary-button>
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
