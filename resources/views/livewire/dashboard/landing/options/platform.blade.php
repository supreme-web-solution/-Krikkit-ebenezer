<studio:card :padding="false" class="space-y-4 !border-0">
    <studio:field :label="__('dashboard.Eyebrow')">
        <studio:input size="md" wire:model="platform.eyebrow" />
    </studio:field>
    <studio:field :label="__('dashboard.Title')">
        <studio:input size="md" wire:model="platform.title" />
    </studio:field>
    <studio:field :label="__('dashboard.Copy')">
        <studio:textarea rows="3" wire:model="platform.copy" />
    </studio:field>
</studio:card>

<studio:card :padding="false" class="space-y-4 !border-0">
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Layers') }}</p>
    @foreach ($platform['layers'] ?? [] as $index => $layer)
        <div class="space-y-3 border-t border-studio-line pt-4 first:border-t-0 first:pt-0">
            <div class="flex justify-end">
                <studio:button type="button" variant="ghost" square size="sm" wire:click="removeLayer({{ $index }})" :aria-label="__('dashboard.Remove')">
                    <studio:icon name="x-mark" class="size-4" />
                </studio:button>
            </div>
            <studio:field :label="__('dashboard.Title')">
                <studio:input size="md" wire:model="platform.layers.{{ $index }}.title" />
            </studio:field>
            <studio:field :label="__('dashboard.Copy')">
                <studio:textarea rows="2" wire:model="platform.layers.{{ $index }}.copy" />
            </studio:field>
        </div>
    @endforeach
    <studio:button type="button" variant="outline" size="sm" wire:click="addLayer">{{ __('dashboard.Add item') }}</studio:button>
</studio:card>

<studio:card :padding="false" class="space-y-4 !border-0">
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Quote') }}</p>
    <studio:field :label="__('dashboard.Quote')">
        <studio:textarea rows="3" wire:model="platform.quote" />
    </studio:field>
    <div class="grid gap-4 sm:grid-cols-2">
        <studio:field :label="__('dashboard.Name')">
            <studio:input size="md" wire:model="platform.quote_name" />
        </studio:field>
        <studio:field :label="__('dashboard.Role')">
            <studio:input size="md" wire:model="platform.quote_role" />
        </studio:field>
    </div>
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Stats') }}</p>
    @foreach ($platform['stats'] ?? [] as $index => $stat)
        <div class="flex items-end gap-3">
            <studio:field class="flex-1" :label="__('dashboard.Stat')">
                <studio:input size="md" wire:model="platform.stats.{{ $index }}" />
            </studio:field>
            <studio:button type="button" variant="ghost" square size="sm" wire:click="removeStat({{ $index }})" :aria-label="__('dashboard.Remove')">
                <studio:icon name="x-mark" class="size-4" />
            </studio:button>
        </div>
    @endforeach
    <studio:button type="button" variant="outline" size="sm" wire:click="addStat">{{ __('dashboard.Add item') }}</studio:button>
</studio:card>
