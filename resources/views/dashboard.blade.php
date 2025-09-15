<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-2">Welcome to Task Hive! 🎉</h3>
                    <p class="text-gray-600 mb-4">Your personal Kanban board management system. Create boards, organize tasks, and track your progress with drag-and-drop functionality.</p>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('boards.index') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                            📋 View All Boards
                        </a>
                        <a href="{{ route('boards.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                            ➕ Create New Board
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-primary-600 text-lg">📋</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Boards</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->boards()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-600 text-lg">✅</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Completed Tasks</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->getCompletedTasksCount() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <span class="text-yellow-600 text-lg">🔄</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">In Progress</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->getInProgressTasksCount() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Boards -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Boards</h3>
                    @if(auth()->user()->boards()->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach(auth()->user()->boards()->latest()->take(6)->get() as $board)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-150 ease-in-out">
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $board->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-3">{{ $board->columns()->count() }} columns • {{ $board->tasks()->count() }} tasks</p>
                                    <a href="{{ route('boards.show', $board) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                        View Board →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-6xl mb-4">📋</div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">No boards yet</h4>
                            <p class="text-gray-600 mb-4">Get started by creating your first Kanban board!</p>
                            <a href="{{ route('boards.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                Create Your First Board
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
