{{--
    Layout for pages before sign-in (login, reset password, two-factor).
    <x-layouts.guest :title="__('Sign in')"> ... </x-layouts.guest>
--}}
@props(['title' => null, 'wide' => false])
@php
    $appName = setting('app_name', config('app.name'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" style="--brand: {{ setting('primary_color', '#2563eb') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? strip_tags($title).' · ' : '' }}{{ $appName }}</title>
    @if (setting('favicon'))
        <link rel="icon" href="{{ Storage::disk('public')->url(setting('favicon')) }}">
    @endif
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">
<div class="grid min-h-screen lg:grid-cols-[1fr_minmax(28rem,36rem)]">

    {{-- Brand panel --}}
    <div class="relative hidden overflow-hidden bg-brand-700 lg:block">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,.18),transparent_45%),radial-gradient(circle_at_80%_70%,rgba(0,0,0,.25),transparent_50%)]"></div>
        <div class="absolute -end-24 -bottom-24 size-[28rem] rounded-full border-[3rem] border-white/5"></div>
        <div class="absolute -start-16 top-1/3 size-64 rounded-full border-[2rem] border-white/5"></div>
        <div class="relative flex h-full flex-col justify-between p-12 text-white">
            <div class="flex items-center gap-3">
                @if (setting('logo'))
                    <img src="{{ Storage::disk('public')->url(setting('logo')) }}" alt="" class="h-11 w-11 rounded-xl bg-white object-contain p-1">
                @else
                    <span class="flex size-11 items-center justify-center rounded-xl bg-white/15 text-lg font-bold">{{ mb_strtoupper(mb_substr($appName, 0, 1)) }}</span>
                @endif
                <span class="text-lg font-semibold">{{ $appName }}</span>
            </div>
            <div class="max-w-md">
                <h2 class="text-3xl leading-tight font-semibold">{{ __(setting('login_title', 'Welcome back')) }}</h2>
                <p class="mt-3 text-base text-white/75">{{ __(setting('login_subtitle', 'Sign in to continue to your workspace.')) }}</p>
            </div>
            <div class="text-sm text-white/60">
                {{ setting('company_name') ?: $appName }}
                @if (setting('support_email'))
                    · <a href="mailto:{{ setting('support_email') }}" class="underline hover:text-white">{{ setting('support_email') }}</a>
                @endif
            </div>
        </div>
    </div>

    {{-- Form panel --}}
    <div class="flex flex-col bg-white">
        <div class="flex items-center justify-between p-5">
            <div class="flex items-center gap-2.5 lg:invisible">
                @if (setting('logo'))
                    <img src="{{ Storage::disk('public')->url(setting('logo')) }}" alt="" class="h-9 w-9 rounded-lg object-contain">
                @else
                    <span class="flex size-9 items-center justify-center rounded-lg bg-brand-600 font-bold text-white">{{ mb_strtoupper(mb_substr($appName, 0, 1)) }}</span>
                @endif
                <span class="font-semibold text-slate-900">{{ $appName }}</span>
            </div>
            <x-locale-switch />
        </div>

        <div class="flex flex-1 items-center justify-center px-6 pb-12 sm:px-12">
            <div class="w-full {{ $wide ? 'max-w-lg' : 'max-w-sm' }}">
                <x-flash class="mb-5" />
                {{ $slot }}
            </div>
        </div>

        <div class="p-5 text-center text-xs text-slate-400">
            {{ setting('footer_text') ?: '© '.date('Y').' '.(setting('company_name') ?: $appName) }}
        </div>
    </div>
</div>
</body>
</html>
