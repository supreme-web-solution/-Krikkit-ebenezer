<x-layouts.app :title="__('messages.Pages')">
    <main class="mx-auto min-h-screen max-w-2xl px-6 py-16">
        <p class="text-xs text-studio-muted">
            <a href="{{ url('/') }}" class="hover:text-studio-fg">{{ $site->name() }}</a>
        </p>
        <h1 class="mt-4 text-3xl font-semibold tracking-tight text-studio-fg">{{ __('messages.Pages') }}</h1>

        @if ($pages->isEmpty())
            <p class="mt-8 text-sm text-studio-muted">{{ __('messages.No pages yet.') }}</p>
        @else
            <ul class="mt-10 space-y-4">
                @foreach ($pages as $page)
                    <li>
                        <a href="{{ route('pages.leaf', $page->slug) }}" class="text-sm font-medium text-studio-fg hover:text-accent-content">
                            {{ $page->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </main>
</x-layouts.app>
