@if ($packOffer)
    <a href="{{ $packOffer['href'] }}" class="group relative block">
        <span
            class="pointer-events-none absolute -inset-4 bg-[radial-gradient(ellipse_at_center,color-mix(in_oklab,var(--color-accent)_42%,transparent),transparent_72%)] opacity-0 transition-opacity duration-500 group-hover:opacity-70"
            aria-hidden="true"
        ></span>
        <span class="relative flex items-center gap-3 rounded-2xl border border-studio-line bg-studio-soft px-3.5 py-3">
            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-semibold tracking-tight text-studio-fg">{{ $packOffer['title'] }}</span>
                <span class="mt-0.5 block truncate text-[13px] leading-snug text-studio-muted">{{ $packOffer['copy'] }}</span>
            </span>
            <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-studio-canvas text-accent-content">
                <studio:icon name="bolt" class="size-[18px]" />
            </span>
        </span>
    </a>
@endif
