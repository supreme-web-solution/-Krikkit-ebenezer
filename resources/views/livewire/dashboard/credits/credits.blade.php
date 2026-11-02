@php
    use App\Finance\FinanceCopy;
@endphp

<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Credits') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Purchased credits, manual grants, and adjustments. Usage stays in Lab.') }}</p>
    </div>

    @allows('finance.revise')
        <form wire:submit="grant" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.User')">
                    <studio:select size="md" searchable wire:model="userPublicId" :value="$userPublicId" placeholder="{{ __('dashboard.Choose…') }}" :invalid="$errors->has('userPublicId')">
                        @foreach ($people as $person)
                            <studio:select.option :value="$person->public_id" :selected="$userPublicId === $person->public_id">
                                {{ $person->name }} · {{ $person->email }}
                            </studio:select.option>
                        @endforeach
                    </studio:select>
                    @error('userPublicId')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Kind')">
                    <studio:select size="md" wire:model.live="kind" :value="$kind" placeholder="{{ __('dashboard.Choose…') }}">
                        <studio:select.option value="grant" :selected="$kind === 'grant'">{{ __('dashboard.Manual grant') }}</studio:select.option>
                        <studio:select.option value="topup" :selected="$kind === 'topup'">{{ __('dashboard.Top-up') }}</studio:select.option>
                        <studio:select.option value="adjustment" :selected="$kind === 'adjustment'">{{ __('dashboard.Adjustment') }}</studio:select.option>
                    </studio:select>
                </studio:field>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @if ($kind === 'topup')
                    <studio:field :label="__('dashboard.Top-up pack')">
                        <studio:select size="md" wire:model="planPublicId" :value="$planPublicId" placeholder="{{ __('dashboard.Choose…') }}" :invalid="$errors->has('planPublicId')">
                            @foreach ($plans as $plan)
                                <studio:select.option :value="$plan->public_id" :selected="$planPublicId === $plan->public_id">
                                    {{ $plan->title }}
                                </studio:select.option>
                            @endforeach
                        </studio:select>
                        @error('planPublicId')
                            <studio:field.error>{{ $message }}</studio:field.error>
                        @enderror
                    </studio:field>
                @else
                    <studio:field :label="__('dashboard.Amount')">
                        <studio:input size="md" type="number" wire:model="amount" placeholder="+100" :invalid="$errors->has('amount')" />
                        @error('amount')
                            <studio:field.error>{{ $message }}</studio:field.error>
                        @enderror
                    </studio:field>
                @endif
                <studio:field :label="__('dashboard.Reason')">
                    <studio:input size="md" wire:model="reason" :placeholder="__('dashboard.Why these credits were added.')" />
                </studio:field>
            </div>

            <studio:button type="submit">{{ __('dashboard.Grant credits') }}</studio:button>
        </form>
    @endallows

    <div class="flex flex-wrap items-end gap-3">
        <div class="min-w-56 flex-1">
            <studio:input size="sm" wire:model.live.debounce.300ms="search" :placeholder="__('dashboard.Search…')" />
        </div>
    </div>

    @if ($rows->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No credit grants yet. Manual grants and top-ups appear here.') }}</p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.User') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Amount') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Kind') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Reason') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Granted by') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.When') }}</studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($rows as $grant)
                    <studio:table.row>
                        <studio:table.cell>
                            <p class="truncate text-xs font-medium text-studio-fg">{{ $grant->user?->name ?: '—' }}</p>
                            <p class="truncate text-[11px] text-studio-muted">{{ $grant->user?->email }}</p>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="tabular-nums font-medium text-studio-fg">{{ $grant->amount > 0 ? '+'.$grant->amount : $grant->amount }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ FinanceCopy::creditKind($grant->kind) }}</span>
                            @if ($grant->plan)
                                <p class="text-[11px] text-studio-subtle">{{ $grant->plan->title }}</p>
                            @endif
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $grant->reason ?: '—' }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-studio-muted">{{ $grant->actor?->name ?: '—' }}</span>
                        </studio:table.cell>
                        <studio:table.cell>
                            <span class="text-[11px] text-studio-muted">{{ $grant->created_at?->format('M j, Y H:i') }}</span>
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
