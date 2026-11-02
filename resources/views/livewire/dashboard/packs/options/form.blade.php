@props([
    'catalog',
    'builtIn' => false,
    'creditExamples' => [],
])

@php
    use App\Entitlement\GrantKind;
    use App\Entitlement\UsageWindow;
@endphp

<div class="space-y-4">
    <studio:card class="space-y-4 !border-0 !p-5">
        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('dashboard.Identity') }}</p>

        <studio:field :label="__('dashboard.Title')">
            <studio:input wire:model="title" id="title" :placeholder="__('dashboard.Pro')" :invalid="$errors->has('title')" required />
            @error('title')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>

        <studio:field :label="__('dashboard.Slug')">
            <studio:input wire:model="slug" id="slug" :placeholder="__('dashboard.pro')" :invalid="$errors->has('slug')" :disabled="$builtIn" />
            @error('slug')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>

        <studio:field :label="__('dashboard.Summary')">
            <studio:input wire:model="summary" id="summary" :placeholder="__('dashboard.For growing teams')" :invalid="$errors->has('summary')" />
            @error('summary')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>

        <studio:field :label="__('dashboard.Rank')">
            <studio:input type="number" min="0" max="999" wire:model="rank" id="rank" :placeholder="__('dashboard.10')" :invalid="$errors->has('rank')" />
            @error('rank')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>

        <div class="flex flex-col gap-3 pt-1">
            <studio:switch wire:model="isDefault" :label="__('dashboard.Default pack')" />
            <p class="text-xs text-studio-muted">{{ __('dashboard.New accounts receive this pack.') }}</p>
            @error('isDefault')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror

            <studio:switch wire:model="isActive" :label="__('dashboard.Available to assign')" />
            <p class="text-xs text-studio-muted">{{ __('dashboard.Locked packs stay on current holders.') }}</p>
            @error('isActive')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </div>
    </studio:card>

    <studio:card class="space-y-4 !border-0 !p-5">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('dashboard.Pricing') }}</p>
            <p class="mt-1 text-xs text-studio-muted">{{ __('dashboard.List prices for monthly and yearly billing. Leave blank if unset.') }}</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <studio:field :label="__('dashboard.Monthly price')">
                <studio:input
                    type="text"
                    inputmode="decimal"
                    autocomplete="off"
                    wire:model="priceMonthly"
                    id="priceMonthly"
                    :placeholder="__('dashboard.29.00')"
                    :invalid="$errors->has('priceMonthly')"
                >
                    <x-slot:prefix>{{ $this->packCurrencySymbol() }}</x-slot:prefix>
                    <x-slot:suffix>{{ $this->packCurrency() }}</x-slot:suffix>
                </studio:input>
                <x-slot:description>{{ __('dashboard.Billed each month. 0 is free.') }}</x-slot:description>
                @error('priceMonthly')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Yearly price')">
                <studio:input
                    type="text"
                    inputmode="decimal"
                    autocomplete="off"
                    wire:model="priceYearly"
                    id="priceYearly"
                    :placeholder="__('dashboard.290.00')"
                    :invalid="$errors->has('priceYearly')"
                >
                    <x-slot:prefix>{{ $this->packCurrencySymbol() }}</x-slot:prefix>
                    <x-slot:suffix>{{ $this->packCurrency() }}</x-slot:suffix>
                </studio:input>
                <x-slot:description>{{ __('dashboard.Billed once a year. Usually a discount vs 12 months.') }}</x-slot:description>
                @error('priceYearly')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </div>
    </studio:card>

    <studio:card class="space-y-5 !border-0 !p-5">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('dashboard.Grants') }}</p>

        @foreach ($catalog as $definition)
            @php
                $code = $definition['code'];
                $title = __('dashboard.'.$definition['title']);
            @endphp

            <div class="space-y-3 border-t border-studio-line pt-4 first:border-t-0 first:pt-0">
                <p class="text-sm font-medium text-studio-fg">{{ $title }}</p>

                @if ($definition['kind'] === GrantKind::Feature)
                    <studio:switch wire:model="grants.{{ $code }}.allowed" :label="__('dashboard.Included')" />
                    @error("grants.$code.allowed")
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                    @if ($code === 'custom_subdomain')
                        <p class="text-xs text-studio-muted">{{ __('dashboard.Choose a readable slug. Without this, Lab assigns a random 12-character address.') }}</p>
                    @elseif ($code === 'custom_domain')
                        <p class="text-xs text-studio-muted">{{ __('dashboard.Point a domain at a published Lab site.') }}</p>
                    @endif
                @else
                    <studio:switch wire:model.live="grants.{{ $code }}.unlimited" :label="__('dashboard.Unlimited')" />
                    @unless ($grants[$code]['unlimited'] ?? false)
                        <studio:field :label="__('dashboard.Ceiling')">
                            <studio:input
                                type="number"
                                min="0"
                                max="1000000"
                                wire:model="grants.{{ $code }}.ceiling"
                                :placeholder="__('dashboard.1000')"
                                :invalid="$errors->has('grants.'.$code.'.ceiling')"
                            />
                            @error("grants.$code.ceiling")
                                <studio:field.error>{{ $message }}</studio:field.error>
                            @enderror
                        </studio:field>
                    @endunless
                    <p class="text-xs text-studio-muted">
                        @if ($definition['window'] === UsageWindow::Monthly)
                            {{ __('dashboard.Charged from real Lab usage, not per message. Resets each month.') }}
                        @elseif ($definition['code'] === 'projects')
                            {{ __('dashboard.Counts every project you keep.') }}
                        @else
                            {{ __('dashboard.Lifetime quota.') }}
                        @endif
                    </p>
                @endif
            </div>
        @endforeach
    </studio:card>

    @if ($creditExamples !== [])
        <studio:card class="space-y-4 !border-0 !p-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('dashboard.Example Lab credit charges') }}</p>
                <p class="mt-1 text-xs text-studio-muted">{{ __('dashboard.Sample charges from real usage. Use these when setting a monthly ceiling.') }}</p>
            </div>

            <studio:table>
                <studio:table.columns>
                    <studio:table.column>{{ __('dashboard.Scenario') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Model') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.In') }}</studio:table.column>
                    <studio:table.column>{{ __('dashboard.Out') }}</studio:table.column>
                    <studio:table.column align="right">{{ __('dashboard.Credits') }}</studio:table.column>
                </studio:table.columns>
                <studio:table.rows>
                    @foreach ($creditExamples as $example)
                        <studio:table.row>
                            <studio:table.cell>
                                <span class="text-studio-fg">{{ $example['scenario'] }}</span>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="text-studio-fg-soft">{{ $example['model'] }}</span>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="tabular-nums text-studio-muted">{{ $example['input'] }}</span>
                            </studio:table.cell>
                            <studio:table.cell>
                                <span class="tabular-nums text-studio-muted">{{ $example['output'] }}</span>
                            </studio:table.cell>
                            <studio:table.cell align="right">
                                <span class="tabular-nums font-medium text-studio-fg">{{ number_format((int) $example['credits']) }}</span>
                            </studio:table.cell>
                        </studio:table.row>
                    @endforeach
                </studio:table.rows>
            </studio:table>
        </studio:card>
    @endif
</div>
