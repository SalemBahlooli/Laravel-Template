{{--
    The signed-in layout: sidebar (config/menu.php), top bar, flash messages.

    <x-layouts.app :title="__('Users')">
        <x-slot:actions> ...buttons on the right of the page title... </x-slot:actions>
        ...page...
    </x-layouts.app>

    Optional: :breadcrumbs="[__('Users') => route('users.index'), $user->name => null]"
              :subtitle="'One line under the title'"
--}}
@props(['title' => null, 'subtitle' => null, 'breadcrumbs' => [], 'wide' => false])
@php
    $user = auth()->user();
    $appName = setting('app_name', config('app.name'));
    $menu = \App\Support\Menu::for($user);
    $sidebar = setting('sidebar_style', 'dark');
    $sidebarClasses = [
        'dark'  => 'bg-slate-900 text-slate-300',
        'light' => 'bg-white text-slate-600 border-e border-slate-200',
        'brand' => 'bg-brand-800 text-brand-100',
    ][$sidebar] ?? 'bg-slate-900 text-slate-300';
    $linkIdle = ['dark' => 'text-slate-300 hover:bg-white/5 hover:text-white', 'light' => 'text-slate-600 hover:bg-slate-100 hover:text-slate-900', 'brand' => 'text-brand-100 hover:bg-white/10 hover:text-white'][$sidebar] ?? '';
    $linkActive = ['dark' => 'bg-white/10 text-white', 'light' => 'bg-brand-50 text-brand-700', 'brand' => 'bg-white/15 text-white'][$sidebar] ?? '';
    $headingClass = $sidebar === 'light' ? 'text-slate-400' : 'text-white/40';
    $newDevice = \App\Services\SignInLog::pullNotice();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" style="--brand: {{ setting('primary_color', '#2563eb') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="heartbeat-url" content="{{ route('heartbeat') }}">
    <title>{{ $title ? strip_tags($title).' · ' : '' }}{{ $appName }}</title>
    @if (setting('favicon'))
        <link rel="icon" href="{{ Storage::disk('public')->url(setting('favicon')) }}">
    @endif
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen" x-data>

{{-- Mobile backdrop --}}
<div x-cloak x-show="$store.layout.sidebarOpen" x-transition.opacity @click="$store.layout.sidebarOpen = false"
     class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"></div>

{{-- Sidebar --}}
<aside class="{{ $sidebarClasses }} no-print fixed inset-y-0 start-0 z-40 flex w-64 flex-col transition-all duration-200 max-lg:-translate-x-full rtl:max-lg:translate-x-full"
       :class="{ 'max-lg:translate-x-0!': $store.layout.sidebarOpen, 'lg:w-[4.5rem]': $store.layout.collapsed }">
    <div class="flex h-16 shrink-0 items-center gap-3 px-4">
        <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
            @if (setting('logo'))
                <img src="{{ Storage::disk('public')->url(setting('logo')) }}" alt="" class="h-9 w-9 shrink-0 rounded-lg bg-white object-contain p-0.5">
            @else
                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-brand-600 text-sm font-bold text-white">{{ mb_strtoupper(mb_substr($appName, 0, 1)) }}</span>
            @endif
            <span class="truncate text-[15px] font-semibold {{ $sidebar === 'light' ? 'text-slate-900' : 'text-white' }}" x-show="!$store.layout.collapsed || $store.layout.sidebarOpen">{{ $appName }}</span>
        </a>
    </div>

    <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-2">
        @foreach ($menu as $item)
            @if (isset($item['heading']))
                <div class="px-3 pt-5 pb-1.5 text-[11px] font-semibold tracking-wider uppercase {{ $headingClass }}"
                     x-show="!$store.layout.collapsed || $store.layout.sidebarOpen">{{ __($item['heading']) }}</div>
                <div class="mx-3 my-3 border-t border-white/10" x-cloak x-show="$store.layout.collapsed && !$store.layout.sidebarOpen"></div>
            @elseif (! empty($item['children']))
                <div x-data="{ open: {{ $item['active'] ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="nav-link w-full {{ $item['active'] ? $linkActive : $linkIdle }}" title="{{ __($item['label']) }}">
                        <i class="fa-solid {{ $item['icon'] ?? 'fa-circle' }} w-5 text-center text-[15px] opacity-90"></i>
                        <span class="flex-1 truncate text-start" x-show="!$store.layout.collapsed || $store.layout.sidebarOpen">{{ __($item['label']) }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition" :class="open && 'rotate-180'" x-show="!$store.layout.collapsed || $store.layout.sidebarOpen"></i>
                    </button>
                    <div x-show="open" x-collapse class="mt-0.5 space-y-0.5" :class="$store.layout.collapsed && !$store.layout.sidebarOpen ? '' : 'ps-4'">
                        @foreach ($item['children'] as $child)
                            <a href="{{ $child['url'] }}" class="nav-link {{ $child['active'] ? $linkActive : $linkIdle }}" title="{{ __($child['label']) }}">
                                <i class="fa-solid {{ $child['icon'] ?? 'fa-circle' }} w-5 text-center text-[13px] opacity-80"></i>
                                <span class="truncate" x-show="!$store.layout.collapsed || $store.layout.sidebarOpen">{{ __($child['label']) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{ $item['url'] }}" class="nav-link {{ $item['active'] ? $linkActive : $linkIdle }}" title="{{ __($item['label']) }}">
                    <i class="fa-solid {{ $item['icon'] ?? 'fa-circle' }} w-5 text-center text-[15px] opacity-90"></i>
                    <span class="flex-1 truncate" x-show="!$store.layout.collapsed || $store.layout.sidebarOpen">{{ __($item['label']) }}</span>
                    @if (! empty($item['badge']))
                        <span x-cloak x-show="$store.live.badges['{{ $item['badge'] }}'] > 0" x-text="$store.live.badges['{{ $item['badge'] }}']"
                              class="rounded-full bg-red-500 px-1.5 text-[11px] font-semibold text-white"></span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>

    <div class="hidden shrink-0 border-t border-white/10 p-3 lg:block">
        <button type="button" @click="$store.layout.toggleCollapsed()" class="nav-link w-full {{ $linkIdle }}" title="{{ __('Collapse menu') }}">
            <i class="fa-solid fa-angles-left rtl-flip w-5 text-center transition" :class="$store.layout.collapsed && 'rotate-180'"></i>
            <span x-show="!$store.layout.collapsed">{{ __('Collapse menu') }}</span>
        </button>
    </div>
</aside>

{{-- Page --}}
<div class="flex min-h-screen flex-col transition-all duration-200 lg:ps-64" :class="$store.layout.collapsed && 'lg:ps-[4.5rem]'">

    {{-- Top bar --}}
    <header class="no-print sticky top-0 z-20 flex h-16 items-center gap-2 border-b border-slate-200 bg-white/90 px-3 backdrop-blur sm:px-5">
        <button type="button" class="btn-icon lg:hidden" @click="$store.layout.sidebarOpen = true" aria-label="{{ __('Open menu') }}">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="min-w-0 flex-1">
            @if ($breadcrumbs)
                <nav class="flex items-center gap-1.5 truncate text-sm text-slate-500">
                    @foreach ($breadcrumbs as $label => $url)
                        @if ($url)
                            <a href="{{ $url }}" class="truncate hover:text-slate-800">{{ $label }}</a>
                            <i class="fa-solid fa-chevron-right rtl-flip text-[10px] text-slate-300"></i>
                        @else
                            <span class="truncate font-medium text-slate-800">{{ $label }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif
        </div>

        {{-- Online now --}}
        @can('presence.view')
            <a href="{{ route('presence.index') }}" class="hidden items-center gap-2 rounded-full border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 sm:inline-flex" title="{{ __('Online now') }}">
                <span class="relative flex size-2"><span class="absolute inline-flex size-full animate-ping rounded-full bg-green-400 opacity-60"></span><span class="relative inline-flex size-2 rounded-full bg-green-500"></span></span>
                <span x-text="$store.live.online ?? {{ \App\Models\User::where('last_activity_at', '>=', now()->subMinutes(5))->count() }}"></span> {{ __('online') }}
            </a>
        @endcan

        {{-- Language --}}
        <x-locale-switch />

        {{-- Bell --}}
        <div class="relative" x-data="bell('{{ route('notifications.feed') }}', '{{ route('notifications.read-all') }}')" @keydown.escape.window="open = false">
            <button type="button" class="btn-icon relative" @click="toggle()" aria-label="{{ __('Notifications') }}">
                <i class="fa-regular fa-bell text-[17px]"></i>
                <span x-cloak x-show="$store.live.unread > 0" x-text="$store.live.unread > 9 ? '9+' : $store.live.unread"
                      class="absolute -top-0.5 -end-0.5 flex h-4.5 min-w-4.5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white ring-2 ring-white"></span>
            </button>
            <div x-cloak x-show="open" x-transition.origin.top @click.outside="open = false"
                 class="absolute end-0 mt-2 w-[22rem] max-w-[calc(100vw-1.5rem)] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                    <span class="text-sm font-semibold">{{ __('Notifications') }}</span>
                    <button type="button" class="text-xs font-medium text-brand-700 hover:underline" @click="readAll()" x-show="notifications.some(n => n.unread)">{{ __('Mark all as read') }}</button>
                </div>
                <div class="max-h-[26rem] overflow-y-auto">
                    <div x-show="loading" class="p-6 text-center text-sm text-slate-400"><i class="fa-solid fa-spinner fa-spin"></i></div>
                    <template x-for="item in [...announcements, ...notifications]" :key="item.id">
                        <a :href="item.url || '#'" @click="!item.url && $event.preventDefault()"
                           class="flex gap-3 border-b border-slate-50 px-4 py-3 hover:bg-slate-50" :class="item.unread && 'bg-brand-50/50'">
                            <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full"
                                  :class="{ 'bg-blue-100 text-blue-600': item.color === 'blue', 'bg-green-100 text-green-600': item.color === 'green', 'bg-amber-100 text-amber-600': item.color === 'amber', 'bg-red-100 text-red-600': item.color === 'red', 'bg-purple-100 text-purple-600': item.color === 'purple', 'bg-slate-100 text-slate-600': !['blue','green','amber','red','purple'].includes(item.color) }">
                                <i class="fa-solid text-xs" :class="item.icon"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="flex items-center gap-2">
                                    <span class="truncate text-sm font-medium text-slate-800" x-text="item.title"></span>
                                    <span x-show="item.unread" class="size-1.5 shrink-0 rounded-full bg-brand-600"></span>
                                </span>
                                <span class="line-clamp-2 text-xs text-slate-500" x-show="item.body" x-text="item.body"></span>
                                <span class="mt-0.5 block text-[11px] text-slate-400"><span x-text="item.label ? item.label + ' · ' : ''"></span><span x-text="item.ago"></span></span>
                            </span>
                        </a>
                    </template>
                    <div x-show="!loading && announcements.length + notifications.length === 0" class="px-4 py-10 text-center text-sm text-slate-400">
                        <i class="fa-regular fa-bell-slash mb-2 block text-2xl"></i>{{ __('You are all caught up.') }}
                    </div>
                </div>
                <a href="{{ route('notifications.index') }}" class="block border-t border-slate-100 px-4 py-2.5 text-center text-xs font-medium text-slate-600 hover:bg-slate-50">{{ __('See all notifications') }}</a>
            </div>
        </div>

        {{-- User menu --}}
        <x-dropdown align="end" width="w-60">
            <x-slot:trigger>
                <button type="button" class="flex items-center gap-2 rounded-full p-0.5 pe-2 hover:bg-slate-100">
                    <x-avatar :user="$user" size="sm" />
                    <span class="hidden max-w-32 truncate text-sm font-medium text-slate-700 md:block">{{ $user->name }}</span>
                    <i class="fa-solid fa-chevron-down hidden text-[10px] text-slate-400 md:block"></i>
                </button>
            </x-slot:trigger>
            <div class="border-b border-slate-100 px-4 py-3">
                <div class="truncate text-sm font-medium text-slate-900">{{ $user->name }}</div>
                <div class="truncate text-xs text-slate-500">{{ $user->email }}</div>
            </div>
            <div class="py-1">
                <x-dropdown-link :href="route('account.profile')" icon="fa-user">{{ __('My profile') }}</x-dropdown-link>
                <x-dropdown-link :href="route('account.security')" icon="fa-shield-halved">{{ __('Security & sign-ins') }}</x-dropdown-link>
                <x-dropdown-link :href="route('account.password')" icon="fa-key">{{ __('Change password') }}</x-dropdown-link>
                @can('self.api')
                    <x-dropdown-link :href="route('account.tokens')" icon="fa-code">{{ __('API tokens') }}</x-dropdown-link>
                @endcan
                <x-dropdown-link :href="route('notifications.index')" icon="fa-bell">{{ __('Notifications') }}</x-dropdown-link>
            </div>
            <div class="border-t border-slate-100 py-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <i class="fa-solid fa-right-from-bracket rtl-flip w-4 text-center"></i>{{ __('Sign out') }}
                    </button>
                </form>
            </div>
        </x-dropdown>
    </header>

    <main class="flex-1 px-3 py-5 sm:px-6 sm:py-7">
        <div class="{{ $wide ? '' : 'mx-auto max-w-7xl' }}">
            @if ($newDevice)
                <x-alert type="warning" class="mb-5" dismissible>
                    {{ __('New sign-in from :device (IP :ip). If this was not you, change your password.', ['device' => $newDevice['label'], 'ip' => $newDevice['ip']]) }}
                    <a href="{{ route('account.security') }}" class="font-semibold underline">{{ __('Review my devices') }}</a>
                </x-alert>
            @endif

            <x-flash class="mb-5" />

            @if ($title || isset($actions))
                <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                    <div class="min-w-0">
                        @if ($title)<h1 class="text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">{{ $title }}</h1>@endif
                        @if ($subtitle)<p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>@endif
                    </div>
                    @isset($actions)
                        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
                    @endisset
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <footer class="no-print border-t border-slate-200 px-6 py-4 text-xs text-slate-400">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-2">
            <span>{{ setting('footer_text') ?: '© '.date('Y').' '.(setting('company_name') ?: $appName) }}</span>
            <a href="{{ route('changelog.index') }}" class="hover:text-slate-600">{{ __("What's new") }}</a>
        </div>
    </footer>
</div>

@stack('scripts')
</body>
</html>
