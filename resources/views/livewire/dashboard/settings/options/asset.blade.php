@props([
    'label',
    'hint' => null,
    'property',
    'preview' => null,
    'accept' => 'image/png,image/jpeg,image/webp,image/svg+xml',
])

<div class="space-y-1.5">
    <div class="flex items-center justify-between gap-3">
        <studio:field.label>{{ $label }}</studio:field.label>
        @if ($preview)
            <studio:button type="button" variant="ghost" size="sm" wire:click="dropAsset('{{ $property }}')">
                {{ __('dashboard.Remove') }}
            </studio:button>
        @endif
    </div>
    <studio:fileUpload
        :accept="$accept"
        :label="__('dashboard.Drop files here or browse')"
        :caption="$hint ?? __('dashboard.PNG, JPG, SVG…')"
        :preview="$preview"
        wire:model="{{ $property }}"
    />
    @error($property)
        <studio:field.error>{{ $message }}</studio:field.error>
    @enderror
</div>
