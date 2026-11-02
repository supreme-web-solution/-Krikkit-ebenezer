<x-layouts.app :title="__('messages.Unavailable')">
    <main class="mx-auto flex min-h-screen max-w-lg flex-col items-center justify-center px-6 text-center">
        <x-site.mark class="mb-8" />
        <h1 class="text-2xl font-semibold tracking-tight text-studio-fg">{{ $site->name() }}</h1>
        <p class="mt-3 text-sm leading-relaxed text-studio-muted">{{ $site->maintenanceMessage() }}</p>
        <studio:button :href="route('login')" :navigate="false" variant="outline" class="mt-8">
            {{ __('messages.Log in') }}
        </studio:button>
    </main>
</x-layouts.app>
