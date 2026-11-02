@php
    use App\Finance\FinanceCopy;
@endphp

<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Invoices') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Paid, open, failed, and refunded charges from Stripe and PayPal.') }}</p>
    </div>

    <div class="flex flex-wrap items-end gap-3">
        <div class="min-w-56 flex-1">
            <studio:input size="sm" wire:model.live.debounce.300ms="search" :placeholder="__('dashboard.Search…')" />
        </div>
        <div class="w-44">
            <studio:select size="sm" wire:model.live="status" :value="$status" placeholder="{{ __('dashboard.Status') }}">
                <studio:select.option value="" :selected="$status === ''">{{ __('dashboard.All') }}</studio:select.option>
                <studio:select.option value="paid" :selected="$status === 'paid'">{{ __('dashboard.Paid') }}</studio:select.option>
                <studio:select.option value="open" :selected="$status === 'open'">{{ __('dashboard.Open') }}</studio:select.option>
                <studio:select.option value="failed" :selected="$status === 'failed'">{{ __('dashboard.Failed') }}</studio:select.option>
                <studio:select.option value="refunded" :selected="$status === 'refunded'">{{ __('dashboard.Refunded') }}</studio:select.option>
            </studio:select>
        </div>
    </div>

    @if ($rows->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No invoices yet. Checkout and invoice.paid webhooks land here.') }}</p>
    @else
        <studio:table fit>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.User') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Pack') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Amount') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Method') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Paid') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($rows as $invoice)
                    @php $tone = FinanceCopy::invoice($invoice->status); @endphp
                    <studio:table.row>
                        <studio:table.cell>
                            <p class="truncate text-xs font-medium text-studio-fg">{{ $invoice->user?->name ?: __('dashboard.Unlinked') }}</p>
                            <p class="truncate text-[11px] text-studio-muted">{{ $invoice->user?->email }}</p>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $invoice->plan?->title ?: '—' }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="tabular-nums text-studio-fg">{{ $invoice->formattedAmount() }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ FinanceCopy::driver($invoice->driver) }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <studio:badge :color="$tone['color']" size="xs">{{ $tone['label'] }}</studio:badge>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-[11px] text-studio-muted">{{ $invoice->paid_at?->format('M j, Y') ?: '—' }}</span>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <studio:button
                                href="{{ route('dashboard.invoices.show', $invoice) }}"
                                variant="ghost"
                                square
                                size="sm"
                                aria-label="{{ __('dashboard.Show') }}"
                            >
                                <studio:icon name="eye" class="size-4" />
                            </studio:button>
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
