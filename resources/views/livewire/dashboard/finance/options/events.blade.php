@php
    use App\Finance\FinanceCopy;
@endphp

<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Events') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Webhook log from Stripe and PayPal, linked to invoices and subscriptions.') }}</p>
    </div>

    @include('livewire.dashboard.finance.options.nav')

    <div class="flex flex-wrap items-end gap-3">
        <div class="min-w-56 flex-1">
            <studio:input size="sm" wire:model.live.debounce.300ms="search" :placeholder="__('dashboard.Search…')" />
        </div>
        <div class="w-44">
            <studio:select size="sm" wire:model.live="status" :value="$status" placeholder="{{ __('dashboard.Status') }}">
                <studio:select.option value="" :selected="$status === ''">{{ __('dashboard.All') }}</studio:select.option>
                <studio:select.option value="received" :selected="$status === 'received'">{{ __('dashboard.Received') }}</studio:select.option>
                <studio:select.option value="applied" :selected="$status === 'applied'">{{ __('dashboard.Applied') }}</studio:select.option>
                <studio:select.option value="ignored" :selected="$status === 'ignored'">{{ __('dashboard.Ignored') }}</studio:select.option>
                <studio:select.option value="failed" :selected="$status === 'failed'">{{ __('dashboard.Failed') }}</studio:select.option>
            </studio:select>
        </div>
    </div>

    @if ($rows->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No webhook events yet.') }}</p>
    @else
        <studio:table fit>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Event') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Method') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Linked') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Retry') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($rows as $event)
                    @php $tone = FinanceCopy::event($event->status); @endphp
                    <studio:table.row>
                        <studio:table.cell>
                            <p class="truncate text-xs font-medium text-studio-fg">{{ $event->event_type }}</p>
                            <p class="truncate text-[11px] text-studio-subtle">{{ $event->provider_event_id }}</p>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ FinanceCopy::driver($event->driver) }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <studio:badge :color="$tone['color']" size="xs">{{ $tone['label'] }}</studio:badge>
                            @if (filled($event->last_error))
                                <p class="mt-1 truncate text-[11px] text-red-600">{{ $event->last_error }}</p>
                            @endif
                        </studio:table.cell>
                        <studio:table.cell>
                            <div class="flex flex-col gap-0.5 text-[11px]">
                                @if ($event->invoice)
                                    <a href="{{ route('dashboard.invoices.show', $event->invoice) }}" wire:navigate class="text-studio-fg hover:underline">
                                        {{ $event->invoice->formattedAmount() }}
                                    </a>
                                @endif
                                @if ($event->entitlement)
                                    <a href="{{ route('dashboard.finance.subscriptions.show', $event->entitlement) }}" wire:navigate class="text-studio-muted hover:underline">
                                        {{ $event->entitlement->user?->name ?: __('dashboard.Subscription') }}
                                    </a>
                                @endif
                                @if (! $event->invoice && ! $event->entitlement)
                                    <span class="text-studio-subtle">—</span>
                                @endif
                            </div>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="tabular-nums text-studio-muted">{{ (int) $event->attempts }}</span>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <div class="inline-flex items-center gap-1">
                                @allows('finance.revise')
                                    <studio:button
                                        type="button"
                                        variant="ghost"
                                        square
                                        size="sm"
                                        wire:click="retry('{{ $event->public_id }}')"
                                        aria-label="{{ __('dashboard.Retry') }}"
                                    >
                                        <studio:icon name="arrow-path" class="size-4" />
                                    </studio:button>
                                @endallows
                                <studio:button
                                    href="{{ route('dashboard.finance.events.show', $event) }}"
                                    variant="ghost"
                                    square
                                    size="sm"
                                    aria-label="{{ __('dashboard.Show') }}"
                                >
                                    <studio:icon name="eye" class="size-4" />
                                </studio:button>
                            </div>
                        </studio:table.cell>
                    </studio:table.row>
                @endforeach
            </studio:table.rows>
        </studio:table>

        <studio:pagination
            goto="gotoPage"
            :current="$rows->currentPage()"
            :last="$rows->lastPage()"
            :total="$rows->total()"
            :first-item="$rows->firstItem()"
            :last-item="$rows->lastItem()"
        />
    @endif
</div>
