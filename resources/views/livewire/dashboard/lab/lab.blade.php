<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="flex items-center gap-2 text-lg font-semibold tracking-tight text-studio-fg">
                <studio:icon name="lab" class="size-5" />
                {{ __('dashboard.Lab') }}
            </h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Every user project, with credits spent on each.') }}</p>
        </div>
        <div
            x-data="{
                open: false,
                expand() {
                    this.open = true
                    this.$nextTick(() => this.$refs.query?.focus())
                },
                collapse() {
                    this.$refs.query?.blur()
                    this.open = false
                    if ($wire.search !== '') {
                        $wire.set('search', '')
                    }
                },
            }"
            x-on:click.outside="if (open) collapse()"
            x-on:keydown.escape.window="if (open) collapse()"
            class="flex items-center"
        >
            <div
                class="flex h-8 items-center overflow-hidden rounded-full border transition-[width,border-color,background-color] duration-300 ease-out"
                :class="open
                    ? 'w-56 border-studio-line/50 bg-studio-surface'
                    : 'w-8 border-transparent bg-transparent'"
            >
                <button
                    type="button"
                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-studio-fg-soft transition hover:bg-studio-soft hover:text-studio-fg"
                    x-on:click.stop="open ? $refs.query?.focus() : expand()"
                    aria-label="{{ __('dashboard.Search') }}"
                    title="{{ __('dashboard.Search') }}"
                >
                    <studio:icon name="search" class="size-4" />
                </button>
                <input
                    x-ref="query"
                    type="text"
                    inputmode="search"
                    autocomplete="off"
                    wire:model.live.debounce.250ms="search"
                    placeholder="{{ __('dashboard.Search…') }}"
                    class="h-8 min-w-0 flex-1 bg-transparent pr-2 text-sm leading-5 text-studio-fg outline-none placeholder:text-studio-subtle"
                    :tabindex="open ? 0 : -1"
                    x-bind:aria-hidden="(! open).toString()"
                />
                <button
                    type="button"
                    class="mr-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-studio-subtle transition hover:bg-studio-soft hover:text-studio-fg"
                    x-show="open"
                    x-cloak
                    x-on:click.stop="collapse()"
                    aria-label="{{ __('dashboard.Clear') }}"
                    title="{{ __('dashboard.Clear') }}"
                >
                    <studio:icon name="x" class="size-3.5" />
                </button>
            </div>
        </div>
    </div>

    @if ($projects->isEmpty())
        <p class="text-sm text-studio-muted">
            {{ filled($search) ? __('dashboard.No results.') : __('dashboard.No Lab projects yet.') }}
        </p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Project') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Owner') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Stack') }}</studio:table.column>
                <studio:table.column align="right">{{ __('dashboard.Credits') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Updated') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($projects as $project)
                    <studio:table.row>
                        <studio:table.cell>
                            <a href="{{ route('dashboard.lab.show', $project) }}" wire:navigate class="min-w-0 block">
                                <p class="truncate text-xs font-medium text-studio-fg">
                                    {{ $project->title ?: __('dashboard.Untitled') }}
                                </p>
                                <p class="mt-0.5 text-[11px] text-studio-muted">
                                    {{ $project->messages_count }} {{ __('dashboard.Messages') }}
                                    @if ($project->isFrozen())
                                        · {{ __('dashboard.Frozen') }}
                                    @endif
                                </p>
                            </a>
                        </studio:table.cell>
                        <studio:table.cell>
                            @if ($project->user)
                                <div class="min-w-0">
                                    <p class="truncate text-xs text-studio-fg">{{ $project->user->name }}</p>
                                    <p class="truncate text-[11px] text-studio-muted">{{ $project->user->email }}</p>
                                </div>
                            @else
                                <span class="text-studio-muted">—</span>
                            @endif
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-xs text-studio-muted">{{ $project->stack ?: '—' }}</span>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <span class="tabular-nums text-studio-fg">{{ number_format((int) $project->credits_spent) }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $project->updated_at?->diffForHumans() }}</span>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <studio:button
                                href="{{ route('lab.show', $project) }}"
                                variant="ghost"
                                square
                                size="sm"
                                aria-label="{{ __('dashboard.Open in Lab') }}"
                                title="{{ __('dashboard.Open in Lab') }}"
                            >
                                <studio:icon name="lab" class="size-4" />
                            </studio:button>
                        </studio:table.cell>
                    </studio:table.row>
                @endforeach
            </studio:table.rows>
        </studio:table>
    @endif
</div>
