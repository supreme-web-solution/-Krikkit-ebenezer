<studio:card :padding="false" class="space-y-4 !border-0">
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Navigation links') }}</p>
    <p class="text-xs text-studio-muted">{{ $hrefHint }}</p>

    @foreach ($header['links'] ?? [] as $index => $link)
        <div class="grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
            <studio:field :label="__('dashboard.Label')">
                <studio:input size="md" wire:model="header.links.{{ $index }}.label" />
            </studio:field>
            <studio:field :label="__('dashboard.Link')">
                <studio:input size="md" wire:model="header.links.{{ $index }}.href" />
            </studio:field>
            <div class="flex items-end">
                <studio:button type="button" variant="ghost" square size="sm" wire:click="removeLink('header', {{ $index }})" :aria-label="__('dashboard.Remove')">
                    <studio:icon name="x-mark" class="size-4" />
                </studio:button>
            </div>
        </div>
    @endforeach

    <studio:button type="button" variant="outline" size="sm" wire:click="addLink('header')">{{ __('dashboard.Add link') }}</studio:button>
</studio:card>

<studio:card :padding="false" class="space-y-4 !border-0">
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Buttons') }}</p>

    <div class="grid gap-4 sm:grid-cols-2">
        <studio:field :label="__('dashboard.Guest link')">
            <studio:input size="md" wire:model="header.guest_link_label" />
        </studio:field>
        <studio:field :label="__('dashboard.Link')">
            <studio:input size="md" wire:model="header.guest_link_href" />
        </studio:field>
        <studio:field :label="__('dashboard.Guest button')">
            <studio:input size="md" wire:model="header.guest_cta_label" />
        </studio:field>
        <studio:field :label="__('dashboard.Link')">
            <studio:input size="md" wire:model="header.guest_cta_href" />
        </studio:field>
        <studio:field :label="__('dashboard.Signed-in link')">
            <studio:input size="md" wire:model="header.auth_link_label" :placeholder="__('messages.Dashboard')" />
        </studio:field>
        <studio:field :label="__('dashboard.Signed-in button')">
            <studio:input size="md" wire:model="header.auth_cta_label" :placeholder="__('messages.Open Lab')" />
        </studio:field>
        <studio:field :label="__('dashboard.Mobile button')">
            <studio:input size="md" wire:model="header.mobile_cta_label" />
        </studio:field>
        <studio:field :label="__('dashboard.Link')">
            <studio:input size="md" wire:model="header.mobile_cta_href" />
        </studio:field>
    </div>
</studio:card>
