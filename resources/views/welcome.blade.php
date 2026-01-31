<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="relative overflow-hidden bg-white">
        <div class="mx-auto max-w-7xl">
            <div class="relative z-10 bg-white pb-8 sm:pb-16 md:pb-20 lg:w-full lg:max-w-2xl lg:pb-28 xl:pb-32">
                <svg class="absolute inset-y-0 right-0 hidden h-full w-48 translate-x-1/2 transform text-white lg:block" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <div class="relative px-4 pt-6 sm:px-6 lg:px-8">
                    <nav class="relative flex items-center justify-between sm:h-10 lg:justify-start" aria-label="Global">
                        <div class="flex flex-shrink-0 flex-grow items-center lg:flex-grow-0">
                            <div class="flex w-full items-center justify-between md:w-auto">
                                <a href="#">
                                    <span class="sr-only">{{ config('app.name') }}</span>
                                    <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">SaaSculpt</h1>
                                </a>
                            </div>
                        </div>
                        <div class="hidden md:ml-10 md:block md:space-x-8 md:pr-4">
                            <a href="#features" class="font-medium text-gray-500 hover:text-gray-900 transition">Features</a>
                            <a href="#pricing" class="font-medium text-gray-500 hover:text-gray-900 transition">Pricing</a>
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="rounded-full bg-indigo-100 px-6 py-2 font-medium text-indigo-700 hover:bg-indigo-200 transition">Register</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </nav>
                </div>

                <main class="mx-auto mt-10 max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Build your next SaaS</span>
                            <span class="block text-indigo-600 xl:inline bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">in record time.</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mx-auto sm:mt-5 sm:max-w-xl sm:text-lg md:mt-5 md:text-xl lg:mx-0 font-light">
                            The ultimate multi-tenant boilerplate with subdomain isolation, subscription billing, and a premium UI. Launch faster, scale better.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-full shadow">
                                <a href="{{ route('register') }}" class="flex w-full items-center justify-center rounded-full border border-transparent bg-indigo-600 px-8 py-3 text-base font-semibold text-white hover:bg-indigo-700 md:py-4 md:text-lg transition transform hover:-translate-y-1">
                                    Get started
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#features" class="flex w-full items-center justify-center rounded-full border border-transparent bg-indigo-50 px-8 py-3 text-base font-medium text-indigo-700 hover:bg-indigo-100 md:py-4 md:text-lg transition">
                                    Live demo
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-gray-50">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:h-full lg:w-full opacity-90" src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80" alt="">
            <div class="absolute inset-0 bg-gradient-to-r from-white to-transparent lg:via-white/20"></div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div id="pricing" class="bg-slate-900 py-24 sm:py-32 relative overflow-hidden">
        <!-- Background Glow -->
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
             <div class="absolute top-[20%] left-[20%] w-[30rem] h-[30rem] bg-purple-500/20 rounded-full blur-3xl"></div>
             <div class="absolute bottom-[20%] right-[20%] w-[30rem] h-[30rem] bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10">
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-400">Simple Pricing</h2>
                <p class="mt-2 text-4xl font-bold tracking-tight text-white sm:text-5xl">Start building for free, scale when ready.</p>
            </div>
            <div class="isolate mx-auto mt-16 grid max-w-md grid-cols-1 gap-y-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                @foreach (App\Models\Plan::where('is_active', true)->get() as $plan)
                <div class="flex flex-col justify-between rounded-3xl bg-white/5 p-8 ring-1 ring-white/10 sm:p-10 hover:bg-white/10 transition duration-300">
                    <div>
                        <h3 id="tier-{{ $plan->slug }}" class="text-base font-semibold leading-7 text-indigo-400">{{ $plan->name }}</h3>
                        <div class="mt-4 flex items-baseline gap-x-2">
                            <span class="text-5xl font-bold tracking-tight text-white">${{ number_format($plan->price / 100, 2) }}</span>
                            <span class="text-base font-semibold leading-7 text-gray-400">/{{ $plan->interval }}</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-400">Everything you need to get started.</p>
                        <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-300">
                            @foreach ($plan->features ?? ['Unlimited Projects', 'Team Collaboration', 'Analytics Dashboard', 'Priority Support'] as $feature)
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="{{ route('register', ['plan' => $plan->slug]) }}" aria-describedby="tier-{{ $plan->slug }}" class="mt-8 block rounded-full bg-indigo-600 px-3 py-3 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 transition transform hover:scale-105">Choose {{ $plan->name }}</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-white/5">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
            <div class="mt-8 md:order-1 md:mt-0">
                <p class="text-center text-base text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
            <div class="flex justify-center md:order-2 space-x-6">
                <!-- Social links could go here -->
            </div>
        </div>
    </footer>
</body>
</html>
