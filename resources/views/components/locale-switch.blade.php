{{-- Language switch. Languages come from config/settings.php → locales. --}}
@php $locales = config('settings.locales', []); @endphp
@if (count($locales) > 1)
    <x-dropdown align="end" width="w-40">
        <x-slot:trigger>
            <button type="button" class="btn-icon w-auto gap-1.5 px-2.5 text-xs font-semibold uppercase" aria-label="{{ __('Language') }}">
                <i class="fa-solid fa-globe text-[15px]"></i>{{ app()->getLocale() }}
            </button>
        </x-slot:trigger>
        <div class="py-1">
            @foreach ($locales as $code => $locale)
                <form method="POST" action="{{ route('locale.switch') }}">
                    @csrf
                    <input type="hidden" name="locale" value="{{ $code }}">
                    <button type="submit" class="flex w-full items-center justify-between px-4 py-2 text-sm hover:bg-slate-50 {{ app()->getLocale() === $code ? 'font-semibold text-brand-700' : 'text-slate-700' }}">
                        {{ $locale['native'] }}
                        @if (app()->getLocale() === $code)<i class="fa-solid fa-check text-xs"></i>@endif
                    </button>
                </form>
            @endforeach
        </div>
    </x-dropdown>
@endif
