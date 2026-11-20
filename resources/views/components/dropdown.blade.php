{{--
    <x-dropdown align="end" width="w-56">
        <x-slot:trigger><button class="btn btn-secondary">Menu</button></x-slot:trigger>
        <x-dropdown-link href="...">Item</x-dropdown-link>
    </x-dropdown>
--}}
@props(['align' => 'end', 'width' => 'w-48'])
<div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
    <div @click="open = !open">{{ $trigger }}</div>
    <div x-cloak x-show="open" x-transition.origin.top
         class="absolute z-50 mt-2 {{ $width }} {{ $align === 'start' ? 'start-0' : 'end-0' }} overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg"
         @click="open = false">
        {{ $slot }}
    </div>
</div>
