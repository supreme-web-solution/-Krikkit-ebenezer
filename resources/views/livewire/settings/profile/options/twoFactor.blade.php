<x-settings.frame section="profile">
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-studio-fg">{{ __('settings.Two-factor') }}</h1>
            <p class="mt-1 text-sm text-studio-muted">
                @if ($enabled)
                    {{ __('dashboard.Two-factor authentication is enabled for your account.') }}
                @else
                    {{ __('dashboard.Add an authenticator app for an extra layer of security.') }}
                @endif
            </p>
        </div>

        @include('livewire.settings.profile.options.nav')

        @unless ($enabled)
            @if ($pendingSecret)
                <div class="space-y-4">
                    <p class="text-sm text-studio-muted">{{ __('dashboard.Add this account in your authenticator app using the secret or otpauth URI.') }}</p>
                    <code class="block break-all rounded-lg bg-studio-soft px-4 py-3 text-sm text-studio-fg">{{ $pendingSecret }}</code>
                    @if ($qrCodeSvg)
                        <div class="inline-block max-w-full overflow-x-auto rounded-lg bg-studio-soft p-3">
                            {!! $qrCodeSvg !!}
                        </div>
                    @endif
                    <form wire:submit="confirmSetup" class="space-y-4">
                        <studio:field :label="__('dashboard.Confirmation code')">
                            <studio:input size="md" wire:model="code" autocomplete="one-time-code" :invalid="$errors->has('code')" />
                            @error('code')
                                <studio:field.error>{{ $message }}</studio:field.error>
                            @enderror
                        </studio:field>
                        <div class="flex justify-end">
                            <studio:button type="submit">{{ __('dashboard.Confirm and enable') }}</studio:button>
                        </div>
                    </form>
                </div>
            @else
                <form wire:submit="enable" class="space-y-8" autocomplete="off">
                    <studio:field :label="__('dashboard.Current password')">
                        <studio:input size="md" type="password" wire:model="password" autocomplete="current-password" :invalid="$errors->has('password')" />
                        @error('password')
                            <studio:field.error>{{ $message }}</studio:field.error>
                        @enderror
                    </studio:field>
                    <div class="flex justify-end">
                        <studio:button type="submit">{{ __('dashboard.Enable two-factor authentication') }}</studio:button>
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
            <form wire:submit="regenerate" class="space-y-4" autocomplete="off">
                <studio:field :label="__('dashboard.Current password')">
                    <studio:input size="md" type="password" wire:model="regeneratePassword" autocomplete="current-password" :invalid="$errors->has('regeneratePassword')" />
                    @error('regeneratePassword')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <div class="flex justify-end">
                    <studio:button type="submit" variant="outline">{{ __('dashboard.Regenerate') }}</studio:button>
                </div>
            </form>

            <div class="space-y-4">
                <studio:field :label="__('dashboard.Current password')">
                    <studio:input size="md" type="password" wire:model="disablePassword" autocomplete="current-password" :invalid="$errors->has('disablePassword')" />
                    @error('disablePassword')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <div class="flex justify-end">
                    <studio:button type="button" variant="danger" wire:click="askDisable">{{ __('dashboard.Disable') }}</studio:button>
                </div>
            </div>

            <studio:confirm name="disable-two-factor" :title="__('dashboard.Disable two-factor authentication')" :copy="__('dashboard.Turn off two-factor for this account?')">
                <x-slot:action>
                    <studio:button type="button" variant="danger" wire:click="confirmPending">{{ __('dashboard.Disable') }}</studio:button>
                </x-slot:action>
            </studio:confirm>
        @endif
    </div>
</x-settings.frame>
