<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Users') }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.People in this workspace and their access role.') }}</p>
        </div>
        <div class="flex shrink-0 items-center gap-2">
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

            @if ($directoryHasUsers)
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
                        @if ($canAdvancedExport)
                            <studio:dropdown.item wire:click="export('tsv')" x-on:click="open = false">
                                {{ __('dashboard.TSV') }}
                            </studio:dropdown.item>
                            <studio:dropdown.item wire:click="export('json')" x-on:click="open = false">
                                {{ __('dashboard.JSON') }}
                            </studio:dropdown.item>
                        @endif
                    </x-slot:menu>
                </studio:dropdown>
            @endif
            @allows('users.compose')
                <studio:button
                    href="{{ route('dashboard.users.create') }}"
                    variant="ghost"
                    square
                    size="sm"
                    aria-label="{{ __('dashboard.New user') }}"
                    title="{{ __('dashboard.New user') }}"
                >
                    <studio:icon name="plus" class="size-4" />
                </studio:button>
            @endallows
        </div>
    </div>

    @if ($users->isEmpty())
        <p class="text-sm text-studio-muted">
            {{ filled($search) ? __('dashboard.No results.') : __('dashboard.No users yet.') }}
        </p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Username') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Email') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Role') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Pack') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Country') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($users as $user)
                    @php
                        $status = $user->statusEnum();
                        $statusColor = match ($status) {
                            \App\Enums\UserStatus::Active => 'teal',
                            \App\Enums\UserStatus::Invited => 'amber',
                            \App\Enums\UserStatus::Disabled => 'red',
                        };
                        $countryLabel = \App\Support\Geography\Countries::label($user->country);
                    @endphp
                    <studio:table.row>
                        <studio:table.cell>
                            <div class="min-w-0">
                                <p class="truncate text-xs font-medium text-studio-fg">{{ $user->name }}</p>
                                @if (filled($user->username))
                                    <p class="truncate text-[11px] text-studio-muted">{{ $user->username }}</p>
                                @endif
                            </div>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-fg-soft">{{ $user->email }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            {{ $user->accessRole?->title ?? __('dashboard.Unassigned') }}
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-fg-soft">{{ $user->entitlement?->plan?->title ?? __('dashboard.Free') }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <studio:badge :color="$statusColor" size="xs">{{ $status->label() }}</studio:badge>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $countryLabel ?: '—' }}</span>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            @allows('users.revise')
                                <studio:button
                                    href="{{ route('dashboard.users.edit', $user) }}"
                                    variant="ghost"
                                    square
                                    size="sm"
                                    aria-label="{{ __('dashboard.Edit') }}"
                                    title="{{ __('dashboard.Edit') }}"
                                >
                                    <studio:icon name="pencil" class="size-4" />
                                </studio:button>
                            @endallows
                        </studio:table.cell>
                    </studio:table.row>
                @endforeach
            </studio:table.rows>
        </studio:table>
    @endif
</div>
