@php
    use App\Finance\FinanceCopy;
@endphp

<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Subscriptions') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Who holds which pack, interval, and provider subscription.') }}</p>
    </div>

    @include('livewire.dashboard.finance.options.nav')

    <div class="flex flex-wrap items-end gap-3">
        <div class="min-w-56 flex-1">
            <studio:input size="sm" wire:model.live.debounce.300ms="search" :placeholder="__('dashboard.Search…')" />
        </div>
        <div class="w-44">
            <studio:select size="sm" wire:model.live="status" :value="$status" placeholder="{{ __('dashboard.Status') }}">
                <studio:select.option value="" :selected="$status === ''">{{ __('dashboard.All') }}</studio:select.option>
                <studio:select.option value="active" :selected="$status === 'active'">{{ __('dashboard.Active') }}</studio:select.option>
                <studio:select.option value="past_due" :selected="$status === 'past_due'">{{ __('dashboard.Past due') }}</studio:select.option>
                <studio:select.option value="canceled" :selected="$status === 'canceled'">{{ __('dashboard.Canceled') }}</studio:select.option>
            </studio:select>
        </div>
    </div>

    @if ($rows->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No subscriptions match.') }}</p>
    @else
        <studio:table fit>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.User') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Pack') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Interval') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Period') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Last payment') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($rows as $row)
                    @php $tone = FinanceCopy::subscription($row->status); @endphp
                    <studio:table.row>
                        <studio:table.cell>
                            <p class="truncate text-xs font-medium text-studio-fg">{{ $row->user?->name ?: '—' }}</p>
                            <p class="truncate text-[11px] text-studio-muted">{{ $row->user?->email }}</p>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-fg">{{ $row->plan?->title ?: '—' }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ FinanceCopy::interval($row->interval) }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <studio:badge :color="$tone['color']" size="xs">{{ $tone['label'] }}</studio:badge>
                        </studio:table.cell>
                        <studio:table.cell>
                            <p class="text-[11px] text-studio-muted">
                                {{ $row->started_at?->format('M j, Y') ?: '—' }}
                                →
                                {{ $row->ends_at?->format('M j, Y') ?: __('dashboard.Open') }}
                            </p>
                            @if (filled($row->provider_subscription_id))
                                <p class="truncate text-[11px] text-studio-subtle">{{ $row->provider_subscription_id }}</p>
                            @endif
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-[11px] text-studio-muted">{{ $row->last_paid_at?->format('M j, Y') ?: '—' }}</span>
                        </studio:table.cell>
                        <studio:table.cell align="right">
                            <studio:button
                                href="{{ route('dashboard.finance.subscriptions.show', $row) }}"
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
