<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ $project->title ?: __('dashboard.Untitled') }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">
                {{ $project->user?->name ?: __('dashboard.Unassigned') }}
                @if ($project->isFrozen())
                    · {{ __('dashboard.Frozen') }}
                @endif
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <studio:button href="{{ route('lab.show', $project) }}" variant="outline" size="sm">
                <studio:icon name="lab" class="size-4" />
                {{ __('dashboard.Open in Lab') }}
            </studio:button>
            @allows('projects.revise')
                <studio:button type="button" variant="ghost" size="sm" wire:click="askFreeze">
                    {{ $project->isFrozen() ? __('dashboard.Unfreeze') : __('dashboard.Freeze') }}
                </studio:button>
            @endallows
            @allows('projects.retire')
                <studio:button type="button" variant="danger" size="sm" wire:click="askDelete">
                    {{ __('dashboard.Delete') }}
                </studio:button>
            @endallows
        </div>
    </div>

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl bg-studio-surface p-4 sm:p-5">
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Stack') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ $project->stack ?: '—' }}</p>
            <p class="mt-1 text-[11px] text-studio-muted">{{ __('dashboard.Workspace') }} · {{ $project->workspace_status ?: '—' }}</p>
        </div>
        <div class="rounded-xl bg-studio-surface p-4 sm:p-5">
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Credits') }}</p>
            <p class="mt-2 text-sm tabular-nums text-studio-fg">{{ number_format((int) $project->credits_spent) }}</p>
        </div>
        <div class="rounded-xl bg-studio-surface p-4 sm:p-5">
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Messages') }}</p>
            <p class="mt-2 text-sm tabular-nums text-studio-fg">{{ number_format($project->messages->count()) }}</p>
        </div>
        <div class="rounded-xl bg-studio-surface p-4 sm:p-5">
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Updated') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ $project->updated_at?->diffForHumans() ?: '—' }}</p>
        </div>
    </div>

    <div class="space-y-4 rounded-xl bg-studio-surface p-4 sm:p-5">
        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Owner') }}</p>
        @if ($project->user)
            <div>
                <p class="text-sm text-studio-fg">{{ $project->user->name }}</p>
                <p class="text-xs text-studio-muted">{{ $project->user->email }}</p>
            </div>
        @else
            <p class="text-sm text-studio-muted">{{ __('dashboard.Unassigned') }}</p>
        @endif
        @allows('projects.revise')
            <form wire:submit="saveOwner" class="flex flex-wrap items-end gap-2">
                <studio:field :label="__('dashboard.Transfer to')" class="min-w-56 flex-1">
                    <studio:select size="md" wire:model="ownerPublicId" :value="$ownerPublicId" searchable>
                        @foreach ($owners as $owner)
                            <studio:select.option :value="$owner->public_id" :selected="$ownerPublicId === $owner->public_id">
                                {{ $owner->name }} · {{ $owner->email }}
                            </studio:select.option>
                        @endforeach
                    </studio:select>
                </studio:field>
                <studio:button type="submit" size="sm">{{ __('dashboard.Save') }}</studio:button>
            </form>
        @endallows
    </div>

    <div class="space-y-3">
        <h2 class="text-sm font-medium text-studio-fg">{{ __('dashboard.Messages') }}</h2>
        @if ($project->messages->isEmpty())
            <p class="text-sm text-studio-muted">{{ __('dashboard.No messages yet.') }}</p>
        @else
            <div class="space-y-2">
                @foreach ($project->messages as $message)
                    @php
                        $usage = is_array($message->metadata['usage'] ?? null) ? $message->metadata['usage'] : null;
                        $turnStatus = $message->metadata['turnStatus'] ?? null;
                    @endphp
                    <div class="rounded-xl bg-studio-surface px-3 py-2.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <studio:badge size="xs" :color="$message->role === 'assistant' ? 'blue' : 'zinc'">
                                {{ $message->role === 'assistant' ? __('dashboard.Assistant') : __('dashboard.User') }}
                            </studio:badge>
                            @if (filled($turnStatus))
                                <span class="text-[11px] text-studio-muted">{{ $turnStatus }}</span>
                            @endif
                            @if (is_array($usage))
                                <span class="text-[11px] text-studio-muted">
                                    {{ $usage['model'] ?? '' }}
                                    @if (isset($usage['credits']))
                                        · {{ number_format((int) $usage['credits']) }} {{ __('dashboard.Credits') }}
                                    @endif
                                </span>
                            @endif
                            <span class="ms-auto text-[11px] text-studio-subtle">{{ $message->created_at?->diffForHumans() }}</span>
                        </div>
                        @if (filled(trim((string) $message->content)))
                            <p class="mt-1.5 whitespace-pre-wrap break-words text-xs leading-relaxed text-studio-fg-soft">{{ \Illuminate\Support\Str::limit(trim($message->content), 480) }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <studio:confirm
        name="lab-project-confirm"
        :title="$confirmAction === 'delete' ? __('dashboard.Delete project') : __('dashboard.Freeze project')"
        :copy="$confirmAction === 'delete' ? __('dashboard.Delete this project and its workspace files?') : __('dashboard.Frozen projects cannot chat or write files.')"
    >
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="confirmPending">
                {{ $confirmAction === 'delete' ? __('dashboard.Delete') : __('dashboard.Freeze') }}
            </studio:button>
        </x-slot:action>
    </studio:confirm>
</div>
