<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Details') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- User Info & Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <img class="h-16 w-16 rounded-full object-cover mr-4" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400 mt-1">Joined {{ $user->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <!-- Ban/Unban -->
                    <form method="POST" action="{{ route('admin.users.ban', $user->id) }}" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest {{ $user->isBanned() ? 'bg-green-600 hover:bg-green-500' : 'bg-red-600 hover:bg-red-500' }} focus:outline-none transition ease-in-out duration-150">
                            {{ $user->isBanned() ? 'Unban User' : 'Ban User' }}
                        </button>
                    </form>

                    <!-- Impersonate -->
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.impersonate', $user->id) }}" onsubmit="return confirm('Login as this user?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:outline-none transition ease-in-out duration-150">
                            Login as User
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Activity Logs -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Activity Logs</h3>
                    </div>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-600 max-h-96 overflow-y-auto">
                        @forelse($user->activityLogs as $log)
                        <li class="p-4">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $log->action }}</span>
                                <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $log->description }}</p>
                            <p class="text-xs text-gray-400 mt-1">IP: {{ $log->ip_address }}</p>
                        </li>
                        @empty
                        <li class="p-4 text-sm text-gray-500">No activity recorded.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Sessions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Active Sessions</h3>
                    </div>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-600 max-h-96 overflow-y-auto">
                        @forelse($user->sessions as $session)
                        <li class="p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $session->ip_address }} 
                                    @if($session->id === request()->session()->getId())
                                        <span class="text-green-500 font-bold">(Current)</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ $session->user_agent }}</p>
                            </div>
                            <div class="text-xs text-gray-400">
                                Last active: {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                            </div>
                        </li>
                        @empty
                        <li class="p-4 text-sm text-gray-500">No active sessions found (Database driver might not be populated instantly).</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Organizations -->
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Organizations</h3>
                </div>
                <div class="p-6">
                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Owned Organizations</h4>
                    <ul class="list-disc pl-5 mb-4 text-sm text-gray-600 dark:text-gray-400">
                        @forelse($user->ownedTeams as $team)
                            <li>{{ $team->name }} ({{ $team->slug }}) - <span class="{{ $team->status === 'active' ? 'text-green-500' : 'text-red-500' }}">{{ $team->status }}</span></li>
                        @empty
                            <li>None</li>
                        @endforelse
                    </ul>

                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Member of Organizations</h4>
                     <ul class="list-disc pl-5 text-sm text-gray-600 dark:text-gray-400">
                        @forelse($user->teams as $team)
                            <li>{{ $team->name }} ({{ $team->slug }}) - Role: {{ $team->membership->role ?? 'N/A' }}</li>
                        @empty
                            <li>None</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
