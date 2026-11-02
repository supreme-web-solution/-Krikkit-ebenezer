@php
    use App\Finance\FinanceCopy;
@endphp

<x-settings.frame section="credits">
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-studio-fg">{{ __('settings.Credits') }}</h1>
            <p class="mt-1 text-sm text-studio-muted">{{ __('settings.Bonus and pack credits added to this account.') }}</p>
        </div>

        <studio:card class="space-y-1">
            @if ($credits && ($credits['unlimited'] ?? false))
                <p class="text-sm font-semibold text-studio-fg">{{ __('settings.Unlimited credits') }}</p>
            @elseif ($credits)
                <p class="text-sm font-semibold text-studio-fg">{{ __('settings.Credits remaining this month') }}</p>
                <p class="text-3xl font-semibold tracking-tight text-studio-fg">{{ number_format((int) ($credits['remaining'] ?? 0)) }}</p>
                <p class="text-sm text-studio-muted">{{ __('settings.Used') }} {{ number_format((int) ($credits['used'] ?? 0)) }} / {{ number_format((int) ($credits['limit'] ?? 0)) }}</p>
            @endif
        </studio:card>

        @if ($grants->isEmpty())
            <p class="text-sm text-studio-muted">{{ __('settings.No credit grants yet.') }}</p>
        @else
            <studio:table>
                <studio:table.columns>
                    <studio:table.column>{{ __('settings.Amount') }}</studio:table.column>
                    <studio:table.column>{{ __('settings.Kind') }}</studio:table.column>
                    <studio:table.column>{{ __('settings.Reason') }}</studio:table.column>
                    <studio:table.column>{{ __('settings.When') }}</studio:table.column>
                </studio:table.columns>
                <studio:table.rows>
                    @foreach ($grants as $grant)
                        <studio:table.row>
                            <studio:table.cell>{{ $grant->amount > 0 ? '+' : '' }}{{ number_format((int) $grant->amount) }}</studio:table.cell>
                            <studio:table.cell>{{ FinanceCopy::creditKind($grant->kind) }}</studio:table.cell>
                            <studio:table.cell>{{ $grant->reason ?: '—' }}</studio:table.cell>
                            <studio:table.cell>{{ $grant->created_at?->diffForHumans() }}</studio:table.cell>
                        </studio:table.row>
                    @endforeach
                </studio:table.rows>
            </studio:table>
        @endif
    </div>
</x-settings.frame>
