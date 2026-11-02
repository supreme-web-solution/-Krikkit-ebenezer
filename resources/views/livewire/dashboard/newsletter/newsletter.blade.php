<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Newsletter') }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Emails collected from the public home footer.') }}</p>
        </div>
        @if ($hasSubscribers)
            <studio:dropdown align="end">
                <x-slot:trigger>
                    <studio:button
                        type="button"
                        variant="ghost"
                        square
                        size="sm"
                        aria-label="{{ __('dashboard.Export') }}"
                        title="{{ __('dashboard.Export') }}"
                    >
                        <studio:icon name="arrow-down-tray" class="size-4" />
                    </studio:button>
                </x-slot:trigger>
                <x-slot:menu>
                    <studio:dropdown.item wire:click="export('csv')" x-on:click="open = false">
                        {{ __('dashboard.CSV') }}
                    </studio:dropdown.item>
                    <studio:dropdown.item wire:click="export('tsv')" x-on:click="open = false">
                        {{ __('dashboard.TSV') }}
                    </studio:dropdown.item>
                    <studio:dropdown.item wire:click="export('json')" x-on:click="open = false">
                        {{ __('dashboard.JSON') }}
                    </studio:dropdown.item>
                </x-slot:menu>
            </studio:dropdown>
        @endif
    </div>

    <div class="max-w-sm">
        <studio:input size="sm" wire:model.live.debounce.300ms="search" :placeholder="__('dashboard.Search…')" />
    </div>

    @if ($rows->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No newsletter subscribers yet.') }}</p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Email') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Joined') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($rows as $row)
                    <studio:table.row :key="$row->public_id">
                        <studio:table.cell>{{ $row->email }}</studio:table.cell>
                        <studio:table.cell>{{ $row->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</studio:table.cell>
                        <studio:table.cell align="right">
                            <studio:button type="button" size="sm" variant="ghost" wire:click="askRemove('{{ $row->public_id }}')">
                                {{ __('dashboard.Remove') }}
                            </studio:button>
                        </studio:table.cell>
                    </studio:table.row>
                @endforeach
            </studio:table.rows>
        </studio:table>
        <div class="mt-4">
            {{ $rows->links() }}
        </div>
    @endif

    <studio:confirm name="remove-subscriber" :title="__('dashboard.Remove subscriber')" :copy="__('dashboard.Remove this email from the list?')">
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="confirmPending">
                {{ __('dashboard.Remove') }}
            </studio:button>
        </x-slot:action>
    </studio:confirm>
</div>
