<x-layouts.guest :title="$welcome ? __('Choose your password') : __('Reset password')">
    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ $welcome ? __('Welcome! Choose your password') : __('Choose a new password') }}</h1>
    <p class="mt-1 text-sm text-slate-500">@include('auth.partials.password-rules')</p>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <x-input name="email" type="email" :label="__('Email')" :value="$email" required autocomplete="email" />
        <x-input name="password" type="password" :label="__('New password')" required autocomplete="new-password" autofocus />
        <x-input name="password_confirmation" type="password" :label="__('Repeat the new password')" required autocomplete="new-password" />
        <button type="submit" class="btn btn-primary w-full py-2.5">{{ __('Save password') }}</button>
    </form>
</x-layouts.guest>
