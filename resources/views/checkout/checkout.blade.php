<x-layouts.app>
    <div class="min-h-screen bg-studio-canvas">
        <header class="border-b border-studio-line bg-studio-surface">
            <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-6 py-4">
                <a href="{{ route('pricing') }}" class="text-sm font-semibold tracking-tight text-studio-fg">{{ $site->name() }}</a>
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('dashboard.Secure checkout') }}</p>
            </div>
        </header>

        <main class="mx-auto grid max-w-5xl gap-8 px-6 py-10 lg:grid-cols-5 lg:py-16">
            <section class="border border-studio-line bg-studio-surface p-6 sm:p-8 lg:col-span-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('dashboard.Order summary') }}</p>
                <div class="mt-4 flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-semibold tracking-tight text-studio-fg">{{ $plan->title }}</h1>
                        <p class="mt-2 text-sm leading-relaxed text-studio-muted">{{ $plan->summary }}</p>
                    </div>
                    <a href="{{ route('pricing') }}" class="shrink-0 text-sm text-studio-muted underline hover:text-studio-fg">{{ __('dashboard.Change pack') }}</a>
                </div>

                <div class="mt-8 inline-flex items-center gap-1 border border-studio-line bg-studio-soft/40 p-1">
                    <a
                        href="{{ route('checkout.start', $plan) }}?interval=monthly"
                        @class([
                            'px-4 py-1.5 text-sm font-medium transition',
                            'bg-studio-fill text-studio-on-fill' => $interval === 'monthly',
                            'text-studio-muted hover:text-studio-fg' => $interval !== 'monthly',
                        ])
                    >
                        {{ __('home.Monthly') }}
                    </a>
                    <a
                        href="{{ route('checkout.start', $plan) }}?interval=yearly"
                        @class([
                            'px-4 py-1.5 text-sm font-medium transition',
                            'bg-studio-fill text-studio-on-fill' => $interval === 'yearly',
                            'text-studio-muted hover:text-studio-fg' => $interval !== 'yearly',
                        ])
                    >
                        {{ __('home.Yearly') }}
                    </a>
                </div>

                <dl class="mt-8 divide-y divide-studio-line border-y border-studio-line text-sm">
                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-studio-muted">{{ $plan->title }} · {{ $interval === 'yearly' ? __('home.Yearly') : __('home.Monthly') }}</dt>
                        <dd class="font-medium text-studio-fg">{{ $price }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-studio-muted">{{ __('dashboard.Billed today') }}</dt>
                        <dd class="text-lg font-semibold text-studio-fg">{{ $price }}</dd>
                    </div>
                </dl>

                @if ($features !== [])
                    <div class="mt-8">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('dashboard.Included in this pack') }}</p>
                        <ul class="mt-4 space-y-2.5">
                            @foreach ($features as $feature)
                                <li class="flex items-start gap-2.5 text-sm text-studio-fg">
                                    <studio:icon name="check" class="mt-0.5 size-4 text-accent-content" />
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </section>

            <aside class="lg:col-span-2">
                <section class="border border-studio-line bg-studio-surface p-6 sm:p-8">
                    <h2 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Payment') }}</h2>
                    @if ($account !== '')
                        <p class="mt-2 text-sm text-studio-muted">{{ $account }}</p>
                    @endif

                    <div class="mt-6 space-y-3">
                        @forelse ($drivers as $row)
                            <form method="GET" action="{{ route('checkout.start', $plan) }}">
                                <input type="hidden" name="interval" value="{{ $interval }}">
                                <input type="hidden" name="driver" value="{{ $row['driver'] }}">
                                <studio:button type="submit" class="w-full !rounded-none" :variant="$row['driver'] === 'stripe' ? 'primary' : 'outline'">
                                    {{ __('dashboard.Pay with :method', ['method' => $row['title']]) }}
                                </studio:button>
                            </form>
                        @empty
                            <studio:callout tone="warning">
                                <studio:callout.text>{{ __('dashboard.Checkout is not configured.') }}</studio:callout.text>
                            </studio:callout>
                        @endforelse
                    </div>

                    <p class="mt-6 flex items-start gap-2 text-xs leading-relaxed text-studio-muted">
                        <studio:icon name="lock-closed" class="mt-0.5 size-3.5 shrink-0" />
                        <span>{{ __('dashboard.You will finish payment on a secure page.') }}</span>
                    </p>
                </section>

                <p class="mt-4 text-sm text-studio-muted">
                    <a href="{{ route('pricing') }}" class="underline hover:text-studio-fg">{{ __('dashboard.Back to pricing') }}</a>
                </p>
            </aside>
        </main>
    </div>
</x-layouts.app>
