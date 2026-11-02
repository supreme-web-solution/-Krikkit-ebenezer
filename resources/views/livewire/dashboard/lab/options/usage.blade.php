<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Usage') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Credits spent by user, project, and model.') }}</p>
    </div>

    <nav class="flex flex-wrap gap-1" aria-label="{{ __('dashboard.Usage') }}">
        @foreach ([
            'user' => __('dashboard.User'),
            'project' => __('dashboard.Project'),
            'model' => __('dashboard.Model'),
        ] as $key => $label)
            <button
                type="button"
                wire:click="showFacet(@js($key))"
                @class([
                    'rounded-full border px-3 py-1 text-xs transition',
                    'border-studio-fg bg-studio-soft text-studio-fg' => $facet === $key,
                    'border-studio-line text-studio-muted hover:text-studio-fg' => $facet !== $key,
                ])
            >
                {{ $label }}
            </button>
        @endforeach
    </nav>

    @if ($facet === 'user')
        @if ($byUser === [])
            <p class="text-sm text-studio-muted">{{ __('dashboard.No Lab usage yet.') }}</p>
        @else
            <studio:table>
                <studio:table.columns>
                    <studio:table.column>{{ __('dashboard.User') }}</studio:table.column>
                    <studio:table.column align="right">{{ __('dashboard.Projects') }}</studio:table.column>
                    <studio:table.column align="right">{{ __('dashboard.Credits') }}</studio:table.column>
                </studio:table.columns>
                <studio:table.rows>
                    @foreach ($byUser as $row)
                        <studio:table.row>
                            <studio:table.cell>
                                @if ($row['user'])
                                    <p class="text-xs text-studio-fg">{{ $row['user']->name }}</p>
                                    <p class="text-[11px] text-studio-muted">{{ $row['user']->email }}</p>
                                @else
                                    <span class="text-studio-muted">{{ __('dashboard.Unassigned') }}</span>
                                @endif
                            </studio:table.cell>
                            <studio:table.cell align="right">
                                <span class="tabular-nums">{{ number_format($row['projects']) }}</span>
                            </studio:table.cell>
                            <studio:table.cell align="right">
                                <span class="tabular-nums text-studio-fg">{{ number_format($row['credits']) }}</span>
                            </studio:table.cell>
                        </studio:table.row>
                    @endforeach
                </studio:table.rows>
            </studio:table>
        @endif
    @elseif ($facet === 'project')
        @if ($byProject === [])
            <p class="text-sm text-studio-muted">{{ __('dashboard.No Lab usage yet.') }}</p>
        @else
            <studio:table>
                <studio:table.columns>
                    <studio:table.column>{{ __('dashboard.Project') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Owner') }}</studio:table.column>
                    <studio:table.column align="right">{{ __('dashboard.Credits') }}</studio:table.column>
                </studio:table.columns>
                <studio:table.rows>
                    @foreach ($byProject as $project)
                        <studio:table.row>
                            <studio:table.cell>
                                <a href="{{ route('dashboard.lab.show', $project) }}" wire:navigate class="text-xs font-medium text-studio-fg hover:underline">
                                    {{ $project->title ?: __('dashboard.Untitled') }}
                                </a>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="text-xs text-studio-muted">{{ $project->user?->name ?: __('dashboard.Unassigned') }}</span>
                            </studio:table.cell>
                            <studio:table.cell align="right">
                                <span class="tabular-nums text-studio-fg">{{ number_format((int) $project->credits_spent) }}</span>
                            </studio:table.cell>
                        </studio:table.row>
                    @endforeach
                </studio:table.rows>
            </studio:table>
        @endif
    @else
        @if ($byModel === [])
            <p class="text-sm text-studio-muted">{{ __('dashboard.No model usage recorded yet.') }}</p>
        @else
            <studio:table>
                <studio:table.columns>
                    <studio:table.column>{{ __('dashboard.Model') }}</studio:table.column>
                    <studio:table.column align="right">{{ __('dashboard.Turns') }}</studio:table.column>
                    <studio:table.column align="right">{{ __('dashboard.Credits') }}</studio:table.column>
                    <studio:table.column align="right">{{ __('dashboard.Tokens') }}</studio:table.column>
                </studio:table.columns>
                <studio:table.rows>
                    @foreach ($byModel as $row)
                        <studio:table.row>
                            <studio:table.cell>
                                <span class="text-xs text-studio-fg">{{ $row['model'] }}</span>
                            </studio:table.cell>
                            <studio:table.cell align="right">
                                <span class="tabular-nums">{{ number_format($row['turns']) }}</span>
                            </studio:table.cell>
                            <studio:table.cell align="right">
                                <span class="tabular-nums text-studio-fg">{{ number_format($row['credits']) }}</span>
                            </studio:table.cell>
                            <studio:table.cell align="right">
                                <span class="tabular-nums text-studio-muted">{{ number_format($row['input_tokens'] + $row['output_tokens']) }}</span>
                            </studio:table.cell>
                        </studio:table.row>
                    @endforeach
                </studio:table.rows>
            </studio:table>
        @endif
    @endif
</div>
