<div class="space-y-8">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ $contact->name }}</h1>
            <p class="mt-0.5 text-xs text-studio-muted">{{ $contact->email }}</p>
        </div>
        @allows('contacts.revise')
            <studio:button type="button" variant="ghost" wire:click="askRemove">{{ __('dashboard.Remove') }}</studio:button>
        @endallows
    </div>

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Status') }}</p>
            <div class="mt-2">
                @if ($contact->isReplied())
                    <studio:badge color="green" size="xs">{{ __('dashboard.Replied') }}</studio:badge>
                @elseif ($contact->isUnread())
                    <studio:badge color="amber" size="xs">{{ __('dashboard.Unread') }}</studio:badge>
                @else
                    <studio:badge size="xs">{{ __('dashboard.Open') }}</studio:badge>
                @endif
            </div>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Pack') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ $contact->plan?->title ?: '—' }}</p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Received') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ $contact->created_at?->format('M j, Y H:i') ?: '—' }}</p>
        </studio:card>
        <studio:card>
            <p class="text-[11px] uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Replied') }}</p>
            <p class="mt-2 text-sm text-studio-fg">{{ $contact->replied_at?->format('M j, Y H:i') ?: '—' }}</p>
        </studio:card>
    </div>

    <studio:card class="space-y-3">
        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Message') }}</p>
        <p class="whitespace-pre-wrap text-sm leading-relaxed text-studio-fg">{{ $contact->body }}</p>
    </studio:card>

    @if ($contact->isReplied())
        <studio:card class="space-y-3">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Your reply') }}</p>
            <p class="whitespace-pre-wrap text-sm leading-relaxed text-studio-fg">{{ $contact->reply_body }}</p>
            @if ($contact->repliedBy)
                <p class="text-xs text-studio-muted">{{ $contact->repliedBy->name }}</p>
            @endif
        </studio:card>
    @endif

    @allows('contacts.revise')
        <form wire:submit="send" class="space-y-4">
            <studio:field :label="$contact->isReplied() ? __('dashboard.Send another reply') : __('dashboard.Reply')">
                <studio:textarea rows="8" wire:model="reply" :placeholder="__('dashboard.Write a reply…')" :invalid="$errors->has('reply')" />
                @error('reply')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
            <studio:button type="submit">{{ __('dashboard.Send reply') }}</studio:button>
        </form>
    @endallows

    <studio:confirm name="remove-contact" :title="__('dashboard.Remove contact')" :copy="__('dashboard.Remove this note from the inbox?')">
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="confirmPending">
                {{ __('dashboard.Remove') }}
            </studio:button>
        </x-slot:action>
    </studio:confirm>
</div>
