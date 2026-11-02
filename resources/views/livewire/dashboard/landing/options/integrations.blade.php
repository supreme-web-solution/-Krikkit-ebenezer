<studio:card :padding="false" class="space-y-4 !border-0">
    <studio:callout>
        {{ __('dashboard.Provider marks stay on API Integration. This tab edits the section copy and link.') }}
    </studio:callout>
    <studio:field :label="__('dashboard.Eyebrow')">
        <studio:input size="md" wire:model="integrations.eyebrow" />
    </studio:field>
    <studio:field :label="__('dashboard.Title')">
        <studio:input size="md" wire:model="integrations.title" />
    </studio:field>
    <div class="grid gap-4 sm:grid-cols-2">
        <studio:field :label="__('dashboard.Button')">
            <studio:input size="md" wire:model="integrations.cta_label" />
        </studio:field>
        <studio:field :label="__('dashboard.Link')">
            <studio:input size="md" wire:model="integrations.cta_href" />
            <x-slot:description>{{ $hrefHint }}</x-slot:description>
        </studio:field>
    </div>
</studio:card>
