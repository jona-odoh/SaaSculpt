<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Tenants -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Total Tenants</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalTenants }}</p>
                </div>
                <!-- Active Subscriptions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Active Subscriptions</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Tenant::whereNotNull('pm_type')->count() }}</p>
                </div>
                <!-- Total Revenue (Placeholder) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Total Revenue (Est.)</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">$0.00</p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Growth Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tenant Growth (Last 12 Months)</h3>
                    <div class="relative h-64">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>

                <!-- Retention Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tenant Status Distribution</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="retentionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Tenant List -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Tenants</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Domain</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach (\App\Models\Tenant::latest()->take(5)->get() as $tenant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $tenant->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $tenant->slug }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tenant->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} dark:bg-opacity-20">
                                            {{ ucfirst($tenant->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $tenant->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Usage (Users per Tenant) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Top Tenants by Users</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Users</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($topTenants as $tenant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white truncate w-32" title="{{ $tenant->name }}">{{ $tenant->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $tenant->users_count }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Growth Chart
            const ctxGrowth = document.getElementById('growthChart').getContext('2d');
            new Chart(ctxGrowth, {
                type: 'bar',
                data: {
                    labels: @json($growthLabels),
                    datasets: [{
                        label: 'New Tenants',
                        data: @json($growthValues),
                        backgroundColor: 'rgba(79, 70, 229, 0.6)', // Indigo-600
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            },
                             ticks: { color: '#9CA3AF' }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: { color: '#9CA3AF' }
                        }
                    },
                    plugins: {
                        legend: { labels: { color: '#9CA3AF' } }
                    }
                }
            });

            // Retention Chart
            const ctxRetention = document.getElementById('retentionChart').getContext('2d');
            new Chart(ctxRetention, {
                type: 'doughnut',
                data: {
                    labels: @json($statusLabels),
                    datasets: [{
                        data: @json($statusValues),
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.6)',  // Green (Active)
                            'rgba(234, 179, 8, 0.6)',  // Yellow (Pending)
                            'rgba(239, 68, 68, 0.6)'   // Red (Suspended)
                        ],
                        borderColor: [
                            'rgba(34, 197, 94, 1)',
                            'rgba(234, 179, 8, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: { color: '#9CA3AF' }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
