<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.SEO') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">
            {{ __('dashboard.Search, social cards, and measurement.') }}
        </p>
    </div>

    <form wire:submit="save" class="space-y-10">
        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Search') }}</p>

            <studio:field :label="__('dashboard.Meta title')">
                <studio:input size="md" wire:model="metaTitle" :placeholder="__('dashboard.Supreme — Lab')" :invalid="$errors->has('metaTitle')" />
                <x-slot:description>{{ __('dashboard.Falls back to the site name when empty.') }}</x-slot:description>
                @error('metaTitle')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Meta description')">
                <studio:textarea rows="3" wire:model="metaDescription" :placeholder="__('dashboard.A short description for search results.')" :invalid="$errors->has('metaDescription')" />
                @error('metaDescription')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Keywords')">
                <studio:input size="md" wire:model="metaKeywords" :placeholder="__('dashboard.lab, ai, workspace')" :invalid="$errors->has('metaKeywords')" />
                @error('metaKeywords')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Home title')">
                <studio:input size="md" wire:model="titleHome" :placeholder="$titleHomeDefault" :invalid="$errors->has('titleHome')" />
                <x-slot:description>{{ __('dashboard.Default: App name | page. Tokens: {name} {page}.') }}</x-slot:description>
                @error('titleHome')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Dashboard title')">
                <studio:input size="md" wire:model="titleDashboard" :placeholder="$titleDashboardDefault" :invalid="$errors->has('titleDashboard')" />
                <x-slot:description>{{ __('dashboard.Default: Dashboard | page.') }}</x-slot:description>
                @error('titleDashboard')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Lab title')">
                <studio:input size="md" wire:model="titleLab" :placeholder="$titleLabDefault" :invalid="$errors->has('titleLab')" />
                <x-slot:description>{{ __('dashboard.Default: App name Lab | page.') }}</x-slot:description>
                @error('titleLab')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Canonical URL')">
                <studio:input size="md" type="url" wire:model="canonical" placeholder="https://" :invalid="$errors->has('canonical')" />
                @error('canonical')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <div class="space-y-3">
                <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Allow search indexing')" wire:model="robotsIndex" />
                <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Follow outbound links')" wire:model="robotsFollow" />
            </div>
        </studio:card>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Social cards') }}</p>

            <studio:field :label="__('dashboard.Open Graph title')">
                <studio:input size="md" wire:model="ogTitle" :placeholder="__('dashboard.Supreme — Lab')" :invalid="$errors->has('ogTitle')" />
                @error('ogTitle')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Open Graph description')">
                <studio:textarea rows="3" wire:model="ogDescription" :placeholder="__('dashboard.A short description for social cards.')" :invalid="$errors->has('ogDescription')" />
                @error('ogDescription')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            @include('livewire.dashboard.settings.options.asset', [
                'label' => __('dashboard.Open Graph image'),
                'hint' => __('dashboard.Recommended 1200×630.'),
                'property' => 'ogImage',
                'preview' => $ogPreview,
                'accept' => 'image/png,image/jpeg,image/webp',
            ])

            <studio:field :label="__('dashboard.Twitter handle')">
                <studio:input size="md" wire:model="twitterHandle" placeholder="@handle" :invalid="$errors->has('twitterHandle')" />
                @error('twitterHandle')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </studio:card>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Measurement') }}</p>

            <studio:field :label="__('dashboard.Google Analytics')">
                <studio:input size="md" wire:model="analyticsId" placeholder="G-XXXXXXXXXX" :invalid="$errors->has('analyticsId')" />
                <x-slot:description>{{ __('dashboard.Measurement ID. Loaded after cookie consent when GDPR is on.') }}</x-slot:description>
                @error('analyticsId')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Search Console')">
                <studio:input size="md" wire:model="searchConsole" :placeholder="__('dashboard.google-site-verification token')" :invalid="$errors->has('searchConsole')" />
                <x-slot:description>{{ __('dashboard.google-site-verification content value.') }}</x-slot:description>
                @error('searchConsole')
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
