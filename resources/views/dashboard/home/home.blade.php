@php
    use App\Finance\FinanceCopy;
    use App\Support\Workspace\WorkspacePulse;

    $pulsePanels = collect([$onlineHref, $turnsHref, $invoicesHref])->filter()->count();
    $cardCount = count($cards);
@endphp

<x-layouts.dashboard
    :title="__('dashboard.Overview')"
    :breadcrumbs="[['label' => __('dashboard.Overview'), 'current' => true]]"
    :wide="true"
    skeleton="page"
>
    <div class="space-y-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Overview') }}</p>
                <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-studio-fg">{{ $hello }}</h1>
                <p class="mt-1.5 max-w-lg text-sm leading-relaxed text-studio-muted">
                    {{ $workspace
                        ? __('dashboard.What moved across people, Lab chat, and billing.')
                        : __('dashboard.Your Lab, pack, and sign-in in one place.') }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <studio:button href="{{ route('lab') }}" :navigate="false" size="sm">
                    <studio:icon name="lab" class="size-4" />
                    {{ __('dashboard.Open Lab') }}
                </studio:button>
                @if ($peopleHref)
                    <studio:button href="{{ $peopleHref }}" variant="outline" size="sm">
                        {{ __('dashboard.People') }}
                    </studio:button>
                @endif
            </div>
        </div>

        @if ($cards !== [])
            <div @class([
                'grid gap-3 sm:grid-cols-2',
                'xl:grid-cols-3' => $cardCount !== 4,
                'xl:grid-cols-4' => $cardCount === 4,
            ])>
                @foreach ($cards as $card)
                    @if (filled($card['href']))
                        <a href="{{ $card['href'] }}" @if (! str_starts_with($card['href'], route('lab'))) wire:navigate @endif class="rounded-xl bg-studio-surface p-5 transition hover:bg-studio-soft">
                    @else
                        <div class="rounded-xl bg-studio-surface p-5">
                    @endif
                        <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ $card['label'] }}</p>
                        <p class="mt-3 truncate text-2xl font-semibold tabular-nums tracking-tight text-studio-fg">{{ $card['value'] }}</p>
                        <p class="mt-1.5 text-[11px] text-studio-muted">{{ $card['hint'] }}</p>
                    @if (filled($card['href']))
                        </a>
                    @else
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="grid gap-3 lg:grid-cols-2">
            <section class="rounded-xl bg-studio-surface p-4 sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Latest chat') }}</h2>
                    <div class="flex items-center gap-3">
                        @if ($attentionCount > 0)
                            <a href="{{ route('dashboard.lab.turns') }}" wire:navigate class="text-[11px] font-medium text-red-600 hover:underline dark:text-red-400">
                                {{ __('dashboard.:count need a look', ['count' => $attentionCount]) }}
                            </a>
                        @endif
                        <a
                            href="{{ $chatHref }}"
                            @if ($workspace) wire:navigate @endif
                            class="text-[11px] text-studio-muted hover:text-studio-fg"
                        >{{ __('dashboard.View all') }}</a>
                    </div>
                </div>

                @if ($chats->isEmpty())
                    <p class="py-10 text-sm text-studio-muted">{{ __('dashboard.No Lab chat yet.') }}</p>
                @else
                    <div class="mt-4 space-y-2">
                        @foreach ($chats as $message)
                            @php
                                $project = $message->project;
                                $fromLab = $message->role === 'assistant';
                                $href = $project
                                    ? ($workspace ? route('dashboard.lab.show', $project) : route('lab.show', $project))
                                    : $chatHref;
                            @endphp
                            <a
                                href="{{ $href }}"
                                @if ($workspace) wire:navigate @endif
                                @class([
                                    'flex gap-3 rounded-xl p-3 transition',
                                    'bg-studio-soft' => $fromLab,
                                    'hover:bg-studio-soft' => ! $fromLab,
                                ])
                            >
                                @if ($fromLab)
                                    <span class="mt-0.5 inline-flex size-7 shrink-0 items-center justify-center rounded-full bg-accent/15 text-accent-content">
                                        <studio:icon name="lab" class="size-3.5" />
                                    </span>
                                @else
                                    <studio:avatar
                                        :name="$project?->user?->name"
                                        :src="$project?->user?->avatarUrl()"
                                        size="xs"
                                    />
                                @endif
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-baseline justify-between gap-3">
                                        <p class="truncate text-xs font-medium text-studio-fg">
                                            {{ WorkspacePulse::speaker($message) }}
                                            <span class="font-normal text-studio-subtle">{{ $project?->title ?: __('dashboard.Untitled') }}</span>
                                        </p>
                                        <time class="shrink-0 text-[11px] text-studio-subtle">{{ $message->created_at?->diffForHumans(short: true) }}</time>
                                    </div>
                                    <p class="mt-1 text-sm leading-snug text-studio-fg-soft">{{ WorkspacePulse::snippet($message->content) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="rounded-xl bg-studio-surface p-4 sm:p-5">
                @if ($workspace)
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Latest people') }}</h2>
                        @if ($peopleHref)
                            <a href="{{ $peopleHref }}" wire:navigate class="text-[11px] text-studio-muted hover:text-studio-fg">{{ __('dashboard.View all') }}</a>
                        @endif
                    </div>
                    @if ($people->isEmpty())
                        <p class="py-10 text-sm text-studio-muted">{{ __('dashboard.No people yet.') }}</p>
                    @else
                        <ul class="mt-3 space-y-1">
                            @foreach ($people as $person)
                                <li>
                                    @if (auth()->user()?->allows('users.revise'))
                                        <a href="{{ route('dashboard.users.edit', $person) }}" wire:navigate class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-studio-soft">
                                    @else
                                        <div class="flex items-center gap-3 rounded-xl px-2 py-2.5">
                                    @endif
                                        <studio:avatar :name="$person->name" :src="$person->avatarUrl()" size="xs" />
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-xs font-medium text-studio-fg">{{ $person->name }}</p>
                                            <p class="truncate text-[11px] text-studio-muted">{{ $person->email }}</p>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            <p class="text-[11px] text-studio-muted">{{ $person->accessRole?->title ?: __('dashboard.Member') }}</p>
                                            <p class="text-[11px] text-studio-subtle">{{ $person->created_at?->diffForHumans(short: true) }}</p>
                                        </div>
                                    @if (auth()->user()?->allows('users.revise'))
                                        </a>
                                    @else
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Profile') }}</h2>
                    <p class="mt-1 text-[11px] text-studio-muted">{{ __('dashboard.Name, password, and two-factor live under Profile.') }}</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <studio:button href="{{ route('dashboard.profile.index') }}" variant="outline" size="sm">
                            {{ __('dashboard.Profile') }}
                        </studio:button>
                        <studio:button href="{{ route('dashboard.profile.password') }}" variant="outline" size="sm">
                            {{ __('dashboard.Password') }}
                        </studio:button>
                        <studio:button href="{{ route('dashboard.profile.two-factor') }}" variant="outline" size="sm">
                            {{ __('dashboard.Two-factor') }}
                        </studio:button>
                    </div>
                @endif
            </section>
        </div>

        @if ($pulsePanels > 0)
            <div @class([
                'grid gap-3',
                'lg:grid-cols-2' => $pulsePanels === 2,
                'lg:grid-cols-3' => $pulsePanels >= 3,
            ])>
                @if ($onlineHref)
                    <section class="rounded-xl bg-studio-surface p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Signed in') }}</h2>
                            <a href="{{ $onlineHref }}" wire:navigate class="text-[11px] text-studio-muted hover:text-studio-fg">{{ __('dashboard.View all') }}</a>
                        </div>
                        @if ($online->isEmpty())
                            <p class="py-10 text-sm text-studio-muted">{{ __('dashboard.Nobody is signed in.') }}</p>
                        @else
                            <ul class="mt-3 space-y-1">
                                @foreach ($online as $session)
                                    @php $person = $session->user; @endphp
                                    <li>
                                        @if (auth()->user()?->allows('users.revise') && $person)
                                            <a href="{{ route('dashboard.users.edit', $person) }}" wire:navigate class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-studio-soft">
                                        @else
                                            <div class="flex items-center gap-3 rounded-xl px-2 py-2.5">
                                        @endif
                                            <studio:avatar :name="$person?->name" :src="$person?->avatarUrl()" size="xs" />
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-xs font-medium text-studio-fg">{{ $person?->name }}</p>
                                                <p class="truncate text-[11px] text-studio-muted">{{ $person?->accessRole?->title ?: __('dashboard.Member') }}</p>
                                            </div>
                                            <p class="shrink-0 text-[11px] text-studio-subtle">{{ $session->lastActivityAt()->diffForHumans(short: true) }}</p>
                                        @if (auth()->user()?->allows('users.revise') && $person)
                                            </a>
                                        @else
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                @endif

                @if ($turnsHref)
                    <section class="rounded-xl bg-studio-surface p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Needs a look') }}</h2>
                            <a href="{{ $turnsHref }}" wire:navigate class="text-[11px] text-studio-muted hover:text-studio-fg">{{ __('dashboard.View all') }}</a>
                        </div>
                        @if ($flagged->isEmpty())
                            <p class="py-10 text-sm text-studio-muted">{{ __('dashboard.All turns look fine.') }}</p>
                        @else
                            <div class="mt-3 space-y-1">
                                @foreach ($flagged as $row)
                                    @php
                                        $message = $row['message'];
                                        $project = $message->project;
                                        $kind = $row['kind'];
                                        $status = \App\Lab\LabTurnHealth::statusLabel($message, $kind);
                                        $href = $project ? route('dashboard.lab.show', $project) : $turnsHref;
                                    @endphp
                                    <a href="{{ $href }}" wire:navigate class="flex items-start gap-3 rounded-xl px-2 py-2.5 transition hover:bg-studio-soft">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-3">
                                                <p class="truncate text-xs font-medium text-studio-fg">{{ $project?->title ?: __('dashboard.Untitled') }}</p>
                                                <studio:badge size="xs" :color="$kind === 'stuck' ? 'amber' : 'red'">{{ $status }}</studio:badge>
                                            </div>
                                            <p class="mt-1 truncate text-[11px] text-studio-muted">{{ $project?->user?->name ?: __('dashboard.Unassigned') }} · {{ $message->updated_at?->diffForHumans(short: true) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endif

                @if ($invoicesHref)
                    <section class="rounded-xl bg-studio-surface p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Recent invoices') }}</h2>
                            <a href="{{ $invoicesHref }}" wire:navigate class="text-[11px] text-studio-muted hover:text-studio-fg">{{ __('dashboard.View all') }}</a>
                        </div>
                        @if ($invoices->isEmpty())
                            <p class="py-10 text-sm text-studio-muted">{{ __('dashboard.No invoices yet.') }}</p>
                        @else
                            <ul class="mt-3 space-y-1">
                                @foreach ($invoices as $invoice)
                                    @php $tone = FinanceCopy::invoice($invoice->status); @endphp
                                    <li>
                                        <a href="{{ route('dashboard.invoices.show', $invoice) }}" wire:navigate class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-studio-soft">
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-xs font-medium tabular-nums text-studio-fg">{{ $invoice->formattedAmount() }}</p>
                                                <p class="truncate text-[11px] text-studio-muted">{{ $invoice->user?->name ?: __('dashboard.Unlinked') }} · {{ $invoice->plan?->title ?: FinanceCopy::driver($invoice->driver) }}</p>
                                            </div>
                                            <studio:badge size="xs" :color="$tone['color']">{{ $tone['label'] }}</studio:badge>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                @endif
            </div>
        @endif

        <div class="grid gap-3 lg:grid-cols-2">
            <section class="rounded-xl bg-studio-surface p-4 sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-medium text-studio-fg">
                        {{ $workspace ? __('dashboard.Recent projects') : __('dashboard.Your projects') }}
                    </h2>
                    @if ($projectsHref)
                        <a href="{{ $projectsHref }}" wire:navigate class="text-[11px] text-studio-muted hover:text-studio-fg">{{ __('dashboard.View all') }}</a>
                    @elseif (! $workspace)
                        <a href="{{ route('lab') }}" class="inline-flex items-center gap-1.5 text-[11px] text-studio-muted hover:text-studio-fg">
                            <studio:icon name="lab" class="size-3.5" />
                            {{ __('dashboard.Open Lab') }}
                        </a>
                    @endif
                </div>
                @if ($projects->isEmpty())
                    <p class="py-10 text-sm text-studio-muted">
                        {{ $workspace ? __('dashboard.No Lab projects yet.') : __('dashboard.No projects yet. Open Lab to start one.') }}
                    </p>
                @else
                    <ul class="mt-3 space-y-1">
                        @foreach ($projects as $project)
                            <li>
                                <a
                                    href="{{ $workspace ? route('dashboard.lab.show', $project) : route('lab.show', $project) }}"
                                    @if ($workspace) wire:navigate @endif
                                    class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-studio-soft"
                                >
                                    <studio:avatar :name="$project->user?->name" :src="$project->user?->avatarUrl()" size="xs" />
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-medium text-studio-fg">{{ $project->title ?: __('dashboard.Untitled') }}</p>
                                        <p class="truncate text-[11px] text-studio-muted">
                                            {{ $project->user?->name ?: __('dashboard.Unassigned') }}
                                            @if ($project->isFrozen())
                                                · {{ __('dashboard.Frozen') }}
                                            @endif
                                        </p>
                                    </div>
                                    <p class="shrink-0 text-[11px] text-studio-subtle">{{ $project->updated_at?->diffForHumans(short: true) }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            @if ($subscriptionsHref)
                <section class="rounded-xl bg-studio-surface p-4 sm:p-5">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Subscriptions') }}</h2>
                        <a href="{{ $subscriptionsHref }}" wire:navigate class="text-[11px] text-studio-muted hover:text-studio-fg">{{ __('dashboard.View all') }}</a>
                    </div>
                    @if ($subscriptions->isEmpty())
                        <p class="py-10 text-sm text-studio-muted">{{ __('dashboard.No subscriptions yet.') }}</p>
                    @else
                        <ul class="mt-3 space-y-1">
                            @foreach ($subscriptions as $row)
                                @php $tone = FinanceCopy::subscription($row->status); @endphp
                                <li>
                                    <a href="{{ route('dashboard.finance.subscriptions.show', $row) }}" wire:navigate class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-studio-soft">
                                        <studio:avatar :name="$row->user?->name" :src="$row->user?->avatarUrl()" size="xs" />
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-xs font-medium text-studio-fg">{{ $row->user?->name ?: __('dashboard.Unlinked') }}</p>
                                            <p class="truncate text-[11px] text-studio-muted">{{ $row->plan?->title ?: '—' }} · {{ FinanceCopy::interval($row->interval) }}</p>
                                        </div>
                                        <studio:badge size="xs" :color="$tone['color']">{{ $tone['label'] }}</studio:badge>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            @elseif ($ownPack)
                <section class="rounded-xl bg-studio-surface p-4 sm:p-5">
                    <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Your pack') }}</h2>
                    <p class="mt-1 text-[11px] text-studio-muted">{{ __('dashboard.Your current plan.') }}</p>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-xl bg-studio-soft px-3 py-3">
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Pack') }}</p>
                            <p class="mt-1.5 truncate text-sm font-medium text-studio-fg">{{ $ownPack['title'] }}</p>
                        </div>
                        <div class="rounded-xl bg-studio-soft px-3 py-3">
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.This period') }}</p>
                            <p class="mt-1.5 truncate text-sm font-medium tabular-nums text-studio-fg">{{ $ownPack['remaining'] }}</p>
                            <p class="mt-1 text-[11px] text-studio-muted">{{ $ownPack['hint'] }}</p>
                        </div>
                        <div class="rounded-xl bg-studio-soft px-3 py-3">
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Two-factor') }}</p>
                            <p class="mt-1.5 text-sm font-medium text-studio-fg">{{ $ownPack['twoFactor'] }}</p>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
