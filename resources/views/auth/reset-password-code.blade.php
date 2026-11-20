<x-layouts.guest :title="__('Reset password')">
    <a href="{{ route('login') }}" class="mb-6 inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700">
        <i class="fa-solid fa-arrow-left rtl-flip text-xs"></i>{{ __('Back to sign in') }}
    </a>
    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Reset with your authenticator') }}</h1>
    <p class="mt-1 text-sm text-slate-500">{{ __('Only for accounts that linked an authenticator app. Otherwise ask an administrator.') }}</p>

    <form method="POST" action="{{ route('password.code.reset') }}" class="mt-6 space-y-4">
        @csrf
        <x-input name="email" type="email" :label="__('Email')" icon="fa-envelope" required autofocus autocomplete="username" />
        <div>
            <label for="f_code" class="label">{{ __('6-digit code from your authenticator app') }}</label>
            <input id="f_code" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="7" required placeholder="123 456"
                   class="input text-center font-mono text-lg tracking-[0.4em]">
        </div>
        <x-input name="password" type="password" :label="__('New password')" required autocomplete="new-password" />
        <x-input name="password_confirmation" type="password" :label="__('Repeat the new password')" required autocomplete="new-password" />
        <p class="text-xs text-slate-500">@include('auth.partials.password-rules')</p>
        <button type="submit" class="btn btn-primary w-full py-2.5">{{ __('Save password') }}</button>
    </form>
</x-layouts.guest>
