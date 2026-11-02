<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.New post') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Write and publish posts for the public blog.') }}</p>
    </div>

    @include('livewire.dashboard.blog.options.form')
</div>
