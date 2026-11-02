<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Contacts') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Notes from the public contact page.') }}</p>
    </div>

    <div class="flex flex-wrap items-end gap-3">
        <div class="min-w-56 flex-1">
            <studio:input size="sm" wire:model.live.debounce.300ms="search" :placeholder="__('dashboard.Search…')" />
        </div>
        <div class="w-44">
            <studio:select size="sm" wire:model.live="status" :value="$status" placeholder="{{ __('dashboard.Status') }}">
                <studio:select.option value="" :selected="$status === ''">{{ __('dashboard.All') }}</studio:select.option>
                <studio:select.option value="unread" :selected="$status === 'unread'">{{ __('dashboard.Unread') }}</studio:select.option>
                <studio:select.option value="open" :selected="$status === 'open'">{{ __('dashboard.Open') }}</studio:select.option>
                <studio:select.option value="replied" :selected="$status === 'replied'">{{ __('dashboard.Replied') }}</studio:select.option>
            </studio:select>
        </div>
    </div>

    @if ($rows->isEmpty())
        <p class="text-sm text-studio-muted">{{ __('dashboard.No contacts yet.') }}</p>
    @else
        <studio:table>
            <studio:table.columns>
                <studio:table.column>{{ __('dashboard.Name') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Email') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Pack') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Status') }}</studio:table.column>
                <studio:table.column>{{ __('dashboard.Received') }}</studio:table.column>
                <studio:table.column align="right"></studio:table.column>
            </studio:table.columns>
            <studio:table.rows>
                @foreach ($rows as $row)
                    <studio:table.row :key="$row->public_id">
                        <studio:table.cell>
                            <a href="{{ route('dashboard.contacts.show', $row) }}" wire:navigate class="text-sm font-medium text-studio-fg hover:underline">
                                {{ $row->name }}
                            </a>
                        </studio:table.cell>
                        <studio:table.cell>{{ $row->email }}</studio:table.cell>
                        <studio:table.cell>{{ $row->plan?->title ?: '—' }}</studio:table.cell>
                        <studio:table.cell>
                            @if ($row->isReplied())
                                <studio:badge color="green" size="xs">{{ __('dashboard.Replied') }}</studio:badge>
                            @elseif ($row->isUnread())
                                <studio:badge color="amber" size="xs">{{ __('dashboard.Unread') }}</studio:badge>
                            @else
                                <studio:badge size="xs">{{ __('dashboard.Open') }}</studio:badge>
                            @endif
                        </studio:table.cell>
                        <studio:table.cell>{{ $row->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</studio:table.cell>
                        <studio:table.cell align="right">
                            <studio:button size="sm" variant="ghost" :href="route('dashboard.contacts.show', $row)" :navigate="true">
                                {{ __('dashboard.Open') }}
                            </studio:button>
                        </studio:table.cell>
                    </studio:table.row>
                @endforeach
            </studio:table.rows>
        </studio:table>
        <div class="mt-4">
            {{ $rows->links() }}
        </div>
    @endif
</div>
