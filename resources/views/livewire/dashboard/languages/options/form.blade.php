@props([
    'lockedCode' => false,
    'lockedDefault' => false,
])

<form wire:submit="save" class="space-y-8">
    <studio:card class="space-y-4 !border-0 !p-5">
        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Identity') }}</p>

        <div class="grid gap-4 sm:grid-cols-2">
            <studio:field :label="__('dashboard.Name')">
                <studio:input size="md" wire:model="name" :placeholder="__('dashboard.French')" :invalid="$errors->has('name')" />
                @error('name')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Native name')">
                <studio:input size="md" wire:model="nativeName" :placeholder="__('dashboard.French')" :invalid="$errors->has('nativeName')" />
                @error('nativeName')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <studio:field :label="__('dashboard.Code')">
                <studio:input
                    size="md"
                    wire:model="code"
                    :placeholder="__('dashboard.fr')"
                    :invalid="$errors->has('code')"
                    :disabled="$lockedCode"
                />
                <x-slot:description>{{ __('dashboard.Two-letter locale, optionally with a region (fr, en-GB).') }}</x-slot:description>
                @error('code')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Direction')">
                <studio:select size="md" wire:model="direction" :value="$direction" :invalid="$errors->has('direction')">
                    <studio:select.option value="ltr" :selected="$direction === 'ltr'">{{ __('dashboard.Left to right') }}</studio:select.option>
                    <studio:select.option value="rtl" :selected="$direction === 'rtl'">{{ __('dashboard.Right to left') }}</studio:select.option>
                </studio:select>
                @error('direction')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </div>

        <div class="grid gap-4 pt-1 sm:grid-cols-2">
            <div class="space-y-1.5">
                <studio:switch wire:model="isDefault" :label="__('dashboard.Default language')" :disabled="$lockedDefault" />
                <p class="text-xs text-studio-muted">{{ __('dashboard.Used when a visitor has no matching locale.') }}</p>
                @error('isDefault')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </div>

            <div class="space-y-1.5">
                <studio:switch wire:model="enabled" :label="__('dashboard.Enabled')" :disabled="$lockedDefault && $isDefault" />
                <p class="text-xs text-studio-muted">{{ __('dashboard.Disabled languages stay in the list but cannot be selected.') }}</p>
                @error('enabled')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </div>
        </div>
    </studio:card>

    <div class="flex justify-end">
        <studio:button type="submit" wire:loading.attr="disabled">
            {{ __('dashboard.Save changes') }}
        </studio:button>
    </div>
</form>
