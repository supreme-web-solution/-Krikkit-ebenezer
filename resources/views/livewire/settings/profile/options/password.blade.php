<x-settings.frame section="profile">
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-studio-fg">{{ __('settings.Password') }}</h1>
            <p class="mt-1 text-sm text-studio-muted">{{ __('dashboard.Update password') }}</p>
        </div>

        @include('livewire.settings.profile.options.nav')

        <form wire:submit="save" class="space-y-8" autocomplete="off">
            <div class="sr-only" aria-hidden="true">
                <input type="text" name="prevent_autofill_user" autocomplete="username" tabindex="-1" value="">
                <input type="password" name="prevent_autofill_pass" autocomplete="current-password" tabindex="-1" value="">
            </div>

            <studio:field :label="__('dashboard.Current password')">
                <studio:input size="md" type="password" wire:model="current_password" autocomplete="current-password" :invalid="$errors->has('current_password')" />
                @error('current_password')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.New password')">
                    <studio:input size="md" type="password" wire:model="password" autocomplete="new-password" :invalid="$errors->has('password')" />
                    @error('password')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Confirm password')">
                    <studio:input size="md" type="password" wire:model="password_confirmation" autocomplete="new-password" :invalid="$errors->has('password_confirmation')" />
                    @error('password_confirmation')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            </div>

            <div class="flex justify-end">
                <studio:button type="submit">{{ __('dashboard.Save changes') }}</studio:button>
            </div>
        </form>
    </div>
</x-settings.frame>
