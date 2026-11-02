<div class="space-y-8">
    <div class="flex items-center gap-3">
        <studio:button
            href="{{ route('dashboard.sessions.index') }}"
            variant="ghost"
            square
            size="sm"
            aria-label="{{ __('dashboard.Back') }}"
            title="{{ __('dashboard.Back') }}"
        >
            <studio:icon name="arrow-left" class="size-4" />
        </studio:button>
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Sessions') }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ $user->email }}</p>
        </div>
    </div>

    @if ($sessions->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No active sessions.') }}</p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Connection') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Last activity') }}</studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($sessions as $row)
                    @php
                        $agent = filled($row->user_agent) ? $row->user_agent : '—';
                        $isCurrent = $row->isCurrent();
                    @endphp
                    <studio:table.row>
                        <studio:table.cell>
                            <div class="min-w-0 max-w-md">
                                <p class="truncate text-xs text-studio-fg" title="{{ $agent }}">{{ $agent }}</p>
                                @if (filled($row->ip_address))
                                    <p class="truncate text-[11px] text-studio-muted">{{ $row->ip_address }}</p>
                                @endif
                            </div>
                        </studio:table.cell>
                        <studio:table.cell>
                            <div class="flex flex-col gap-1">
                                <span class="text-studio-muted">{{ $row->lastActivityAt()->diffForHumans() }}</span>
                                @if ($isCurrent)
                                    <span class="inline-flex w-fit rounded-md bg-teal-500/15 px-1.5 py-0.5 text-[10px] font-medium text-teal-700 dark:text-teal-300">
                                        {{ __('dashboard.This device') }}
                                    </span>
                                @endif
                            </div>
                        </studio:table.cell>
                    </studio:table.row>
                @endforeach
            </studio:table.rows>
        </studio:table>
    @endif
</div>
