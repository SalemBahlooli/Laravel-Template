<x-layouts.guest :title="__('Link an authenticator app')" wide>
    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ $relink ? __('Link a new phone') : __('Link an authenticator app') }}</h1>
    <p class="mt-1 text-sm text-slate-500">
        @if ($required)
            {{ __('Two-factor authentication is required on this system. It takes a minute.') }}
        @else
            {{ __('After this, signing in asks for a code from your phone as well as your password.') }}
        @endif
    </p>

    <ol class="mt-6 space-y-5">
        <li class="flex gap-3">
            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-brand-600 text-xs font-bold text-white">1</span>
            <div class="text-sm text-slate-600">{{ __('Install an authenticator app: Microsoft Authenticator, Google Authenticator, 1Password or similar.') }}</div>
        </li>
        <li class="flex gap-3">
            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-brand-600 text-xs font-bold text-white">2</span>
            <div class="min-w-0 flex-1 text-sm text-slate-600">
                {{ __('In the app, add an account and scan this QR code:') }}
                <div class="mt-3 flex flex-col items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-start">
                    <div class="shrink-0 rounded-lg bg-white p-1 [&_svg]:size-44">{!! $qr !!}</div>
                    <div class="min-w-0 text-xs text-slate-500" x-data="copy('{{ str_replace(' ', '', $secret) }}')">
                        {{ __("Can't scan? Type this key instead:") }}
                        <div class="mt-2 flex items-center gap-2">
                            <code class="rounded-md bg-slate-100 px-2 py-1.5 font-mono text-[13px] break-all text-slate-800">{{ $secret }}</code>
                            <button type="button" class="btn-icon size-8 shrink-0" @click="copy()" :title="copied ? '{{ __('Copied') }}' : '{{ __('Copy') }}'">
                                <i class="fa-regular" :class="copied ? 'fa-circle-check text-green-600' : 'fa-copy'"></i>
                            </button>
                        </div>
                        <div class="mt-2">{{ __('Account: :email', ['email' => auth()->user()->email]) }}</div>
                    </div>
                </div>
            </div>
        </li>
        <li class="flex gap-3">
            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-brand-600 text-xs font-bold text-white">3</span>
            <div class="min-w-0 flex-1 text-sm text-slate-600">
                {{ __('Type the 6-digit code the app shows:') }}
                <form method="POST" action="{{ route('two-factor.confirm') }}" class="mt-3 flex gap-2">
                    @csrf
                    <input name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="7" required autofocus placeholder="123 456"
                           class="input text-center font-mono text-lg tracking-[0.4em]">
                    <button type="submit" class="btn btn-primary shrink-0">{{ __('Link') }}</button>
                </form>
            </div>
        </li>
    </ol>

    <div class="mt-8 flex items-center justify-between text-sm">
        @if (! $required)
            <a href="{{ route('account.security') }}" class="text-slate-500 hover:text-slate-700 hover:underline">{{ __('Cancel') }}</a>
        @else
            <span></span>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-slate-500 hover:text-slate-700 hover:underline">{{ __('Sign out') }}</button>
        </form>
    </div>
</x-layouts.guest>
