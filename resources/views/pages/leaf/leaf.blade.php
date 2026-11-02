<x-layouts.app :title="$page->title">
    <main class="mx-auto min-h-screen max-w-2xl px-6 py-16">
        <p class="text-xs text-studio-muted">
            <a href="{{ url('/') }}" class="hover:text-studio-fg">{{ $site->name() }}</a>
        </p>
        <h1 class="mt-4 text-3xl font-semibold tracking-tight text-studio-fg">{{ $page->title }}</h1>
        @if ($page->published_on)
            <p class="mt-2 text-sm text-studio-muted">{{ $page->published_on->toFormattedDateString() }}</p>
        @endif
        <article class="prose prose-neutral mt-8 max-w-none text-sm leading-relaxed text-studio-fg-soft dark:prose-invert">
            {!! $html !!}
        </article>
    </main>
</x-layouts.app>
