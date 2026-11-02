<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Packs') }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Create, lock, and tune workspace packs.') }}</p>
        </div>
        @allows('packs.compose')
            <studio:button
                href="{{ route('dashboard.packs.create') }}"
                variant="ghost"
                square
                size="sm"
                aria-label="{{ __('dashboard.New pack') }}"
                title="{{ __('dashboard.New pack') }}"
            >
                <studio:icon name="plus" class="size-4" />
            </studio:button>
        @endallows
    </div>

    @error('lock')
        <studio:callout tone="danger">{{ $message }}</studio:callout>
    @enderror
    @error('retire')
        <studio:callout tone="danger">{{ $message }}</studio:callout>
    @enderror

    @if ($packs->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No packs yet.') }}</p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Pack') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Summary') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Pricing') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Holders') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Slug') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($packs as $pack)
                    <studio:table.row>
                        <studio:table.cell>
                            <div class="flex min-w-0 items-center gap-2">
                                <p class="truncate text-xs font-medium text-studio-fg">{{ $pack->title }}</p>
                                @if ($pack->is_default)
                                    <studio:badge size="xs">{{ __('dashboard.Default') }}</studio:badge>
                                @endif
                            </div>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-fg-soft">{{ $pack->summary ?: '—' }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="tabular-nums text-studio-muted">{{ $pack->priceLine() }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $pack->entitlements_count }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $pack->slug }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            @if ($pack->isLocked())
                                <studio:badge color="red" size="xs">{{ __('dashboard.Locked') }}</studio:badge>
                            @else
                                <studio:badge color="teal" size="xs">{{ __('dashboard.Active') }}</studio:badge>
                            @endif
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <div class="inline-flex items-center gap-1">
                                @allows('packs.revise')
                                    <studio:button
                                        href="{{ route('dashboard.packs.edit', $pack) }}"
                                        variant="ghost"
                                        square
                                        size="sm"
                                        aria-label="{{ __('dashboard.Edit') }}"
                                        title="{{ __('dashboard.Edit') }}"
                                    >
                                        <studio:icon name="pencil" class="size-4" />
                                    </studio:button>
                                    @unless ($pack->is_default && $pack->is_active)
                                        <studio:button
                                            type="button"
                                            variant="ghost"
                                            square
                                            size="sm"
                                            wire:click="askLock('{{ $pack->public_id }}')"
                                            aria-label="{{ $pack->isLocked() ? __('dashboard.Unlock') : __('dashboard.Lock') }}"
                                            title="{{ $pack->isLocked() ? __('dashboard.Unlock') : __('dashboard.Lock') }}"
                                        >
                                            <studio:icon :name="$pack->isLocked() ? 'lock-open' : 'lock-closed'" class="size-4" />
                                        </studio:button>
                                    @endunless
                                @endallows
                                @allows('packs.retire')
                                    @unless ($pack->isBuiltIn() || $pack->is_default)
                                        <studio:button
                                            type="button"
                                            variant="ghost"
                                            square
                                            size="sm"
                                            wire:click="askRetire('{{ $pack->public_id }}')"
                                            aria-label="{{ __('dashboard.Retire') }}"
                                            title="{{ __('dashboard.Retire') }}"
                                        >
                                            <studio:icon name="trash" class="size-4" />
                                        </studio:button>
                                    @endunless
                                @endallows
                            </div>
                        </studio:table.cell>
                    </studio:table.row>
                @endforeach
            </studio:table.rows>
        </studio:table>
    @endif

    <studio:confirm
        name="pack-confirm"
        :title="$confirmAction === 'retire' ? __('dashboard.Retire pack') : __('dashboard.Lock pack')"
        :copy="$confirmAction === 'retire' ? __('dashboard.Retire this pack?') : __('dashboard.Lock this pack? Holders keep it; new assignments stop.')"
    >
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="confirmPending">
                {{ $confirmAction === 'retire' ? __('dashboard.Retire') : __('dashboard.Lock') }}
            </studio:button>
        </x-slot:action>
    </studio:confirm>
</div>
