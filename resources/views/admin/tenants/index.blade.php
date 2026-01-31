<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Organizations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div class="flex space-x-4">
                    <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 text-sm font-medium {{ !request('status') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900 dark:text-indigo-200' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }} rounded-md">All</a>
                    <a href="{{ route('admin.tenants.index', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-medium {{ request('status') === 'pending' ? 'text-yellow-600 bg-yellow-50 dark:bg-yellow-900 dark:text-yellow-200' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }} rounded-md">Pending</a>
                    <a href="{{ route('admin.tenants.index', ['status' => 'suspended']) }}" class="px-4 py-2 text-sm font-medium {{ request('status') === 'suspended' ? 'text-red-600 bg-red-50 dark:bg-red-900 dark:text-red-200' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }} rounded-md">Suspended</a>
                </div>
                <div>
                    <!-- Import/Export Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.tenants.export') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                            Export CSV
                        </a>
                         <a href="{{ route('admin.tenants.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:shadow-outline-indigo disabled:opacity-25 transition ease-in-out duration-150">
                            Create Organization
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Domain (Slug)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Owner</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Plan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($tenants as $tenant)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $tenant->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $tenant->slug }}</div>
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $tenant->owner?->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-400">{{ $tenant->owner?->email ?? '' }}</div>
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $tenant->plan?->name ?? 'Free' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'suspended' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    ];
                                    $class = $statusClasses[$tenant->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-2">Edit</a>
                                <!-- Add Suspend/Delete actions here later -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4">
                    {{ $tenants->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
