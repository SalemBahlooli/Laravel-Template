<x-layouts.guest :title="__('Forgot password')">
    <a href="{{ route('login') }}" class="mb-6 inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700">
        <i class="fa-solid fa-arrow-left rtl-flip text-xs"></i>{{ __('Back to sign in') }}
    </a>
    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Forgot your password?') }}</h1>
    <p class="mt-1 text-sm text-slate-500">{{ __('Enter your email and we will send you a link to choose a new one.') }}</p>

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf
        <x-input name="email" type="email" :label="__('Email')" icon="fa-envelope" required autofocus autocomplete="email" />
        <button type="submit" class="btn btn-primary w-full py-2.5">{{ __('Send the reset link') }}</button>
    </form>

    @if ($codeReset)
        <div class="mt-6 rounded-xl border border-slate-200 p-4 text-sm">
            <div class="font-medium text-slate-800"><i class="fa-solid fa-mobile-screen me-1.5 text-slate-400"></i>{{ __('No email access?') }}</div>
            <p class="mt-1 text-slate-500">{{ __('If you linked an authenticator app, you can reset your password with its code.') }}</p>
            <a href="{{ route('password.code') }}" class="mt-2 inline-block font-medium text-brand-700 hover:underline">{{ __('Reset with the authenticator code') }}</a>
        </div>
    @endif
</x-layouts.guest>
