<div class="space-y-6">
    <div class="flex items-center gap-3">
        <studio:button
            href="{{ route('dashboard.payments.index') }}"
            variant="ghost"
            square
            size="sm"
            aria-label="{{ __('dashboard.Back') }}"
            title="{{ __('dashboard.Back') }}"
        >
            <studio:icon name="arrow-left" class="size-4" />
        </studio:button>
        <h1 class="text-2xl font-semibold tracking-tight text-studio-fg">{{ $definition['title'] }}</h1>
    </div>

    <form wire:submit="save" class="mx-auto max-w-2xl space-y-6" autocomplete="off">
        <div class="sr-only" aria-hidden="true">
            <input type="text" name="prevent_autofill_user" autocomplete="username" tabindex="-1" value="">
            <input type="password" name="prevent_autofill_pass" autocomplete="current-password" tabindex="-1" value="">
        </div>

        @if ($row['unreadable'] ?? false)
            <studio:callout tone="warning">
                <studio:callout.heading>{{ __('dashboard.Saved key cannot be read') }}</studio:callout.heading>
                <studio:callout.text>{{ __('dashboard.The saved secret can no longer be read. Paste it again to restore this provider.') }}</studio:callout.text>
            </studio:callout>
        @endif

        <studio:field :label="__('dashboard.'.$definition['public_label'])">
            <studio:input
                type="text"
                wire:model="publicKey"
                autocomplete="off"
                autocorrect="off"
                spellcheck="false"
                data-1p-ignore
                data-lpignore="true"
                readonly
                x-on:focus="$el.removeAttribute('readonly')"
                :placeholder="$row['has_public'] ? __('dashboard.•••• keep current') : __('dashboard.'.$definition['public_label'])"
            />
        </studio:field>
        @if ($row['has_public'])
            <studio:checkbox :label="__('dashboard.Clear stored public key')" wire:model="clearPublic" />
        @endif

        <studio:field :label="__('dashboard.'.$definition['secret_label'])">
            <studio:input
                type="password"
                wire:model="secretKey"
                autocomplete="new-password"
                data-1p-ignore
                data-lpignore="true"
                readonly
                x-on:focus="$el.removeAttribute('readonly')"
                :placeholder="$row['has_secret'] ? __('dashboard.•••• keep current') : __('dashboard.'.$definition['secret_label'])"
            />
        </studio:field>
        @if ($row['has_secret'])
            <studio:checkbox :label="__('dashboard.Clear stored secret')" wire:model="clearSecret" />
        @endif

        <studio:field :label="__('dashboard.'.$definition['webhook_label'])">
            <studio:input
                type="password"
                wire:model="webhookSecret"
                autocomplete="new-password"
                data-1p-ignore
                data-lpignore="true"
                readonly
                x-on:focus="$el.removeAttribute('readonly')"
                :placeholder="$row['has_webhook'] ? __('dashboard.•••• keep current') : __('dashboard.'.$definition['webhook_label'])"
            />
        </studio:field>
        @if ($row['has_webhook'])
            <studio:checkbox :label="__('dashboard.Clear stored webhook secret')" wire:model="clearWebhook" />
        @endif

        <div
            class="space-y-1"
            x-data="{
                copied: false,
                async copy() {
                    try {
                        await navigator.clipboard.writeText(@js($row['webhook_url']))
                        this.copied = true
                        clearTimeout(this._copyTimer)
                        this._copyTimer = setTimeout(() => { this.copied = false }, 1600)
                    } catch (e) {}
                },
            }"
        >
            <p class="text-xs font-medium text-studio-fg-soft">{{ __('dashboard.Webhook URL') }}</p>
            <div class="flex min-w-0 items-center gap-2">
                <span class="min-w-0 truncate text-xs text-studio-muted">{{ $row['webhook_url'] }}</span>
                <button
                    type="button"
                    class="shrink-0 text-xs font-medium text-studio-muted transition hover:text-studio-fg"
                    x-on:click="copy()"
                    x-bind:aria-label="copied ? @js(__('dashboard.Copied')) : @js(__('dashboard.Copy'))"
                >
                    <span x-text="copied ? @js(__('dashboard.Copied')) : @js(__('dashboard.Copy'))"></span>
                </button>
            </div>
            <p class="text-xs text-studio-muted">{{ __('dashboard.Paste this URL into the provider webhook settings.') }}</p>
        </div>

        <div class="grid grid-cols-2 gap-6 border-t border-studio-line pt-4">
            <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Enabled')" wire:model="enabled" />
            <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Live mode')" wire:model="live" />
        </div>

        <div class="flex justify-end">
            <studio:button type="submit" wire:loading.attr="disabled">
                {{ __('dashboard.Save changes') }}
            </studio:button>
        </div>
    </form>
</div>
