<x-layouts.dashboard :title="__('dashboard.Access roles')" skeleton="table">
    <div class="space-y-8">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div class="min-w-0">
                <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Roles') }}</h1>
                <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Define who can act in the workspace.') }}</p>
            </div>
            @allows('roles.compose')
                <studio:button
                    href="{{ route('dashboard.roles.create') }}"
                    variant="ghost"
                    square
                    size="sm"
                    aria-label="{{ __('dashboard.New role') }}"
                    title="{{ __('dashboard.New role') }}"
                >
                    <studio:icon name="plus" class="size-4" />
                </studio:button>
            @endallows
        </div>

        @if ($roles->isEmpty())
            <p class="text-sm text-studio-muted">{{ __('dashboard.No roles yet.') }}</p>
        @else
            <studio:table>
                <studio:table.columns>
                    <studio:table.column>{{ __('dashboard.Role') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Summary') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Abilities') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Users') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Slug') }}</studio:table.column>
                    <studio:table.column align="right"></studio:table.column>
                </studio:table.columns>
                <studio:table.rows>
                    @foreach ($roles as $role)
                        <studio:table.row>
                            <studio:table.cell>
                                <div class="flex min-w-0 items-center gap-2">
                                    <p class="truncate text-xs font-medium text-studio-fg">{{ $role->title }}</p>
                                    @if ($role->locked)
                                        <studio:badge size="xs">{{ __('dashboard.Locked') }}</studio:badge>
                                    @endif
                                </div>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="text-studio-fg-soft">{{ $role->summary ?: '—' }}</span>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="text-studio-muted">{{ $role->abilities_count }}</span>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="text-studio-muted">{{ $role->holders_count }}</span>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="text-studio-muted">{{ $role->slug }}</span>
                            </studio:table.cell>
                            <studio:table.cell align="right">
                                <div class="inline-flex items-center gap-1">
                                    @allows('roles.revise')
                                        @unless ($role->locked)
                                            <studio:button
                                                href="{{ route('dashboard.roles.edit', $role) }}"
                                                variant="ghost"
                                                square
                                                size="sm"
                                                aria-label="{{ __('dashboard.Edit') }}"
                                                title="{{ __('dashboard.Edit') }}"
                                            >
                                                <studio:icon name="pencil" class="size-4" />
                                            </studio:button>
                                        @endunless
                                    @endallows
                                    @allows('roles.retire')
                                        @unless ($role->locked)
                                            <studio:button
                                                type="button"
                                                variant="ghost"
                                                square
                                                size="sm"
                                                aria-label="{{ __('dashboard.Retire') }}"
                                                title="{{ __('dashboard.Retire') }}"
                                                @click="$dispatch('studio-modal-open', { name: 'retire-role', url: @js(route('dashboard.roles.destroy', $role)) })"
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

        <studio:confirm name="retire-role" :title="__('dashboard.Retire role')" :copy="__('dashboard.Retire this role?')">
            <x-slot:action>
                <form method="POST" x-bind:action="payload?.url ?? ''">
                    @csrf
                    @method('DELETE')
                    <studio:button type="submit" variant="danger">{{ __('dashboard.Retire') }}</studio:button>
                </form>
            </x-slot:action>
        </studio:confirm>
    </div>
</x-layouts.dashboard>
