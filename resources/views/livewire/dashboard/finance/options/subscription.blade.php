@php
    use App\Finance\FinanceCopy;
@endphp

<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ $entitlement->user?->name ?: __('dashboard.Subscription') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ $entitlement->user?->email }}</p>
    </div>

    @include('livewire.dashboard.finance.options.nav')

    @php $tone = FinanceCopy::subscription($entitlement->status); @endphp

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Pack') }}</p>
            <p class="mt-2 text-sm font-medium text-studio-fg">{{ $entitlement->plan?->title ?: '—' }}</p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Status') }}</p>
            <div class="mt-2"><studio:badge :color="$tone['color']" size="xs">{{ $tone['label'] }}</studio:badge></div>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Interval') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ FinanceCopy::interval($entitlement->interval) }}</p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Last payment') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ $entitlement->last_paid_at?->format('M j, Y H:i') ?: '—' }}</p>
        </studio:card>
    </div>

    <studio:card class="space-y-4">
        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Provider') }}</p>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <p class="text-[11px] text-studio-subtle">{{ __('dashboard.Method') }}</p>
                <p class="mt-1 text-sm text-studio-fg">{{ $entitlement->driver ? FinanceCopy::driver($entitlement->driver) : '—' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-studio-subtle">{{ __('dashboard.Provider subscription') }}</p>
                <p class="mt-1 break-all text-sm text-studio-fg">{{ $entitlement->provider_subscription_id ?: '—' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-studio-subtle">{{ __('dashboard.Started') }}</p>
                <p class="mt-1 text-sm text-studio-fg">{{ $entitlement->started_at?->format('M j, Y') ?: '—' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-studio-subtle">{{ __('dashboard.Period ends') }}</p>
                <p class="mt-1 text-sm text-studio-fg">{{ $entitlement->ends_at?->format('M j, Y') ?: __('dashboard.Open') }}</p>
            </div>
        </div>
    </studio:card>

    @allows('finance.revise')
        <form wire:submit="savePack" class="space-y-4">
            <studio:field :label="__('dashboard.Change pack')">
                <studio:select size="md" wire:model="planPublicId" :value="$planPublicId" placeholder="{{ __('dashboard.Choose…') }}">
                    @foreach ($plans as $plan)
                        <studio:select.option :value="$plan->public_id" :selected="$planPublicId === $plan->public_id">
                            {{ $plan->title }}
                        </studio:select.option>
                    @endforeach
                </studio:select>
            </studio:field>
            <div class="flex flex-wrap gap-2">
                <studio:button type="submit">{{ __('dashboard.Save pack') }}</studio:button>
                <studio:button type="button" variant="outline" wire:click="askExtend">{{ __('dashboard.Extend period') }}</studio:button>
                <studio:button type="button" variant="danger" wire:click="askCancel">{{ __('dashboard.Cancel subscription') }}</studio:button>
            </div>
        </form>
    @endallows

    @if ($entitlement->invoices->isNotEmpty())
        <div class="space-y-3">
            <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Invoices') }}</h2>
            <studio:table>
                <studio:table.columns>
                    <studio:table.column>{{ __('dashboard.Amount') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Paid') }}</studio:table.column>
                </studio:table.columns>
                <studio:table.rows>
                    @foreach ($entitlement->invoices as $invoice)
                        @php $invoiceTone = FinanceCopy::invoice($invoice->status); @endphp
                        <studio:table.row>
                            <studio:table.cell>
                                <a href="{{ route('dashboard.invoices.show', $invoice) }}" wire:navigate class="text-xs font-medium text-studio-fg hover:underline">
                                    {{ $invoice->formattedAmount() }}
                                </a>
                            </studio:table.cell>
                            <studio:table.cell>
                                <studio:badge :color="$invoiceTone['color']" size="xs">{{ $invoiceTone['label'] }}</studio:badge>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="text-[11px] text-studio-muted">{{ $invoice->paid_at?->format('M j, Y') ?: '—' }}</span>
                            </studio:table.cell>
                        </studio:table.row>
                    @endforeach
                </studio:table.rows>
            </studio:table>
        </div>
    @endif

    <studio:confirm
        name="finance-subscription"
        :title="$confirmAction === 'extend' ? __('dashboard.Extend period') : __('dashboard.Cancel subscription')"
        :copy="$confirmAction === 'extend' ? __('dashboard.Add one billing period to this subscription?') : __('dashboard.Cancel this subscription?')"
    >
        <x-slot:action>
            <studio:button type="button" :variant="$confirmAction === 'extend' ? 'primary' : 'danger'" wire:click="confirmPending">
                {{ $confirmAction === 'extend' ? __('dashboard.Extend period') : __('dashboard.Cancel') }}
            </studio:button>
        </x-slot:action>
    </studio:confirm>
</div>
