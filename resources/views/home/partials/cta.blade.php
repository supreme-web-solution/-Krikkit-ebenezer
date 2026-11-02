@php
    $cta = $landing->section('cta');
    $guestEntry = $landing->guestEntryHref();
@endphp

<section id="start" class="border-b border-studio-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-studio-line">
        <div class="relative overflow-hidden px-6 py-24 text-center sm:px-10 md:py-32">
            <canvas
                data-ascii-hexdump
                data-ascii-src="{{ $landing->asciiUrl() }}"
                class="absolute inset-0 size-full"
                aria-hidden="true"
            ></canvas>
            <div class="pointer-events-none absolute inset-0 bg-studio-canvas/70"></div>
            <div class="relative z-10">
                <p class="mx-auto max-w-xl text-lg leading-relaxed text-studio-fg sm:text-xl">
                    {{ $cta['copy'] }}
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    @auth
                        <a href="{{ $landing->href($cta['primary_href'] ?? '', route('lab')) }}" class="inline-flex items-center gap-2 bg-accent px-6 py-3 text-sm font-medium text-accent-foreground transition hover:opacity-90">
                            <studio:icon name="lab" class="size-4" />
                            {{ $cta['primary_label'] }}
                        </a>
                        <a href="{{ route('dashboard.home') }}" class="inline-flex items-center border border-studio-line bg-studio-soft px-6 py-3 text-sm font-medium text-studio-fg">{{ __('messages.Dashboard') }}</a>
                    @else
                        <a href="{{ $landing->href($cta['primary_href'] ?? '', $guestEntry) }}" class="inline-flex items-center gap-2 bg-accent px-6 py-3 text-sm font-medium text-accent-foreground transition hover:opacity-90">
                            <studio:icon name="lab" class="size-4" />
                            {{ $cta['primary_label'] }}
                        </a>
                        <a href="{{ $landing->href($cta['guest_secondary_href'] ?? '', route('login')) }}" class="inline-flex items-center border border-studio-line bg-studio-soft px-6 py-3 text-sm font-medium text-studio-fg">{{ $cta['guest_secondary_label'] }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
