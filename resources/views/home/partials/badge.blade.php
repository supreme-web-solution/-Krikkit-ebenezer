@props([
    'label' => null,
])

<span {{ $attributes->class('inline-flex items-center gap-2 border border-studio-line bg-studio-soft/50 px-3 py-1.5') }}>
    <span class="size-1.5 shrink-0 bg-accent"></span>
    <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-studio-muted">
        {{ $label ?? $slot }}
    </span>
</span>
