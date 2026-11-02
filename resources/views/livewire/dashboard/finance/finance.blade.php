@php
    use App\Finance\FinanceCopy;
    use App\Finance\Money;

    $seriesMax = max(1, collect($cards['series'])->max('value') ?: 1);
    $healthTotal = max(1, array_sum($cards['health']));
@endphp

<div class="space-y-8">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Finance') }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Revenue, collections, credit liability, and payment events.') }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <studio:dropdown align="end">
                <x-slot:trigger>
                    <studio:button
                        type="button"
                        variant="ghost"
                        square
                        size="sm"
                        aria-label="{{ __('dashboard.Export') }}"
                        title="{{ __('dashboard.Export') }}"
                    >
                        <studio:icon name="arrow-down-tray" class="size-4" />
                    </studio:button>
                </x-slot:trigger>
                <x-slot:menu>
                    <studio:dropdown.item wire:click="export('csv')" x-on:click="open = false">
                        {{ __('dashboard.CSV') }}
                    </studio:dropdown.item>
                    @if ($canAdvancedExport)
                        <studio:dropdown.item wire:click="export('tsv')" x-on:click="open = false">
                            {{ __('dashboard.TSV') }}
                        </studio:dropdown.item>
                        <studio:dropdown.item wire:click="export('json')" x-on:click="open = false">
                            {{ __('dashboard.JSON') }}
                        </studio:dropdown.item>
                    @endif
                </x-slot:menu>
            </studio:dropdown>
            <span class="rounded-md bg-studio-soft px-2 py-1 text-[11px] font-medium text-studio-fg-soft">{{ $cards['month'] }}</span>
            <span class="rounded-md bg-studio-soft px-2 py-1 text-[11px] font-medium tabular-nums text-studio-fg-soft">{{ $cards['currency'] }}</span>
        </div>
    </div>

    @include('livewire.dashboard.finance.options.nav')

    <div class="grid gap-3 lg:grid-cols-3">
        <studio:card class="lg:col-span-2">
            <div class="grid gap-6 sm:grid-cols-[minmax(0,1fr)_13rem] sm:items-end">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Collected this month') }}</p>
                    <p class="mt-2 text-3xl font-semibold tabular-nums tracking-tight text-studio-fg">{{ Money::format($cards['collected'], $cards['currency']) }}</p>
                    <p class="mt-1 flex items-center gap-2 text-[11px] text-studio-muted">
                        @include('livewire.dashboard.finance.partials.delta', ['delta' => $cards['collected_delta']])
                        <span>{{ __('dashboard.vs last month') }}</span>
                    </p>
                </div>
                <div class="grid grid-cols-6 gap-2" aria-hidden="true">
                    @foreach ($cards['series'] as $point)
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="relative h-14 w-full">
                                <div class="absolute inset-x-0 bottom-0 mx-auto w-2.5 rounded-full bg-studio-fg/85" style="height: {{ max(14, (int) round(($point['value'] / $seriesMax) * 100)) }}%"></div>
                            </div>
                            <span class="text-[10px] leading-none text-studio-subtle">{{ $point['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </studio:card>

        <studio:card>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.MRR') }}</p>
            <p class="mt-2 text-3xl font-semibold tabular-nums tracking-tight text-studio-fg">{{ Money::format($cards['mrr'], $cards['currency']) }}</p>
            <p class="mt-1 text-[11px] text-studio-muted">{{ number_format($cards['active']) }} {{ __('dashboard.active subscriptions') }}</p>
            <p class="mt-3 text-[11px] text-studio-subtle">{{ __('dashboard.Active subscriptions, yearly divided by 12.') }}</p>
        </studio:card>
    </div>

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <studio:card>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Net after API') }}</p>
            <p class="mt-2 text-xl font-semibold tabular-nums text-studio-fg">{{ Money::format($cards['net'], $cards['currency']) }}</p>
            <p class="mt-1 text-[11px] text-studio-muted">
                {{ __('dashboard.Collected :revenue · est. API :cost (:credits credits)', [
                    'revenue' => Money::format($cards['collected'], $cards['currency']),
                    'cost' => Money::format($cards['api_usd'], $cards['currency']),
                    'credits' => number_format($cards['credits_burned']),
                ]) }}
            </p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Failed charges') }}</p>
            <p class="mt-2 text-xl font-semibold tabular-nums text-studio-fg">{{ number_format($cards['failed']) }}</p>
            <p class="mt-1 flex items-center gap-2 text-[11px] text-studio-muted">
                @include('livewire.dashboard.finance.partials.delta', ['delta' => $cards['failed_delta']])
                <span>{{ __('dashboard.Failed invoices this month.') }}</span>
            </p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Cancellations') }}</p>
            <p class="mt-2 text-xl font-semibold tabular-nums text-studio-fg">{{ number_format($cards['canceled']) }}</p>
            <p class="mt-1 flex items-center gap-2 text-[11px] text-studio-muted">
                @include('livewire.dashboard.finance.partials.delta', ['delta' => $cards['canceled_delta']])
                <span>{{ __('dashboard.Canceled subscriptions this month.') }}</span>
            </p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Credit liability') }}</p>
            <p class="mt-2 text-xl font-semibold tabular-nums text-studio-fg">{{ number_format($cards['credit_liability']) }}</p>
            <p class="mt-1 text-[11px] text-studio-muted">
                {{ __('dashboard.Unused pack and grant credits.') }}
                @if ($cards['unlimited_holders'] > 0)
                    · {{ __('dashboard.:count unlimited', ['count' => $cards['unlimited_holders']]) }}
                @endif
            </p>
        </studio:card>
    </div>

    <div class="grid gap-3 xl:grid-cols-2">
        <studio:card>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Collections by method') }}</p>
            @if (count($cards['mix']) === 0)
                <p class="mt-4 text-sm text-studio-muted">{{ __('dashboard.No collections this month.') }}</p>
            @else
                <div class="mt-4 space-y-3">
                    @foreach ($cards['mix'] as $row)
                        <div>
                            <div class="mb-1 flex items-center justify-between text-[11px]">
                                <span class="font-medium text-studio-fg">{{ FinanceCopy::driver($row['driver']) }}</span>
                                <span class="tabular-nums text-studio-muted">{{ Money::format($row['amount'], $cards['currency']) }} · {{ $row['share'] }}%</span>
                            </div>
                            <div class="h-1.5 overflow-hidden rounded-full bg-studio-soft">
                                <div class="h-full rounded-full bg-studio-fg" style="width: {{ min(100, $row['share']) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </studio:card>
        <studio:card>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Invoice health') }}</p>
            <div class="mt-4 grid grid-cols-4 gap-2">
                @foreach (['paid', 'open', 'failed', 'refunded'] as $key)
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-studio-muted">{{ __('dashboard.'.ucfirst($key)) }}</p>
                        <p class="mt-1 text-lg font-semibold tabular-nums text-studio-fg">{{ number_format($cards['health'][$key]) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex h-1.5 overflow-hidden rounded-full bg-studio-soft">
                <div class="bg-teal-600" style="width: {{ ($cards['health']['paid'] / $healthTotal) * 100 }}%"></div>
                <div class="bg-blue-500" style="width: {{ ($cards['health']['open'] / $healthTotal) * 100 }}%"></div>
                <div class="bg-red-500" style="width: {{ ($cards['health']['failed'] / $healthTotal) * 100 }}%"></div>
                <div class="bg-zinc-400" style="width: {{ ($cards['health']['refunded'] / $healthTotal) * 100 }}%"></div>
            </div>
        </studio:card>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <section class="space-y-3">
            <div class="flex items-end justify-between gap-3">
                <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Recent invoices') }}</h2>
                <a href="{{ route('dashboard.invoices.index') }}" wire:navigate class="text-[11px] text-studio-muted hover:text-studio-fg">{{ __('dashboard.View all') }}</a>
            </div>
            @if ($invoices->isEmpty())
                <div class="rounded-xl border border-dashed border-studio-line px-4 py-8 text-center text-sm text-studio-muted">
                    {{ __('dashboard.No invoices yet. Checkout and invoice.paid webhooks land here.') }}
                </div>
            @else
                <studio:table fit>
                    <studio:table.columns>
                        <studio:table.column>{{ __('dashboard.User') }}</studio:table.column>
                        <studio:table.column>{{ __('dashboard.Amount') }}</studio:table.column>
                        <studio:table.column align="right">{{ __('dashboard.Status') }}</studio:table.column>
                    </studio:table.columns>
                    <studio:table.rows>
                        @foreach ($invoices as $invoice)
                            @php $tone = FinanceCopy::invoice($invoice->status); @endphp
                            <studio:table.row>
                                <studio:table.cell>
                                    <a href="{{ route('dashboard.invoices.show', $invoice) }}" wire:navigate class="text-xs font-medium text-studio-fg hover:underline">
                                        {{ $invoice->user?->name ?: __('dashboard.Unlinked') }}
                                    </a>
                                    <p class="text-[11px] text-studio-muted">{{ $invoice->plan?->title ?: FinanceCopy::driver($invoice->driver) }} · {{ $invoice->paid_at?->format('M j') ?: $invoice->created_at?->format('M j') }}</p>
                                </studio:table.cell>
                                <studio:table.cell>
                                    <span class="tabular-nums text-studio-fg">{{ $invoice->formattedAmount() }}</span>
                                </studio:table.cell>
                                <studio:table.cell align="right">
                                    <studio:badge :color="$tone['color']" size="xs">{{ $tone['label'] }}</studio:badge>
                                </studio:table.cell>
                            </studio:table.row>
                        @endforeach
                    </studio:table.rows>
                </studio:table>
            @endif
        </section>

        <section class="space-y-3">
            <div class="flex items-end justify-between gap-3">
                <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Recent events') }}</h2>
                <a href="{{ route('dashboard.finance.events.index') }}" wire:navigate class="text-[11px] text-studio-muted hover:text-studio-fg">{{ __('dashboard.View all') }}</a>
            </div>
            @if ($events->isEmpty())
                <div class="rounded-xl border border-dashed border-studio-line px-4 py-8 text-center text-sm text-studio-muted">
                    {{ __('dashboard.No webhook events yet.') }}
                </div>
            @else
                <studio:table fit>
                    <studio:table.columns>
                        <studio:table.column>{{ __('dashboard.Event') }}</studio:table.column>
                        <studio:table.column align="right">{{ __('dashboard.Status') }}</studio:table.column>
                    </studio:table.columns>
                    <studio:table.rows>
                        @foreach ($events as $event)
                            @php $tone = FinanceCopy::event($event->status); @endphp
                            <studio:table.row>
                                <studio:table.cell>
                                    <a href="{{ route('dashboard.finance.events.show', $event) }}" wire:navigate class="block truncate text-xs font-medium text-studio-fg hover:underline">
                                        {{ $event->event_type }}
                                    </a>
                                    <p class="text-[11px] text-studio-muted">{{ FinanceCopy::driver($event->driver) }} · {{ $event->created_at?->format('M j, H:i') }}</p>
                                </studio:table.cell>
                                <studio:table.cell align="right">
                                    <studio:badge :color="$tone['color']" size="xs">{{ $tone['label'] }}</studio:badge>
                                </studio:table.cell>
                            </studio:table.row>
                        @endforeach
                    </studio:table.rows>
                </studio:table>
            @endif
        </section>
    </div>

    <section class="space-y-3">
        <div class="flex items-end justify-between gap-3">
            <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Recent grants') }}</h2>
            <a href="{{ route('dashboard.credits.index') }}" wire:navigate class="text-[11px] text-studio-muted hover:text-studio-fg">{{ __('dashboard.View all') }}</a>
        </div>
        @if ($grants->isEmpty())
            <div class="rounded-xl border border-dashed border-studio-line px-4 py-8 text-center text-sm text-studio-muted">
                {{ __('dashboard.No credit grants yet. Manual grants and top-ups appear here.') }}
            </div>
        @else
            <studio:table fit>
                <studio:table.columns>
                    <studio:table.column>{{ __('dashboard.User') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Amount') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Kind') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Granted by') }}</studio:table.column>
                </studio:table.columns>
                <studio:table.rows>
                    @foreach ($grants as $grant)
                        <studio:table.row>
                            <studio:table.cell>
                                <p class="text-xs font-medium text-studio-fg">{{ $grant->user?->name ?: '—' }}</p>
                                <p class="text-[11px] text-studio-muted">{{ $grant->reason ?: $grant->plan?->title }}</p>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="tabular-nums text-studio-fg">{{ $grant->amount > 0 ? '+'.$grant->amount : $grant->amount }}</span>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="text-studio-muted">{{ FinanceCopy::creditKind($grant->kind) }}</span>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="text-studio-muted">{{ $grant->actor?->name ?: '—' }}</span>
                            </studio:table.cell>
                        </studio:table.row>
                    @endforeach
                </studio:table.rows>
            </studio:table>
        @endif
    </section>
</div>
