<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.plans.store') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <!-- Slug -->
                        <div class="mt-4">
                            <x-label for="slug" value="{{ __('Slug') }}" />
                            <x-input id="slug" class="block mt-1 w-full" type="text" name="slug" :value="old('slug')" required />
                            <x-input-error for="slug" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Stripe Price ID -->
                            <div>
                                <x-label for="stripe_price_id" value="{{ __('Stripe Price ID') }}" />
                                <x-input id="stripe_price_id" class="block mt-1 w-full" type="text" name="stripe_price_id" :value="old('stripe_price_id')" />
                                <x-input-error for="stripe_price_id" class="mt-2" />
                            </div>

                            <!-- Paystack Plan Code -->
                            <div>
                                <x-label for="paystack_plan_code" value="{{ __('Paystack Plan Code') }}" />
                                <x-input id="paystack_plan_code" class="block mt-1 w-full" type="text" name="paystack_plan_code" :value="old('paystack_plan_code')" />
                                <x-input-error for="paystack_plan_code" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <!-- Price -->
                            <div>
                                <x-label for="price" value="{{ __('Price (in cents)') }}" />
                                <x-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price')" required />
                                <x-input-error for="price" class="mt-2" />
                            </div>

                            <!-- Currency -->
                            <div>
                                <x-label for="currency" value="{{ __('Currency') }}" />
                                <x-input id="currency" class="block mt-1 w-full" type="text" name="currency" :value="old('currency', 'USD')" required />
                                <x-input-error for="currency" class="mt-2" />
                            </div>

                            <!-- Interval -->
                            <div>
                                <x-label for="interval" value="{{ __('Interval') }}" />
                                <select id="interval" name="interval" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    <option value="month">Monthly</option>
                                    <option value="year">Yearly</option>
                                </select>
                                <x-input-error for="interval" class="mt-2" />
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="mt-4">
                            <x-label for="features" value="{{ __('Features (comma separated)') }}" />
                            <textarea id="features" name="features" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" rows="4">{{ old('features') }}</textarea>
                            <x-input-error for="features" class="mt-2" />
                        </div>

                        <!-- Active Status -->
                        <div class="block mt-4">
                            <label for="is_active" class="flex items-center">
                                <x-checkbox id="is_active" name="is_active" :checked="old('is_active', true)" />
                                <span class="ml-2 text-sm text-gray-600">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                {{ __('Create Plan') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
