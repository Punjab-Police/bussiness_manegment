<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 px-4 py-12">

        <div class="w-full max-w-md">

            {{-- Logo / Brand --}}
            <div class="text-center mb-8">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-xl shadow-indigo-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-8 w-8 text-white"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/> 
                    </svg>
                </div>

                <h1 class="text-3xl font-bold tracking-tight text-white">
                    Welcome back
                </h1>

                <p class="mt-2 text-sm text-slate-400">
                    Sign in to continue to your account
                </p>
            </div>

            {{-- Login Card --}}
            <div class="rounded-3xl border border-white/10 bg-white/[0.07] p-6 shadow-2xl shadow-black/20 backdrop-blur-xl sm:p-8">

                <x-auth-session-status
                    class="mb-5 rounded-xl bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300"
                    :status="session('status')"
                />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email"
                               class="mb-2 block text-sm font-medium text-slate-200">
                            Email address
                        </label>

                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-slate-400"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.7">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="you@example.com"
                                class="block w-full rounded-xl border border-white/10 bg-slate-950/40 py-3.5 pl-12 pr-4 text-sm text-white placeholder-slate-500 outline-none transition duration-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20"
                            />
                        </div>

                        @error('email')
                            <p class="mt-2 text-sm text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password"
                                   class="block text-sm font-medium text-slate-200">
                                Password
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-xs font-medium text-indigo-300 transition hover:text-indigo-200 hover:underline">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-slate-400"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.7">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="block w-full rounded-xl border border-white/10 bg-slate-950/40 py-3.5 pl-12 pr-4 text-sm text-white placeholder-slate-500 outline-none transition duration-200 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20"
                            />
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0"
                        />

                        <label for="remember_me"
                               class="ml-2 cursor-pointer text-sm text-slate-300">
                            Remember me
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition duration-300 hover:-translate-y-0.5 hover:from-indigo-400 hover:to-violet-500 hover:shadow-xl hover:shadow-indigo-500/30 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 active:translate-y-0"
                    >
                        Sign in to your account

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>

                {{-- Optional Registration Link --}}
                @if (Route::has('register'))
                    <div class="mt-6 text-center text-sm text-slate-400">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                           class="font-semibold text-indigo-300 transition hover:text-indigo-200">
                            Create account
                        </a>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <p class="mt-8 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'Your Application') }}.
                All rights reserved.
            </p>

        </div>
    </div>
</x-guest-layout>