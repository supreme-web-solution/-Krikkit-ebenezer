<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Settings') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">
            {{ __('dashboard.Cookie banner and data-controller details.') }}
        </p>
    </div>

    @include('livewire.dashboard.settings.options.nav')

    <form wire:submit="save" class="space-y-10">
        <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Show cookie banner')" wire:model="enabled" />

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Banner') }}</p>

            <studio:field :label="__('dashboard.Title')">
                <studio:input size="md" wire:model="title" :placeholder="__('dashboard.We use cookies')" :invalid="$errors->has('title')" />
                @error('title')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Message')">
                <studio:textarea rows="4" wire:model="message" :placeholder="__('messages.We use essential cookies to run this site. Optional analytics stay off until you accept.')" :invalid="$errors->has('message')" />
                <x-slot:description>{{ __('dashboard.Explain what you store and link to the privacy policy.') }}</x-slot:description>
                @error('message')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.Accept label')">
                    <studio:input size="md" wire:model="acceptLabel" :placeholder="__('dashboard.Accept')" :invalid="$errors->has('acceptLabel')" />
                    @error('acceptLabel')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Reject label')">
                    <studio:input size="md" wire:model="rejectLabel" :placeholder="__('dashboard.Reject')" :invalid="$errors->has('rejectLabel')" />
                    @error('rejectLabel')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            </div>

            <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Show reject button')" wire:model="showReject" />
            <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Wait for consent before analytics')" wire:model="consentAnalytics" />
        </studio:card>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Controller') }}</p>

            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.Controller name')">
                    <studio:input size="md" wire:model="controllerName" :placeholder="__('dashboard.Acme Inc.')" :invalid="$errors->has('controllerName')" />
                    @error('controllerName')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Controller email')">
                    <studio:input size="md" type="email" wire:model="controllerEmail" :placeholder="__('dashboard.privacy@example.com')" :invalid="$errors->has('controllerEmail')" />
                    @error('controllerEmail')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            </div>
        </studio:card>

        <div class="flex justify-end">
            <studio:button type="submit" wire:loading.attr="disabled">
                {{ __('dashboard.Save changes') }}
            </studio:button>
        </div>
    </form>
</div>
