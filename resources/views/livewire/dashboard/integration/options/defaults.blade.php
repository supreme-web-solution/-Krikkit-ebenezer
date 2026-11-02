<section class="space-y-4">
    <div>
        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Default model') }}</p>
        <p class="mt-1 text-xs text-studio-muted">
            {{ __('dashboard.Used by Lab when the chat picker hasn’t chosen a model yet.') }}
        </p>
    </div>

    <form wire:submit="saveDefaults" class="flex flex-col gap-3 sm:flex-row sm:items-end">
        <studio:field :label="__('dashboard.Model')" class="min-w-0 flex-1">
            <studio:select
                size="md"
                wire:model="defaultModel"
                :value="$defaultModel"
                placeholder="{{ __('dashboard.Choose a model…') }}"
                :invalid="$errors->has('defaultModel')"
            >
                @foreach ($models as $model)
                    <studio:select.option
                        :value="$model->id"
                        :selected="$defaultModel === $model->id"
                    >
                        {{ $model->label }}{{ $model->available ? '' : __('dashboard. — key missing') }}
                    </studio:select.option>
                @endforeach
            </studio:select>
            @error('defaultModel')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>

        <studio:button type="submit" size="md" wire:loading.attr="disabled">
            {{ __('dashboard.Save default') }}
        </studio:button>
    </form>
</section>
