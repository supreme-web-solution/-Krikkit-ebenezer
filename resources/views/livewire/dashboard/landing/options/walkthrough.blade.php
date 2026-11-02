<studio:card :padding="false" class="space-y-4 !border-0">
    <studio:field :label="__('dashboard.Eyebrow')">
        <studio:input size="md" wire:model="walkthrough.eyebrow" />
    </studio:field>
    <studio:field :label="__('dashboard.Title')">
        <studio:input size="md" wire:model="walkthrough.title" />
    </studio:field>
    <studio:field :label="__('dashboard.Copy')">
        <studio:textarea rows="3" wire:model="walkthrough.copy" />
    </studio:field>

    @include('livewire.dashboard.settings.options.asset', [
        'label' => __('dashboard.ASCII background'),
        'property' => 'asciiFile',
        'preview' => $asciiPreview,
        'hint' => __('dashboard.PNG, JPG, or WebP. Used behind the walkthrough and the closing CTA.'),
    ])
</studio:card>

<studio:card :padding="false" class="space-y-4 !border-0">
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Tabs') }}</p>
    @foreach ($walkthrough['tabs'] ?? [] as $index => $item)
        <div class="space-y-3 border-t border-studio-line pt-4 first:border-t-0 first:pt-0">
            <div class="flex justify-end">
                <studio:button type="button" variant="ghost" square size="sm" wire:click="removeWalkTab({{ $index }})" :aria-label="__('dashboard.Remove')">
                    <studio:icon name="x-mark" class="size-4" />
                </studio:button>
            </div>
            <studio:field :label="__('dashboard.Label')">
                <studio:input size="md" wire:model="walkthrough.tabs.{{ $index }}.label" />
            </studio:field>
            <studio:field :label="__('dashboard.Title')">
                <studio:input size="md" wire:model="walkthrough.tabs.{{ $index }}.title" />
            </studio:field>
            <studio:field :label="__('dashboard.Copy')">
                <studio:textarea rows="2" wire:model="walkthrough.tabs.{{ $index }}.copy" />
            </studio:field>
            <studio:field :label="__('dashboard.Image URL')">
                <studio:input size="md" wire:model="walkthrough.tabs.{{ $index }}.image" />
                <x-slot:description>{{ __('dashboard.Paste a URL, or upload a file below to replace it.') }}</x-slot:description>
            </studio:field>
            <studio:fileUpload
                accept="image/png,image/jpeg,image/webp"
                :label="__('dashboard.Drop files here or browse')"
                :caption="__('dashboard.PNG, JPG, or WebP')"
                wire:model="walkFiles.{{ $index }}"
            />
        </div>
    @endforeach
    <studio:button type="button" variant="outline" size="sm" wire:click="addWalkTab">{{ __('dashboard.Add item') }}</studio:button>
</studio:card>
