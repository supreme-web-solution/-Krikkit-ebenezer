<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.API Integration') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">
            {{ __('dashboard.Default model, provider keys, GitHub OAuth, photograph catalogs, and the Lab datastore.') }}
        </p>
    </div>

    @include('livewire.dashboard.integration.options.defaults')

    @if (count($providers) === 0)
        <p class="text-sm text-studio-muted">{{ __('dashboard.No providers configured.') }}</p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Provider') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Models') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.API key') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($providers as $provider)
                    <studio:table.row>
                        <studio:table.cell>
                            <p class="text-xs font-medium text-studio-fg">{{ $provider['label'] }}</p>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">
                                {{ $provider['models_label'] ?? count($provider['models']) }}
                            </span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-fg-soft">
                                @if (in_array($provider['id'], ['github', 'supabase'], true))
                                    @if ($provider['source'] === 'workspace')
                                        {{ __('dashboard.Configured') }}
                                    @elseif ($provider['source'] === 'env')
                                        {{ __('dashboard.Using .env') }}
                                    @else
                                        {{ __('dashboard.Not configured') }}
                                    @endif
                                @elseif ($provider['source'] === 'workspace')
                                    {{ __('dashboard.workspace key :masked', ['masked' => $provider['masked']]) }}
                                @elseif ($provider['source'] === 'env')
                                    {{ __('dashboard..env fallback :masked', ['masked' => $provider['masked']]) }}
                                @else
                                    {{ __('dashboard.no key') }}
                                @endif
                            </span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <div class="flex flex-wrap items-center gap-1">
                                @if ($provider['enabled'])
                                    <studio:badge color="teal" size="xs">{{ __('dashboard.On') }}</studio:badge>
                                @else
                                    <studio:badge size="xs">{{ __('dashboard.Off') }}</studio:badge>
                                @endif
                                @if ($provider['source'] === 'missing')
                                    <studio:badge color="amber" size="xs">
                                        {{ in_array($provider['id'], ['github', 'supabase'], true) ? __('dashboard.Not configured') : __('dashboard.Needs key') }}
                                    </studio:badge>
                                @endif
                            </div>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <studio:button
                                href="{{ route('dashboard.integration.edit', $provider['id']) }}"
                                variant="ghost"
                                square
                                size="sm"
                                aria-label="{{ __('dashboard.Edit') }}"
                                title="{{ __('dashboard.Edit') }}"
                            >
                                <studio:icon name="pencil" class="size-4" />
                            </studio:button>
                        </studio:table.cell>
                    </studio:table.row>
                @endforeach
            </studio:table.rows>
        </studio:table>
    @endif
</div>
