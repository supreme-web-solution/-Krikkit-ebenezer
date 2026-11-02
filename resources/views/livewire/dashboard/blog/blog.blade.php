<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Blog') }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Write and publish posts for the public blog.') }}</p>
        </div>
        @allows('blog.compose')
            <studio:button
                href="{{ route('dashboard.blog.create') }}"
                variant="ghost"
                square
                size="sm"
                aria-label="{{ __('dashboard.New post') }}"
            >
                <studio:icon name="plus" class="size-4" />
            </studio:button>
        @endallows
    </div>

    @if ($posts->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No posts yet.') }}</p>
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
                @foreach ($posts as $post)
                    <studio:table.row>
                        <studio:table.cell>
                            <p class="truncate text-xs font-medium text-studio-fg">{{ $post->title }}</p>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $post->slug }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            @if ($post->isReleased())
                                <studio:badge color="teal" size="xs">{{ __('dashboard.Published') }}</studio:badge>
                            @else
                                <studio:badge size="xs">{{ __('dashboard.Draft') }}</studio:badge>
                            @endif
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $post->updated_at?->toFormattedDateString() }}</span>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <div class="inline-flex items-center gap-1">
                                @allows('blog.revise')
                                    <studio:button
                                        href="{{ route('dashboard.blog.edit', $post) }}"
                                        variant="ghost"
                                        square
                                        size="sm"
                                        aria-label="{{ __('dashboard.Edit') }}"
                                    >
                                        <studio:icon name="pencil" class="size-4" />
                                    </studio:button>
                                @endallows
                                @allows('blog.retire')
                                    <studio:button
                                        type="button"
                                        variant="ghost"
                                        square
                                        size="sm"
                                        wire:click="askRetire('{{ $post->public_id }}')"
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

    <studio:confirm name="retire-post" :title="__('dashboard.Retire post')" :copy="__('dashboard.Retire this post?')">
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="confirmPending">
                {{ __('dashboard.Retire') }}
            </studio:button>
        </x-slot:action>
    </studio:confirm>
</div>
