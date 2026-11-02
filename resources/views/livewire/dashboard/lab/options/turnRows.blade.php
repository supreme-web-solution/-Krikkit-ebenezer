<studio:table>
    <studio:table.columns>
        <studio:table.column>{{ __('dashboard.Project') }}</studio:table.column>
        <studio:table.column>{{ __('dashboard.Owner') }}</studio:table.column>
        <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
        <studio:table.column>{{ __('dashboard.Updated') }}</studio:table.column>
    </studio:table.columns>
    <studio:table.rows>
        @foreach ($rows as $message)
            @php
                $project = $message->project;
                $status = \App\Lab\LabTurnHealth::statusLabel($message, $kind);
            @endphp
            <studio:table.row>
                <studio:table.cell>
                    @if ($project)
                        <a href="{{ route('dashboard.lab.show', $project) }}" wire:navigate class="text-xs font-medium text-studio-fg hover:underline">
                            {{ $project->title ?: __('dashboard.Untitled') }}
                        </a>
                    @else
                        <span class="text-studio-muted">—</span>
                    @endif
                </studio:table.cell>
                <studio:table.cell>
                    <span class="text-xs text-studio-muted">{{ $project?->user?->name ?: __('dashboard.Unassigned') }}</span>
                </studio:table.cell>
                <studio:table.cell>
                    <studio:badge size="xs" :color="$kind === 'stuck' ? 'amber' : 'red'">{{ $status }}</studio:badge>
                </studio:table.cell>
                <studio:table.cell>
                    <span class="text-studio-muted">{{ $message->updated_at?->diffForHumans() }}</span>
                </studio:table.cell>
            </studio:table.row>
        @endforeach
    </studio:table.rows>
</studio:table>
