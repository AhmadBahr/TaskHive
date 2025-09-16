<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell -->
    <button @click="open = !open" 
            class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-md">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5L19.5 4.5M15 4.5h5v5M4.5 4.5l15 15"></path>
        </svg>
        
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
         style="display: none;">
        
        <!-- Header -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                <div class="flex items-center space-x-2">
                    @if($unreadCount > 0)
                        <button wire:click="markAsRead" 
                                class="text-sm text-primary-600 hover:text-primary-700">
                            Mark all read
                        </button>
                    @endif
                    <button wire:click="clearNotifications" 
                            class="text-sm text-gray-600 hover:text-gray-700">
                        Clear all
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @if(count($notifications) > 0)
                @foreach($notifications as $notification)
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <!-- Notification Icon -->
                            <div class="flex-shrink-0">
                                @switch($notification['type'])
                                    @case('assignment')
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('movement')
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('priority')
                                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('due_date')
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @default
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                @endswitch
                            </div>

                            <!-- Notification Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $notification['task_title'] }}</span>
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $notification['message'] }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5L19.5 4.5M15 4.5h5v5M4.5 4.5l15 15"></path>
                    </svg>
                    <p class="text-gray-500">No notifications yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
