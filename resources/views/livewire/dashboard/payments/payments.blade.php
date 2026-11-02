<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Payment Methods') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Connect Stripe and PayPal. Keys stay on this workspace.') }}</p>
    </div>

    <studio:table>
        <studio:table.columns>
            <studio:table.column>{{ __('dashboard.Method') }}</studio:table.column>
            <studio:table.column>{{ __('dashboard.Mode') }}</studio:table.column>
            <studio:table.column>{{ __('dashboard.Keys') }}</studio:table.column>
            <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
            <studio:table.column align="right"></studio:table.column>
        </studio:table.columns>
        <studio:table.rows>
            @foreach ($gateways as $gateway)
                <studio:table.row>
                    <studio:table.cell>
                        <p class="text-xs font-medium text-studio-fg">{{ $gateway['title'] }}</p>
                        <p class="mt-0.5 text-[11px] text-studio-muted">{{ __("dashboard.{$gateway['summary']}") }}</p>
                    </studio:table.cell>
                    <studio:table.cell>
                        <span class="text-studio-muted">
                            {{ $gateway['mode'] === 'live' ? __('dashboard.Live') : __('dashboard.Test') }}
                        </span>
                    </studio:table.cell>
                    <studio:table.cell>
                        <span class="text-studio-fg-soft">
                            @if ($gateway['source'] === 'workspace')
                                {{ __('dashboard.Configured') }}
                            @elseif ($gateway['source'] === 'env')
                                {{ __('dashboard.Using .env') }}
                            @else
                                {{ __('dashboard.Not configured') }}
                            @endif
                        </span>
                    </studio:table.cell>
                    <studio:table.cell>
                        <div class="flex flex-wrap items-center gap-1">
                            @if ($gateway['ready'])
                                <studio:badge color="teal" size="xs">{{ __('dashboard.Ready') }}</studio:badge>
                            @elseif ($gateway['enabled'])
                                <studio:badge color="amber" size="xs">{{ __('dashboard.Needs keys') }}</studio:badge>
                            @else
                                <studio:badge size="xs">{{ __('dashboard.Off') }}</studio:badge>
                            @endif
                        </div>
                    </studio:table.cell>
                    <studio:table.cell align="right">
                        <studio:button
                            href="{{ route('dashboard.payments.edit', $gateway['driver']) }}"
                            variant="ghost"
                            square
                            size="sm"
                            aria-label="{{ __('dashboard.Edit') }}"
                            title="{{ __('dashboard.Edit') }}"
                        >
                            <studio:icon name="pencil" class="size-4" />
                        </studio:button>
                    </studio:table.cell>
                </studio:table.row>
            @endforeach
        </studio:table.rows>
    </studio:table>
</div>
