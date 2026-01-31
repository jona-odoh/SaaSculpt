<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Organization') }}: {{ $tenant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('admin.tenants.update', $tenant->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $tenant->name)" required autofocus />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <!-- Slug -->
                        <div class="mt-4">
                            <x-label for="slug" value="{{ __('Slug (Subdomain)') }}" />
                            <x-input id="slug" class="block mt-1 w-full" type="text" name="slug" :value="old('slug', $tenant->slug)" required />
                            <x-input-error for="slug" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">{{ __('Changing this will affect the organization\'s URL.') }}</p>
                        </div>

                        <!-- Plan -->
                        <div class="mt-4">
                            <x-label for="plan_id" value="{{ __('Plan') }}" />
                            <select id="plan_id" name="plan_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">{{ __('No Plan (Free)') }}</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id', $tenant->plan_id) == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} ({{ $plan->price_formatted }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="plan_id" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-label for="status" value="{{ __('Status') }}" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ old('status', $tenant->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="suspended" {{ old('status', $tenant->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            <x-input-error for="status" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                {{ __('Update Organization') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
