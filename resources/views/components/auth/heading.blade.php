@props([
    'copy' => null,
    'eyebrow' => null,
])

<div {{ $attributes->class('mb-8') }}>
    @if (filled($eyebrow))
        <p class="mb-3 text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ $eyebrow }}</p>
    @endif
    <h1 class="text-2xl font-medium tracking-tight text-studio-fg">{{ $slot }}</h1>
    @if (filled($copy))
        <p class="mt-2 text-sm leading-relaxed text-studio-muted">{{ $copy }}</p>
    @endif
</div>
