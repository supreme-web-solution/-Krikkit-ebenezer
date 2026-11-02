<studio:card :padding="false" class="space-y-4 !border-0">
    <studio:field :label="__('dashboard.Eyebrow')">
        <studio:input size="md" wire:model="hero.eyebrow" />
    </studio:field>
    <studio:field :label="__('dashboard.Title')">
        <studio:input size="md" wire:model="hero.title" :invalid="$errors->has('hero.title')" />
        @error('hero.title')
            <studio:field.error>{{ $message }}</studio:field.error>
        @enderror
    </studio:field>
    <studio:field :label="__('dashboard.Copy')">
        <studio:textarea rows="3" wire:model="hero.copy" />
    </studio:field>
    <studio:field :label="__('dashboard.Footnote')">
        <studio:input size="md" wire:model="hero.footnote" />
    </studio:field>
</studio:card>

<studio:card :padding="false" class="space-y-4 !border-0">
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Buttons') }}</p>
    <p class="text-xs text-studio-muted">{{ $hrefHint }}</p>
    <div class="grid gap-4 sm:grid-cols-2">
        <studio:field :label="__('dashboard.Primary button')">
            <studio:input size="md" wire:model="hero.primary_label" />
        </studio:field>
        <studio:field :label="__('dashboard.Link')">
            <studio:input size="md" wire:model="hero.primary_href" />
        </studio:field>
        <studio:field :label="__('dashboard.Secondary button')">
            <studio:input size="md" wire:model="hero.secondary_label" />
        </studio:field>
        <studio:field :label="__('dashboard.Link')">
            <studio:input size="md" wire:model="hero.secondary_href" />
        </studio:field>
    </div>
</studio:card>
