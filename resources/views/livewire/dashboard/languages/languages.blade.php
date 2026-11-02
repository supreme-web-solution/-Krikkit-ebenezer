<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Languages') }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Add locales, then translate existing UI phrases.') }}</p>
        </div>
        @allows('languages.compose')
            <studio:button
                href="{{ route('dashboard.languages.create') }}"
                variant="ghost"
                square
                size="sm"
                aria-label="{{ __('dashboard.New language') }}"
            >
                <studio:icon name="plus" class="size-4" />
            </studio:button>
        @endallows
    </div>

    @if ($languages->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No languages yet.') }}</p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Language') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Code') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Phrases') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($languages as $language)
                    @php
                        $done = $language->code === $sourceLocale
                            ? $totalPhrases
                            : (int) ($filledCounts[$language->id] ?? 0);
                    @endphp
                    <studio:table.row>
                        <studio:table.cell>
                            <p class="truncate text-xs font-medium text-studio-fg">{{ $language->name }}</p>
                            @if (filled($language->native_name) && $language->native_name !== $language->name)
                                <p class="truncate text-[11px] text-studio-muted">{{ $language->native_name }}</p>
                            @endif
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $language->code }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <div class="flex flex-wrap items-center gap-1">
                                @if ($language->is_default)
                                    <studio:badge color="teal" size="xs">{{ __('dashboard.Default') }}</studio:badge>
                                @endif
                                @if ($language->enabled)
                                    <studio:badge size="xs">{{ __('dashboard.Enabled') }}</studio:badge>
                                @else
                                    <studio:badge size="xs">{{ __('dashboard.Disabled') }}</studio:badge>
                                @endif
                            </div>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $done }} / {{ $totalPhrases }}</span>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <div class="inline-flex items-center gap-1">
                                @allows('languages.revise')
                                    <studio:button
                                        href="{{ route('dashboard.languages.translate', $language) }}"
                                        variant="ghost"
                                        square
                                        size="sm"
                                        aria-label="{{ __('dashboard.Translate') }}"
                                    >
                                        <studio:icon name="language" class="size-4" />
                                    </studio:button>
                                    <studio:button
                                        href="{{ route('dashboard.languages.edit', $language) }}"
                                        variant="ghost"
                                        square
                                        size="sm"
                                        aria-label="{{ __('dashboard.Edit') }}"
                                    >
                                        <studio:icon name="pencil" class="size-4" />
                                    </studio:button>
                                @endallows
                                @allows('languages.retire')
                                    @unless ($language->is_default)
                                        <studio:button
                                            type="button"
                                            variant="ghost"
                                            square
                                            size="sm"
                                            wire:click="askRetire('{{ $language->public_id }}')"
                                            aria-label="{{ __('dashboard.Retire') }}"
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

    <studio:confirm name="retire-language" :title="__('dashboard.Retire language')" :copy="__('dashboard.Retire this language?')">
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="confirmPending">
                {{ __('dashboard.Retire') }}
            </studio:button>
        </x-slot:action>
    </studio:confirm>
</div>
