@props(['href' => '#', 'icon' => null])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50']) }}>
    @if ($icon)<i class="fa-solid {{ $icon }} w-4 text-center text-slate-400"></i>@endif
    {{ $slot }}
</a>
