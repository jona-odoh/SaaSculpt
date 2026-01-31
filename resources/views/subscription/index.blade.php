<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Subscription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 font-medium text-sm text-red-600">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Plan Info -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Current Plan</h3>
                        <div class="mt-4 p-4 bg-gray-50 rounded-md">
                            @if ($tenant->plan)
                                <p class="text-2xl font-bold">{{ $tenant->plan?->name }}</p>
                                <p class="text-gray-500">{{ $tenant->plan?->price / 100 }} {{ $tenant->plan?->currency }} / {{ $tenant->plan?->interval }}</p>
                            @else
                                <p class="text-gray-500">No active plan.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Change Plan / Subscribe -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Update Subscription</h3>
                        <form action="{{ route('subscription.store') }}" method="POST" id="subscription-form" class="mt-4 space-y-4">
                            @csrf
                            
                            <div>
                                <label for="plan" class="block text-sm font-medium text-gray-700">Choose Plan</label>
                                <select id="plan" name="plan_slug" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->slug }}" {{ ($tenant->plan_id == $plan->id) ? 'selected' : '' }}>
                                            {{ $plan->name }} - ${{ number_format($plan->price / 100, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Stripe Element -->
                            <div class="mt-4">
                                <label for="card-element" class="block text-sm font-medium text-gray-700">Credit or debit card</label>
                                <div id="card-holder-name" class="mt-2 p-2 border rounded" data-name="{{ auth()->user()->name }}"></div>
                                <div id="card-element" class="mt-2 p-3 border rounded-md">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <div id="card-errors" role="alert" class="mt-2 text-sm text-red-600"></div>
                            </div>

                                Subscribe / Swap
                            </button>
                        </form>

                        @if ($tenant->subscribed('default') && !$tenant->subscription('default')->onGracePeriod())
                            <div class="mt-8 border-t pt-6">
                                <h3 class="text-lg font-medium text-red-600">Danger Zone</h3>
                                <form action="{{ route('subscription.destroy') }}" method="POST" class="mt-4" onsubmit="return confirm('Are you sure you want to cancel?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                                        Cancel Subscription
                                    </button>
                                </form>
                            </div>
                        @endif
                        
                        @if ($tenant->subscription('default') && $tenant->subscription('default')->onGracePeriod())
                             <div class="mt-4 p-4 bg-yellow-50 text-yellow-700 rounded-md">
                                 Subscription will end on {{ $tenant->subscription('default')->ends_at->format('Y-m-d') }}.
                             </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');

        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        const form = document.getElementById('subscription-form');

        cardButton.addEventListener('click', async (e) => {
            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: { name: cardHolderName.dataset.name }
                    }
                }
            );

            if (error) {
                const displayError = document.getElementById('card-errors');
                displayError.textContent = error.message;
            } else {
                // Send the token to your server.
                stripeTokenHandler(setupIntent);
            }
        });

        function stripeTokenHandler(setupIntent) {
            // Insert the setupIntent ID into the form so it gets submitted to the server
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', setupIntent.payment_method);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>
    @endpush
</x-app-layout>
