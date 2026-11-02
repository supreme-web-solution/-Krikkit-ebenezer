@props(['href' => null, 'current' => false])

@if ($href && ! $current)
    <a href="{{ $href }}" {{ $attributes->class('inline-flex items-center gap-1 hover:text-studio-fg transition') }}>
        {{ $slot }}
        <studio:icon name="chevron-right" class="size-3.5 text-studio-subtle" />
    </a>
@else
    <span {{ $attributes->class(['inline-flex items-center gap-1', $current ? 'font-medium text-studio-fg' : '']) }} @if ($current) aria-current="page" @endif>
        {{ $slot }}
    </span>
@endif
