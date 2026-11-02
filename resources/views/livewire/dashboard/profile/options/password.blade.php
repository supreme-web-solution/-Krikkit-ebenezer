<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Password') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Update password') }}</p>
    </div>

    @include('livewire.dashboard.profile.options.nav')

    <form wire:submit="save" class="space-y-10" autocomplete="off">
        <div class="sr-only" aria-hidden="true">
            <input type="text" name="prevent_autofill_user" autocomplete="username" tabindex="-1" value="">
            <input type="password" name="prevent_autofill_pass" autocomplete="current-password" tabindex="-1" value="">
        </div>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Sign-in') }}</p>

            <studio:field :label="__('dashboard.Current password')">
                <studio:input
                    size="md"
                    type="password"
                    wire:model="current_password"
                    autocomplete="current-password"
                    :placeholder="__('dashboard.••••••••')"
                    :invalid="$errors->has('current_password')"
                />
                @error('current_password')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.New password')">
                    <studio:input
                        size="md"
                        type="password"
                        wire:model="password"
                        autocomplete="new-password"
                        :placeholder="__('dashboard.••••••••')"
                        :invalid="$errors->has('password')"
                    />
                    @error('password')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Confirm password')">
                    <studio:input
                        size="md"
                        type="password"
                        wire:model="password_confirmation"
                        autocomplete="new-password"
                        :placeholder="__('dashboard.••••••••')"
                        :invalid="$errors->has('password_confirmation')"
                    />
                    @error('password_confirmation')
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
