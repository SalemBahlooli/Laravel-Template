<x-layouts.guest :title="__('Create an account')">
    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Create an account') }}</h1>
    <p class="mt-1 text-sm text-slate-500">{{ __('Already have one?') }} <a href="{{ route('login') }}" class="font-medium text-brand-700 hover:underline">{{ __('Sign in') }}</a></p>

    <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
        @csrf
        <x-input name="name" :label="__('Full name')" icon="fa-user" required autofocus autocomplete="name" />
        <x-input name="email" type="email" :label="__('Email')" icon="fa-envelope" required autocomplete="email" />
        <x-input name="password" type="password" :label="__('Password')" required autocomplete="new-password" />
        <x-input name="password_confirmation" type="password" :label="__('Repeat the password')" required autocomplete="new-password" />
        <p class="text-xs text-slate-500">@include('auth.partials.password-rules')</p>
        <button type="submit" class="btn btn-primary w-full py-2.5">{{ __('Create my account') }}</button>
    </form>
</x-layouts.guest>
