@php
    use App\Finance\FinanceCopy;
@endphp

<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ $invoice->formattedAmount() }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ $invoice->user?->name ?: __('dashboard.Unlinked') }} · {{ FinanceCopy::driver($invoice->driver) }}</p>
        </div>
        @allows('finance.revise')
            @if ($invoice->canRefund())
                <studio:button type="button" variant="danger" wire:click="askRefund">{{ __('dashboard.Refund') }}</studio:button>
            @endif
        @endallows
    </div>

    @php $tone = FinanceCopy::invoice($invoice->status); @endphp

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Status') }}</p>
            <div class="mt-2"><studio:badge :color="$tone['color']" size="xs">{{ $tone['label'] }}</studio:badge></div>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Pack') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ $invoice->plan?->title ?: '—' }}</p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Paid') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ $invoice->paid_at?->format('M j, Y H:i') ?: '—' }}</p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Refunded') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ $invoice->refunded_at?->format('M j, Y H:i') ?: '—' }}</p>
        </studio:card>
    </div>

    <studio:card class="space-y-4">
        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Provider') }}</p>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <p class="text-[11px] text-studio-subtle">{{ __('dashboard.Provider invoice') }}</p>
                <p class="mt-1 break-all text-sm text-studio-fg">{{ $invoice->provider_invoice_id ?: '—' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-studio-subtle">{{ __('dashboard.Provider subscription') }}</p>
                <p class="mt-1 break-all text-sm text-studio-fg">{{ $invoice->provider_subscription_id ?: '—' }}</p>
            </div>
            @if ($invoice->entitlement)
                <div>
                    <p class="text-[11px] text-studio-subtle">{{ __('dashboard.Subscription') }}</p>
                    <a href="{{ route('dashboard.finance.subscriptions.show', $invoice->entitlement) }}" wire:navigate class="mt-1 inline-block text-sm text-studio-fg hover:underline">
                        {{ $invoice->entitlement->plan?->title ?: __('dashboard.Subscription') }}
                    </a>
                </div>
            @endif
            @if ($invoice->refundedBy)
                <div>
                    <p class="text-[11px] text-studio-subtle">{{ __('dashboard.Refunded by') }}</p>
                    <p class="mt-1 text-sm text-studio-fg">{{ $invoice->refundedBy->name }}</p>
                </div>
            @endif
        </div>
    </studio:card>

    @if ($invoice->events->isNotEmpty())
        <div class="space-y-3">
            <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Events') }}</h2>
            <studio:table>
                <studio:table.columns>
                    <studio:table.column>{{ __('dashboard.Event') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                </studio:table.columns>
                <studio:table.rows>
                    @foreach ($invoice->events as $event)
                        @php $eventTone = FinanceCopy::event($event->status); @endphp
                        <studio:table.row>
                            <studio:table.cell>
                                <a href="{{ route('dashboard.finance.events.show', $event) }}" wire:navigate class="text-xs font-medium text-studio-fg hover:underline">
                                    {{ $event->event_type }}
                                </a>
                            </studio:table.cell>
                            <studio:table.cell>
                                <studio:badge :color="$eventTone['color']" size="xs">{{ $eventTone['label'] }}</studio:badge>
                            </studio:table.cell>
                        </studio:table.row>
                    @endforeach
                </studio:table.rows>
            </studio:table>
        </div>
    @endif

    <studio:confirm name="refund-invoice" :title="__('dashboard.Refund')" :copy="__('dashboard.Refund this invoice?')">
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="confirmPending">
                {{ __('dashboard.Refund') }}
            </studio:button>
        </x-slot:action>
    </studio:confirm>
</div>
