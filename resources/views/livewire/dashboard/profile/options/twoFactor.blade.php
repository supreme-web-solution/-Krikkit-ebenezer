<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Two-factor') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">
            @if ($enabled)
                {{ __('dashboard.Two-factor authentication is enabled for your account.') }}
            @else
                {{ __('dashboard.Add an authenticator app for an extra layer of security.') }}
            @endif
        </p>
    </div>

    @include('livewire.dashboard.profile.options.nav')

    @unless ($enabled)
        @if ($pendingSecret)
            <studio:card :padding="false" class="space-y-4 !border-0">
                <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Confirm and enable') }}</p>
                <p class="text-xs text-studio-muted">{{ __('dashboard.Add this account in your authenticator app using the secret or otpauth URI.') }}</p>

                <div>
                    <p class="mb-1.5 text-[11px] font-medium text-studio-muted">{{ __('dashboard.Secret') }}</p>
                    <code class="block break-all rounded-xl bg-studio-surface px-4 py-3 text-sm text-studio-fg">{{ $pendingSecret }}</code>
                </div>

                @if ($qrCodeSvg)
                    <div class="inline-block max-w-full overflow-x-auto rounded-xl bg-studio-surface p-3">
                        {!! $qrCodeSvg !!}
                    </div>
                @endif

                <form wire:submit="confirmSetup" class="space-y-4">
                    <studio:field :label="__('dashboard.Confirmation code')">
                        <studio:input
                            size="md"
                            wire:model="code"
                            autocomplete="one-time-code"
                            :placeholder="__('dashboard.123456')"
                            :invalid="$errors->has('code')"
                        />
                        @error('code')
                            <studio:field.error>{{ $message }}</studio:field.error>
                        @enderror
                    </studio:field>
                    <div class="flex justify-end">
                        <studio:button type="submit" wire:loading.attr="disabled">
                            {{ __('dashboard.Confirm and enable') }}
                        </studio:button>
                    </div>
                </form>
            </studio:card>
        @else
            <form wire:submit="enable" class="space-y-10" autocomplete="off">
                <div class="sr-only" aria-hidden="true">
                    <input type="text" name="prevent_autofill_user" autocomplete="username" tabindex="-1" value="">
                    <input type="password" name="prevent_autofill_pass" autocomplete="current-password" tabindex="-1" value="">
                </div>

                <studio:card :padding="false" class="space-y-4 !border-0">
                    <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Authenticator app') }}</p>
                    <studio:field :label="__('dashboard.Current password')">
                        <studio:input
                            size="md"
                            type="password"
                            wire:model="password"
                            autocomplete="current-password"
                            :placeholder="__('dashboard.••••••••')"
                            :invalid="$errors->has('password')"
                        />
                        @error('password')
                            <studio:field.error>{{ $message }}</studio:field.error>
                        @enderror
                    </studio:field>
                </studio:card>

                <div class="flex justify-end">
                    <studio:button type="submit" wire:loading.attr="disabled">
                        {{ __('dashboard.Enable two-factor authentication') }}
                    </studio:button>
                </div>
            </form>
        @endif
    @endunless

    @if ($recoveryCodes !== [])
        <studio:callout tone="warning">
            <studio:callout.heading>{{ __('dashboard.Recovery codes') }}</studio:callout.heading>
            <studio:callout.text>{{ __('dashboard.Store these codes somewhere safe. Each code can be used once.') }}</studio:callout.text>
            <ul class="mt-3 grid gap-1 font-mono text-sm">
                @foreach ($recoveryCodes as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </studio:callout>
    @endif

    @if ($enabled)
        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Regenerate recovery codes') }}</p>
            <form wire:submit="regenerate" class="space-y-4" autocomplete="off">
                <studio:field :label="__('dashboard.Current password')">
                    <studio:input
                        size="md"
                        type="password"
                        wire:model="regeneratePassword"
                        autocomplete="current-password"
                        :placeholder="__('dashboard.••••••••')"
                        :invalid="$errors->has('regeneratePassword')"
                    />
                    @error('regeneratePassword')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <div class="flex justify-end">
                    <studio:button type="submit" variant="outline" wire:loading.attr="disabled">
                        {{ __('dashboard.Regenerate') }}
                    </studio:button>
                </div>
            </form>
        </studio:card>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Disable two-factor authentication') }}</p>
            <studio:field :label="__('dashboard.Current password')">
                <studio:input
                    size="md"
                    type="password"
                    wire:model="disablePassword"
                    autocomplete="current-password"
                    :placeholder="__('dashboard.••••••••')"
                    :invalid="$errors->has('disablePassword')"
                />
                @error('disablePassword')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
            <div class="flex justify-end">
                <studio:button type="button" variant="danger" wire:click="askDisable">
                    {{ __('dashboard.Disable') }}
                </studio:button>
            </div>
        </studio:card>

        <studio:confirm name="disable-two-factor" :title="__('dashboard.Disable two-factor authentication')" :copy="__('dashboard.Turn off two-factor for this account?')">
            <x-slot:action>
                <studio:button type="button" variant="danger" wire:click="confirmPending">
                    {{ __('dashboard.Disable') }}
                </studio:button>
            </x-slot:action>
        </studio:confirm>
    @endif
</div>
