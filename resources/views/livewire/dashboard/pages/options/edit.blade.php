<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Edit page') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Standalone public pages with their own URL.') }}</p>
    </div>

    @include('livewire.dashboard.pages.options.form', ['publicUrl' => $publicUrl])
</div>
