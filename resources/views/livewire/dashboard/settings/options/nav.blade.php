@php
    $section = $section ?? 'general';
    $nav = $nav ?? [];
@endphp

<nav class="-mx-4 flex gap-1 overflow-x-auto border-b border-studio-line px-4 pb-px sm:-mx-5 sm:px-5 lg:mx-0 lg:flex-wrap lg:overflow-visible lg:px-0" aria-label="{{ __('dashboard.Settings') }}">
    @foreach ($nav as $item)
        @php $active = $section === $item['key']; @endphp
        <a
            href="{{ route($item['route']) }}"
            wire:navigate
            @class([
                '-mb-px shrink-0 border-b px-3 py-2 text-sm font-medium transition',
                'border-studio-fg text-studio-fg' => $active,
                'border-transparent text-studio-muted hover:text-studio-fg' => ! $active,
            ])
        >
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
