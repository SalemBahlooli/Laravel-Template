<x-layouts.guest :title="__('Sign in')">
    <div x-data="{ method: '{{ old('method', $passwordLogin || ! $codeSignIn ? 'password' : 'code') }}' }">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Sign in') }}</h1>
        <p class="mt-1 text-sm text-slate-500">{{ __('Welcome back. Enter your details to continue.') }}</p>

        @if ($codeSignIn && $passwordLogin)
            <div class="mt-6 grid grid-cols-2 gap-1 rounded-xl bg-slate-100 p-1 text-sm font-medium">
                <button type="button" @click="method = 'password'" class="flex items-center justify-center gap-2 rounded-lg py-2 transition"
                        :class="method === 'password' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                    <i class="fa-solid fa-key text-xs"></i>{{ __('Password') }}
                </button>
                <button type="button" @click="method = 'code'" class="flex items-center justify-center gap-2 rounded-lg py-2 transition"
                        :class="method === 'code' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                    <i class="fa-solid fa-mobile-screen text-xs"></i>{{ __('Authenticator code') }}
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="method" :value="method">

            <x-input name="login" :label="__('Email')" icon="fa-envelope" autocomplete="username" required autofocus
                     :value="old('login')" placeholder="name@company.com" />

            <div x-show="method === 'password'">
                <div class="mb-1.5 flex items-center justify-between">
                    <label for="f_password" class="label mb-0">{{ __('Password') }}</label>
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-brand-700 hover:underline">{{ __('Forgot password?') }}</a>
                </div>
                <div class="relative" x-data="{ show: false }">
                    <span class="pointer-events-none absolute inset-y-0 start-0 flex w-10 items-center justify-center text-slate-400"><i class="fa-solid fa-lock text-sm"></i></span>
                    <input id="f_password" name="password" :type="show ? 'text' : 'password'" autocomplete="current-password" class="input ps-10 pe-10" :required="method === 'password'">
                    <button type="button" class="absolute inset-y-0 end-0 flex w-10 items-center justify-center text-slate-400 hover:text-slate-600" @click="show = !show" :aria-label="show ? '{{ __('Hide') }}' : '{{ __('Show') }}'">
                        <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <div x-show="method === 'code'" x-cloak>
                <label for="f_code" class="label">{{ __('6-digit code from your authenticator app') }}</label>
                <input id="f_code" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="7" placeholder="123 456"
                       class="input text-center font-mono text-lg tracking-[0.4em]" :required="method === 'code'">
                <p class="help">{{ __('Works once you have linked an authenticator app to your account.') }}</p>
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="checkbox" @checked(old('remember'))>
                {{ __('Keep me signed in') }}
            </label>

            <button type="submit" class="btn btn-primary w-full py-2.5">
                {{ __('Sign in') }} <i class="fa-solid fa-arrow-right rtl-flip text-xs"></i>
            </button>
        </form>

        @if ($microsoft)
            <div class="my-6 flex items-center gap-3 text-xs text-slate-400">
                <span class="h-px flex-1 bg-slate-200"></span>{{ __('or') }}<span class="h-px flex-1 bg-slate-200"></span>
            </div>
            <a href="{{ route('auth.microsoft') }}" class="btn btn-secondary w-full py-2.5">
                <svg class="size-4" viewBox="0 0 23 23" aria-hidden="true"><path fill="#f35325" d="M1 1h10v10H1z"/><path fill="#81bc06" d="M12 1h10v10H12z"/><path fill="#05a6f0" d="M1 12h10v10H1z"/><path fill="#ffba08" d="M12 12h10v10H12z"/></svg>
                {{ __('Sign in with Microsoft') }}
            </a>
        @endif

        <div class="mt-8 space-y-2 text-center text-sm text-slate-500">
            @if ($registration)
                <p>{{ __('New here?') }} <a href="{{ route('register') }}" class="font-medium text-brand-700 hover:underline">{{ __('Create an account') }}</a></p>
            @endif
            @if ($codeReset)
                <p><a href="{{ route('password.code') }}" class="hover:text-slate-700 hover:underline">{{ __('Reset my password with the authenticator app') }}</a></p>
            @endif
        </div>
    </div>
</x-layouts.guest>
