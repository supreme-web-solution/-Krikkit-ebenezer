<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Settings') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">
            {{ __('dashboard.Workspace name, brand marks, and public access.') }}
        </p>
    </div>

    @include('livewire.dashboard.settings.options.nav')

    <form wire:submit="save" class="space-y-10">
        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Identity') }}</p>

            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.Site name')">
                    <studio:input size="md" wire:model="name" :placeholder="__('dashboard.Supreme')" :invalid="$errors->has('name')" />
                    @error('name')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Site URL')">
                    <studio:input size="md" type="url" wire:model="url" placeholder="https://" :invalid="$errors->has('url')" />
                    @error('url')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            </div>

            <studio:field :label="__('dashboard.Tagline')">
                <studio:input size="md" wire:model="tagline" :placeholder="__('dashboard.Build in the Lab.')" :invalid="$errors->has('tagline')" />
                <x-slot:description>{{ __('dashboard.Short line under the name on the public site.') }}</x-slot:description>
                @error('tagline')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.Legal name')">
                    <studio:input size="md" wire:model="legalName" :placeholder="__('dashboard.Acme Inc.')" :invalid="$errors->has('legalName')" />
                    @error('legalName')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Copyright')">
                    <studio:input size="md" wire:model="copyright" placeholder="© {{ date('Y') }}" :invalid="$errors->has('copyright')" />
                    @error('copyright')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            </div>
        </studio:card>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Brand') }}</p>
            <p class="text-xs text-studio-muted">{{ __('dashboard.Light marks sit on pale surfaces. Dark marks sit on dark surfaces.') }}</p>

            <div class="grid gap-6 sm:grid-cols-2">
                @include('livewire.dashboard.settings.options.asset', [
                    'label' => __('dashboard.Logo · light'),
                    'hint' => __('dashboard.Used in light mode.'),
                    'property' => 'logoLight',
                    'preview' => $previews['logoLight'],
                ])
                @include('livewire.dashboard.settings.options.asset', [
                    'label' => __('dashboard.Logo · dark'),
                    'hint' => __('dashboard.Used in dark mode.'),
                    'property' => 'logoDark',
                    'preview' => $previews['logoDark'],
                ])
                @include('livewire.dashboard.settings.options.asset', [
                    'label' => __('dashboard.Favicon'),
                    'hint' => __('dashboard.Browser tab icon. PNG, SVG, or ICO.'),
                    'property' => 'favicon',
                    'preview' => $previews['favicon'],
                    'accept' => 'image/png,image/svg+xml,image/x-icon,.ico',
                ])
                @include('livewire.dashboard.settings.options.asset', [
                    'label' => __('dashboard.Apple touch icon'),
                    'hint' => __('dashboard.Home-screen icon on iOS.'),
                    'property' => 'appleTouch',
                    'preview' => $previews['appleTouch'],
                    'accept' => 'image/png,image/jpeg,image/webp',
                ])
                @include('livewire.dashboard.settings.options.asset', [
                    'label' => __('dashboard.SVG icon · light'),
                    'hint' => __('dashboard.Compact mark for the sidebar and auth.'),
                    'property' => 'iconLight',
                    'preview' => $previews['iconLight'],
                ])
                @include('livewire.dashboard.settings.options.asset', [
                    'label' => __('dashboard.SVG icon · dark'),
                    'hint' => __('dashboard.Compact mark for dark mode.'),
                    'property' => 'iconDark',
                    'preview' => $previews['iconDark'],
                ])
            </div>
        </studio:card>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Contact') }}</p>

            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.Support email')">
                    <studio:input size="md" type="email" wire:model="supportEmail" :placeholder="__('dashboard.you@example.com')" :invalid="$errors->has('supportEmail')" />
                    @error('supportEmail')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Contact email')">
                    <studio:input size="md" type="email" wire:model="contactEmail" :placeholder="__('dashboard.hello@example.com')" :invalid="$errors->has('contactEmail')" />
                    @error('contactEmail')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.X / Twitter')">
                    <studio:input size="md" type="url" wire:model="socialX" placeholder="https://x.com/…" :invalid="$errors->has('socialX')" />
                    @error('socialX')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.GitHub')">
                    <studio:input size="md" type="url" wire:model="socialGithub" placeholder="https://github.com/…" :invalid="$errors->has('socialGithub')" />
                    @error('socialGithub')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Discord')">
                    <studio:input size="md" type="url" wire:model="socialDiscord" placeholder="https://discord.gg/…" :invalid="$errors->has('socialDiscord')" />
                    @error('socialDiscord')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.LinkedIn')">
                    <studio:input size="md" type="url" wire:model="socialLinkedin" placeholder="https://linkedin.com/…" :invalid="$errors->has('socialLinkedin')" />
                    @error('socialLinkedin')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            </div>
        </studio:card>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Workspace') }}</p>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <studio:field :label="__('dashboard.Locale')">
                    <studio:select size="md" wire:model="locale" :value="$locale" :invalid="$errors->has('locale')">
                        @foreach ($locales as $code => $label)
                            <studio:select.option :value="$code" :selected="$locale === $code">{{ $label }}</studio:select.option>
                        @endforeach
                    </studio:select>
                    @error('locale')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Timezone')">
                    <studio:select size="md" searchable wire:model="timezone" :value="$timezone" :invalid="$errors->has('timezone')">
                        @foreach ($timezones as $zone)
                            <studio:select.option :value="$zone" :selected="$timezone === $zone">{{ $zone }}</studio:select.option>
                        @endforeach
                    </studio:select>
                    @error('timezone')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.Currency')">
                    <studio:select size="md" searchable wire:model="currency" :value="$currency" :invalid="$errors->has('currency')">
                        @foreach ($currencies as $code => $label)
                            <studio:select.option :value="$code" :selected="$currency === $code">{{ $label }}</studio:select.option>
                        @endforeach
                    </studio:select>
                    <x-slot:description>{{ __('dashboard.Used for pack prices and billing display.') }}</x-slot:description>
                    @error('currency')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            </div>
        </studio:card>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Access') }}</p>

            <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Allow public registration')" wire:model="allowRegistration" />
            <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Maintenance mode')" wire:model="maintenance" />

            <studio:field :label="__('dashboard.Maintenance message')">
                <studio:textarea rows="3" wire:model="maintenanceMessage" :placeholder="__('dashboard.We will be back shortly.')" :invalid="$errors->has('maintenanceMessage')" />
                <x-slot:description>{{ __('dashboard.Shown to guests while the public site is closed. Owners can still sign in.') }}</x-slot:description>
                @error('maintenanceMessage')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </studio:card>

        <div class="flex justify-end">
            <studio:button type="submit" wire:loading.attr="disabled">
                {{ __('dashboard.Save changes') }}
            </studio:button>
        </div>
    </form>
</div>
