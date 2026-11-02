<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Settings') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">
            {{ __('dashboard.Name and status lines shown in the Lab terminal while the preview runtime starts.') }}
        </p>
    </div>

    @include('livewire.dashboard.settings.options.nav')

    <form wire:submit="save" class="space-y-10">
        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Console name') }}</p>

            <studio:field :label="__('dashboard.Runtime name')">
                <studio:input size="md" wire:model="runtimeName" :placeholder="__('dashboard.DeepThought')" :invalid="$errors->has('runtimeName')" />
                <x-slot:description>
                    {{ __('dashboard.Replaces DeepThought on waiting / install / auto-start lines. Use {name} in the templates below.') }}
                </x-slot:description>
                @error('runtimeName')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </studio:card>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Status lines') }}</p>
            <p class="text-sm leading-relaxed text-studio-muted">
                {{ __('dashboard.Leave a line empty to keep the default. Tokens: {name}, {command}, {code}, {error}.') }}
            </p>

            @foreach ($statusFields as [$field, $label, $placeholder])
                <studio:field :label="$label">
                    <studio:input size="md" wire:model="{{ $field }}" :placeholder="$placeholder" :invalid="$errors->has($field)" />
                    @error($field)
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            @endforeach
        </studio:card>

        <div class="flex justify-end">
            <studio:button type="submit" wire:loading.attr="disabled">
                {{ __('dashboard.Save changes') }}
            </studio:button>
        </div>
    </form>
</div>
