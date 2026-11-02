<x-settings.frame section="usage">
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-studio-fg">{{ __('settings.Usage') }}</h1>
            <p class="mt-1 text-sm text-studio-muted">{{ __('settings.How Lab credits and tokens have been used on your projects.') }}</p>
        </div>

        @if ($snapshot['credits'] ?? null)
            <studio:card class="grid gap-4 sm:grid-cols-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('settings.Used') }}</p>
                    <p class="mt-1 text-lg font-semibold text-studio-fg">{{ $snapshot['credits']['unlimited'] ? '—' : number_format((int) $snapshot['credits']['used']) }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('settings.Remaining') }}</p>
                    <p class="mt-1 text-lg font-semibold text-studio-fg">{{ $snapshot['credits']['unlimited'] ? __('settings.Unlimited credits') : number_format((int) ($snapshot['credits']['remaining'] ?? 0)) }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('settings.Projects') }}</p>
                    <p class="mt-1 text-lg font-semibold text-studio-fg">{{ number_format((int) ($snapshot['quotas']['projects']['used'] ?? 0)) }}</p>
                </div>
            </studio:card>
        @endif

        <div>
            <p class="mb-3 text-sm font-medium text-studio-muted">{{ __('settings.Projects') }}</p>
            @if ($ledger['projects'] === [])
                <p class="text-sm text-studio-muted">{{ __('settings.No usage yet.') }}</p>
            @else
                <studio:table>
                    <studio:table.columns>
                        <studio:table.column>{{ __('settings.Projects') }}</studio:table.column>
                        <studio:table.column>{{ __('settings.Credits') }}</studio:table.column>
                        <studio:table.column>{{ __('settings.When') }}</studio:table.column>
                    </studio:table.columns>
                    <studio:table.rows>
                        @foreach ($ledger['projects'] as $project)
                            <studio:table.row>
                                <studio:table.cell>
                                    <a href="{{ route('lab.show', $project['uuid']) }}" class="text-sm font-medium text-studio-fg hover:text-accent-content">{{ $project['title'] }}</a>
                                </studio:table.cell>
                                <studio:table.cell>{{ number_format((int) $project['credits']) }}</studio:table.cell>
                                <studio:table.cell>{{ $project['updated'] }}</studio:table.cell>
                            </studio:table.row>
                        @endforeach
                    </studio:table.rows>
                </studio:table>
            @endif
        </div>

        <div>
            <p class="mb-3 text-sm font-medium text-studio-muted">{{ __('settings.Models') }}</p>
            @if ($ledger['models'] === [])
                <p class="text-sm text-studio-muted">{{ __('settings.No usage yet.') }}</p>
            @else
                <studio:table>
                    <studio:table.columns>
                        <studio:table.column>{{ __('settings.Models') }}</studio:table.column>
                        <studio:table.column>{{ __('settings.Credits') }}</studio:table.column>
                        <studio:table.column>{{ __('settings.Turns') }}</studio:table.column>
                        <studio:table.column>{{ __('settings.Input tokens') }}</studio:table.column>
                        <studio:table.column>{{ __('settings.Output tokens') }}</studio:table.column>
                    </studio:table.columns>
                    <studio:table.rows>
                        @foreach ($ledger['models'] as $row)
                            <studio:table.row>
                                <studio:table.cell>{{ $row['model'] }}</studio:table.cell>
                                <studio:table.cell>{{ number_format((int) $row['credits']) }}</studio:table.cell>
                                <studio:table.cell>{{ number_format((int) $row['turns']) }}</studio:table.cell>
                                <studio:table.cell>{{ number_format((int) $row['input_tokens']) }}</studio:table.cell>
                                <studio:table.cell>{{ number_format((int) $row['output_tokens']) }}</studio:table.cell>
                            </studio:table.row>
                        @endforeach
                    </studio:table.rows>
                </studio:table>
            @endif
        </div>
    </div>
</x-settings.frame>
