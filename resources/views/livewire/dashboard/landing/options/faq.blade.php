<studio:card :padding="false" class="space-y-4 !border-0">
    <studio:field :label="__('dashboard.Title')">
        <studio:input size="md" wire:model="faq.title" />
    </studio:field>
</studio:card>

<studio:card :padding="false" class="space-y-4 !border-0">
    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.FAQ') }}</p>
    @foreach ($faq['items'] ?? [] as $index => $item)
        <div class="space-y-3 border-t border-studio-line pt-4 first:border-t-0 first:pt-0">
            <div class="flex justify-end">
                <studio:button type="button" variant="ghost" square size="sm" wire:click="removeFaq({{ $index }})" :aria-label="__('dashboard.Remove')">
                    <studio:icon name="x-mark" class="size-4" />
                </studio:button>
            </div>
            <studio:field :label="__('dashboard.Question')">
                <studio:input size="md" wire:model="faq.items.{{ $index }}.q" />
            </studio:field>
            <studio:field :label="__('dashboard.Answer')">
                <studio:textarea rows="3" wire:model="faq.items.{{ $index }}.a" />
            </studio:field>
        </div>
    @endforeach
    <studio:button type="button" variant="outline" size="sm" wire:click="addFaq">{{ __('dashboard.Add question') }}</studio:button>
</studio:card>
