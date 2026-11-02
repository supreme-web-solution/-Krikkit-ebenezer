<studio:card :padding="false" class="space-y-4 !border-0">
    <studio:field :label="__('dashboard.Blurb')">
        <studio:textarea rows="3" wire:model="footer.blurb" />
    </studio:field>
    <div class="grid gap-4 sm:grid-cols-2">
        <studio:field :label="__('dashboard.Subscribe placeholder')">
            <studio:input size="md" wire:model="footer.subscribe_placeholder" />
        </studio:field>
        <studio:field :label="__('dashboard.Subscribe label')">
            <studio:input size="md" wire:model="footer.subscribe_label" />
        </studio:field>
    </div>
</studio:card>

@foreach ($footer['columns'] ?? [] as $column => $col)
    <studio:card :padding="false" class="space-y-4 !border-0">
        <studio:field :label="__('dashboard.Column')">
            <studio:input size="md" wire:model="footer.columns.{{ $column }}.heading" />
        </studio:field>
        <p class="text-xs text-studio-muted">{{ $hrefHint }}</p>
        @foreach ($col['links'] ?? [] as $index => $link)
            <div class="grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
                <studio:field :label="__('dashboard.Label')">
                    <studio:input size="md" wire:model="footer.columns.{{ $column }}.links.{{ $index }}.label" />
                </studio:field>
                <studio:field :label="__('dashboard.Link')">
                    <studio:input size="md" wire:model="footer.columns.{{ $column }}.links.{{ $index }}.href" />
                </studio:field>
                <div class="flex items-end">
                    <studio:button type="button" variant="ghost" square size="sm" wire:click="removeFooterLink({{ $column }}, {{ $index }})" :aria-label="__('dashboard.Remove')">
                        <studio:icon name="x-mark" class="size-4" />
                    </studio:button>
                </div>
            </div>
        @endforeach
        <studio:button type="button" variant="outline" size="sm" wire:click="addFooterLink({{ $column }})">{{ __('dashboard.Add link') }}</studio:button>
    </studio:card>
@endforeach

<studio:card :padding="false" class="space-y-4 !border-0">
    <studio:field :label="__('dashboard.Legal heading')">
        <studio:input size="md" wire:model="footer.legal_heading" />
    </studio:field>
    <p class="text-xs text-studio-muted">{{ __('dashboard.Privacy and Terms appear automatically when those pages are published.') }}</p>
    @foreach ($footer['legal_extra'] ?? [] as $index => $item)
        <div class="flex items-end gap-3">
            <studio:field class="flex-1" :label="__('dashboard.Label')">
                <studio:input size="md" wire:model="footer.legal_extra.{{ $index }}" />
            </studio:field>
            <studio:button type="button" variant="ghost" square size="sm" wire:click="removeLegalExtra({{ $index }})" :aria-label="__('dashboard.Remove')">
                <studio:icon name="x-mark" class="size-4" />
            </studio:button>
        </div>
    @endforeach
    <studio:button type="button" variant="outline" size="sm" wire:click="addLegalExtra">{{ __('dashboard.Add item') }}</studio:button>
</studio:card>

<studio:card :padding="false" class="space-y-4 !border-0">
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Social') }}</p>
    @foreach ($footer['social'] ?? [] as $index => $item)
        <div class="grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
            <studio:field :label="__('dashboard.Label')">
                <studio:input size="md" wire:model="footer.social.{{ $index }}.label" />
            </studio:field>
            <studio:field :label="__('dashboard.Link')">
                <studio:input size="md" wire:model="footer.social.{{ $index }}.href" />
            </studio:field>
            <div class="flex items-end">
                <studio:button type="button" variant="ghost" square size="sm" wire:click="removeSocial({{ $index }})" :aria-label="__('dashboard.Remove')">
                    <studio:icon name="x-mark" class="size-4" />
                </studio:button>
            </div>
        </div>
    @endforeach
    <studio:button type="button" variant="outline" size="sm" wire:click="addSocial">{{ __('dashboard.Add link') }}</studio:button>
</studio:card>
