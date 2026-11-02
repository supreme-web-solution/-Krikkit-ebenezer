@props([
    'month' => null,
    'year' => null,
    'selected' => null,
])

@php
    $month = (int) ($month ?? now()->month);
    $year = (int) ($year ?? now()->year);
    $start = \Carbon\Carbon::create($year, $month, 1)->startOfWeek(\Carbon\Carbon::MONDAY);
    $end = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
    $days = [];
    for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
        $days[] = $d->copy();
    }
    $selectedDate = $selected ? \Carbon\Carbon::parse($selected)->toDateString() : null;
    $label = \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y');
@endphp

<div
    x-data="{ selected: @js($selectedDate) }"
    {{ $attributes->class('w-full max-w-sm rounded-xl bg-studio-surface p-3') }}
>
    <div class="mb-3 flex items-center justify-between px-1">
        <p class="text-sm font-semibold text-studio-fg">{{ $label }}</p>
    </div>
    <div class="mb-1 grid grid-cols-7 gap-1 text-center text-[11px] font-medium text-studio-muted">
        @foreach (['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'] as $dow)
            <span class="py-1">{{ $dow }}</span>
        @endforeach
    </div>
    <div class="grid grid-cols-7 gap-1">
        @foreach ($days as $day)
            @php
                $inMonth = $day->month === $month;
                $iso = $day->toDateString();
            @endphp
            <button
                type="button"
                @click="selected = @js($iso); $dispatch('studio-day', @js($iso))"
                class="aspect-square rounded-lg text-sm transition"
                :class="{
                    'bg-studio-fill text-studio-on-fill': selected === @js($iso),
                    'text-studio-subtle': @js(! $inMonth) && selected !== @js($iso),
                    'text-studio-fg hover:bg-studio-soft': @js($inMonth) && selected !== @js($iso),
                }"
            >
                {{ $day->day }}
            </button>
        @endforeach
    </div>
</div>
