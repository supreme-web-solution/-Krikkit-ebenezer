@php
    use App\Finance\FinanceCopy;
@endphp

<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ $event->event_type }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ FinanceCopy::driver($event->driver) }} · {{ $event->provider_event_id ?: $event->public_id }}</p>
        </div>
        @allows('finance.revise')
            <studio:button type="button" variant="outline" wire:click="retry">{{ __('dashboard.Retry') }}</studio:button>
        @endallows
    </div>

    @include('livewire.dashboard.finance.options.nav')

    @php $tone = FinanceCopy::event($event->status); @endphp

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Status') }}</p>
            <div class="mt-2"><studio:badge :color="$tone['color']" size="xs">{{ $tone['label'] }}</studio:badge></div>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Retry') }}</p>
            <p class="mt-2 text-sm tabular-nums text-studio-fg">{{ (int) $event->attempts }}</p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Invoice') }}</p>
            <p class="mt-2 text-sm text-studio-fg">
                @if ($event->invoice)
                    <a href="{{ route('dashboard.invoices.show', $event->invoice) }}" wire:navigate class="hover:underline">{{ $event->invoice->formattedAmount() }}</a>
                @else
                    —
                @endif
            </p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Subscription') }}</p>
            <p class="mt-2 text-sm text-studio-fg">
                @if ($event->entitlement)
                    <a href="{{ route('dashboard.finance.subscriptions.show', $event->entitlement) }}" wire:navigate class="hover:underline">
                        {{ $event->entitlement->user?->name ?: __('dashboard.Subscription') }}
                    </a>
                @else
                    —
                @endif
            </p>
        </studio:card>
    </div>

    @if (filled($event->last_error))
        <studio:callout tone="danger">{{ $event->last_error }}</studio:callout>
    @endif

    <div class="space-y-2">
        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Payload') }}</p>
        <pre class="max-h-[28rem] overflow-auto rounded-lg border border-studio-line bg-studio-soft p-4 text-xs leading-5 text-studio-fg">{{ $payload }}</pre>
    </div>
</div>
