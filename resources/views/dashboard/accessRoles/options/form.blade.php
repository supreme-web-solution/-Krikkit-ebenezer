@props([
    'role' => null,
    'clusters',
    'selected' => [],
])

@php
    $title = old('title', $role?->title);
    $summary = old('summary', $role?->summary);
@endphp

<div class="space-y-4">
    <studio:card class="space-y-4 !border-0 !p-5">
        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('dashboard.Identity') }}</p>

        <studio:field :label="__('dashboard.Title')">
            <studio:input name="title" id="title" :value="$title" :placeholder="__('dashboard.Editor')" :invalid="$errors->has('title')" required />
            @error('title')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>

        <studio:field :label="__('dashboard.Summary')">
            <studio:input name="summary" id="summary" :value="$summary" :placeholder="__('dashboard.Can revise content')" :invalid="$errors->has('summary')" />
            @error('summary')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>
    </studio:card>

    <div class="space-y-4">
        <p class="px-5 text-xs font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('dashboard.Abilities') }}</p>
        <x-access.ability-picker :clusters="$clusters" :selected="$selected" />
    </div>
</div>
