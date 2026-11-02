<studio:card :padding="false" class="space-y-4 !border-0">
    <studio:field :label="__('dashboard.Copy')">
        <studio:textarea rows="3" wire:model="cta.copy" />
    </studio:field>
    <p class="text-xs text-studio-muted">{{ $hrefHint }}</p>
    <div class="grid gap-4 sm:grid-cols-2">
        <studio:field :label="__('dashboard.Primary button')">
            <studio:input size="md" wire:model="cta.primary_label" />
        </studio:field>
        <studio:field :label="__('dashboard.Link')">
            <studio:input size="md" wire:model="cta.primary_href" />
        </studio:field>
        <studio:field :label="__('dashboard.Guest button')">
            <studio:input size="md" wire:model="cta.guest_secondary_label" />
        </studio:field>
        <studio:field :label="__('dashboard.Link')">
            <studio:input size="md" wire:model="cta.guest_secondary_href" />
        </studio:field>
    </div>
    <p class="text-xs text-studio-muted">{{ __('dashboard.Signed-in visitors see Dashboard as the second action. ASCII background is shared with Walkthrough.') }}</p>
</studio:card>
