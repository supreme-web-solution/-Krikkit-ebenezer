@props([
    'value' => null,
    'placeholder' => 'Pick a date',
    'name' => null,
    'invalid' => false,
])

@php
    $name = $name ?? $attributes->get('name');
    $initial = $value ?? $attributes->get('value');
@endphp

<div
    x-data="studioDateDesk({ value: @js($initial) })"
    @keydown.escape.window="open = false"
    @click.outside="open = false"
    {{ $attributes->only('class')->class('relative w-full') }}
>
    <button
        type="button"
        @click="open = ! open; pane = 'days'"
        class="flex h-10 w-full items-center justify-between gap-2 rounded-full border bg-studio-surface px-4 text-left text-sm outline-none transition
            focus-visible:border-studio-muted
            {{ $invalid ? 'border-red-400/70' : 'border-studio-line focus-visible:border-studio-muted/40' }}"
    >
        <span :class="value ? 'text-studio-fg' : 'text-studio-subtle'" x-text="value ? format(value) : @js($placeholder)"></span>
        <studio:icon name="calendar" class="size-4 shrink-0 text-studio-subtle" />
    </button>

    <input
        x-ref="input"
        type="hidden"
        @if ($name) name="{{ $name }}" @endif
        x-model="value"
        {{ $attributes->whereStartsWith('wire:') }}
    >

    <div
        x-cloak
        x-show="open"
        x-transition.origin.top
        class="absolute z-40 mt-1.5 w-[18.5rem] rounded-lg border border-studio-line bg-studio-surface p-3"
    >
        <div class="mb-2 flex items-center justify-between gap-1">
            <button type="button" class="rounded-lg p-1.5 text-studio-muted hover:bg-studio-soft hover:text-studio-fg" @click="prev()" aria-label="{{ __('messages.Previous month') }}">
                <studio:icon name="chevron-left" class="size-4" />
            </button>
            <div class="flex min-w-0 items-center gap-0.5">
                <button
                    type="button"
                    class="rounded-lg px-1.5 py-1 text-sm font-semibold hover:bg-studio-soft"
                    :class="pane === 'days' ? 'bg-studio-soft text-studio-fg' : 'text-studio-muted'"
                    @click="show('days')"
                >
                    <span x-text="dayStamp()"></span>
                </button>
                <button
                    type="button"
                    class="rounded-lg px-1.5 py-1 text-sm font-semibold hover:bg-studio-soft"
                    :class="pane === 'months' ? 'bg-studio-soft text-studio-fg' : 'text-studio-muted'"
                    @click="show('months')"
                >
                    <span x-text="monthStamp()"></span>
                </button>
                <button
                    type="button"
                    class="rounded-lg px-1.5 py-1 text-sm font-semibold hover:bg-studio-soft"
                    :class="pane === 'years' ? 'bg-studio-soft text-studio-fg' : 'text-studio-muted'"
                    @click="show('years')"
                >
                    <span x-text="cursor.y"></span>
                </button>
            </div>
            <button type="button" class="rounded-lg p-1.5 text-studio-muted hover:bg-studio-soft hover:text-studio-fg" @click="next()" aria-label="{{ __('messages.Next month') }}">
                <studio:icon name="chevron-right" class="size-4" />
            </button>
        </div>

        <div x-show="pane === 'days'">
            <div class="mb-1 grid grid-cols-7 gap-1 text-center text-[11px] font-medium text-studio-muted">
                <template x-for="d in weekdays" :key="d"><span class="py-1" x-text="d"></span></template>
            </div>
            <div class="grid grid-cols-7 gap-1">
                <template x-for="day in days()" :key="day.iso">
                    <button
                        type="button"
                        class="aspect-square rounded-lg text-sm transition"
                        :class="{
                            'bg-studio-fill text-studio-on-fill': value === day.iso,
                            'text-studio-subtle': ! day.inMonth && value !== day.iso,
                            'text-studio-fg hover:bg-studio-soft': day.inMonth && value !== day.iso,
                        }"
                        @click="pick(day.iso)"
                        x-text="day.day"
                    ></button>
                </template>
            </div>
        </div>

        <div x-show="pane === 'months'" class="grid grid-cols-3 gap-1">
            <template x-for="month in months()" :key="month.m">
                <button
                    type="button"
                    class="rounded-lg px-2 py-2 text-sm font-medium hover:bg-studio-soft"
                    :class="cursor.m === month.m ? 'bg-studio-soft text-studio-fg' : 'text-studio-muted'"
                    @click="pickMonth(month.m)"
                    x-text="month.label"
                ></button>
            </template>
        </div>

        <div x-show="pane === 'years'" class="grid grid-cols-3 gap-1">
            <template x-for="year in years()" :key="year">
                <button
                    type="button"
                    class="rounded-lg px-2 py-2 text-sm font-medium hover:bg-studio-soft"
                    :class="cursor.y === year ? 'bg-studio-soft text-studio-fg' : 'text-studio-muted'"
                    @click="pickYear(year)"
                    x-text="year"
                ></button>
            </template>
        </div>
    </div>
</div>
