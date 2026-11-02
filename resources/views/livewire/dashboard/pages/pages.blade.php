<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Pages') }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Standalone public pages with their own URL.') }}</p>
        </div>
        @allows('pages.compose')
            <studio:button
                href="{{ route('dashboard.pages.create') }}"
                variant="ghost"
                square
                size="sm"
                aria-label="{{ __('dashboard.New page') }}"
            >
                <studio:icon name="plus" class="size-4" />
            </studio:button>
        @endallows
    </div>

    @if ($pages->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No pages yet.') }}</p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Title') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Slug') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Last updated') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($pages as $page)
                    <studio:table.row>
                        <studio:table.cell>
                            <p class="truncate text-xs font-medium text-studio-fg">{{ $page->title }}</p>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $page->slug }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            @if ($page->isReleased())
                                <studio:badge color="teal" size="xs">{{ __('dashboard.Published') }}</studio:badge>
                            @else
                                <studio:badge size="xs">{{ __('dashboard.Draft') }}</studio:badge>
                            @endif
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $page->updated_at?->toFormattedDateString() }}</span>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <div class="inline-flex items-center gap-1">
                                @allows('pages.revise')
                                    <studio:button
                                        href="{{ route('dashboard.pages.edit', $page) }}"
                                        variant="ghost"
                                        square
                                        size="sm"
                                        aria-label="{{ __('dashboard.Edit') }}"
                                    >
                                        <studio:icon name="pencil" class="size-4" />
                                    </studio:button>
                                @endallows
                                @allows('pages.retire')
                                    <studio:button
                                        type="button"
                                        variant="ghost"
                                        square
                                        size="sm"
                                        wire:click="askRetire('{{ $page->public_id }}')"
                                        aria-label="{{ __('dashboard.Retire') }}"
                                    >
                                        <studio:icon name="trash" class="size-4" />
                                    </studio:button>
                                @endallows
                            </div>
                        </studio:table.cell>
                    </studio:table.row>
                @endforeach
            </studio:table.rows>
        </studio:table>
    @endif

    <studio:confirm name="retire-page" :title="__('dashboard.Retire page')" :copy="__('dashboard.Retire this page?')">
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="confirmPending">
                {{ __('dashboard.Retire') }}
            </studio:button>
        </x-slot:action>
    </studio:confirm>
</div>
