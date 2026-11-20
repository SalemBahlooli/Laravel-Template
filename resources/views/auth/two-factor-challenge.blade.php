<x-layouts.guest :title="__('Two-factor authentication')">
    <div class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
        <i class="fa-solid fa-shield-halved text-xl"></i>
    </div>
    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Enter your code') }}</h1>
    <p class="mt-1 text-sm text-slate-500">{{ __('Open the authenticator app on your phone and type the 6-digit code for :app.', ['app' => setting('app_name')]) }}</p>

    <form method="POST" action="{{ route('two-factor.verify') }}" class="mt-6 space-y-4">
        @csrf
        <input name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="7" required autofocus placeholder="123 456"
               class="input py-3 text-center font-mono text-2xl tracking-[0.5em]">

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember_device" value="1" class="checkbox">
            {{ __("Don't ask again on this browser for :days days", ['days' => \App\Support\TwoFactor::REMEMBER_DAYS]) }}
        </label>

        <button type="submit" class="btn btn-primary w-full py-2.5">{{ __('Verify') }}</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center">
        @csrf
        <button type="submit" class="text-sm text-slate-500 hover:text-slate-700 hover:underline">{{ __('Use another account') }}</button>
    </form>

    <p class="mt-6 rounded-lg bg-slate-50 p-3 text-xs text-slate-500">
        <i class="fa-solid fa-circle-info me-1"></i>{{ __('Lost your phone? Ask an administrator to unlink your authenticator app.') }}
    </p>
</x-layouts.guest>
