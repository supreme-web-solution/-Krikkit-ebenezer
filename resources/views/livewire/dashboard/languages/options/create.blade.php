<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.New language') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Add a locale, then open Translate to fill phrases.') }}</p>
    </div>

    @include('livewire.dashboard.languages.options.form')
</div>
