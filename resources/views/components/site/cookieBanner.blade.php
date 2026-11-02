@php
    $site = app(\App\Support\Site\SiteSettings::class);
    $gdpr = $site->bag('gdpr');
@endphp

@if ($gdpr['enabled'] ?? false)
    @php
        $title = trim((string) ($gdpr['title'] ?? '')) ?: __('messages.We use cookies');
        $message = trim((string) ($gdpr['message'] ?? '')) ?: __('messages.We use essential cookies to run this site. Optional analytics stay off until you accept.');
        $accept = trim((string) ($gdpr['accept_label'] ?? '')) ?: __('messages.Accept');
        $reject = trim((string) ($gdpr['reject_label'] ?? '')) ?: __('messages.Reject');
    @endphp
    <div
        x-data="{
            open: localStorage.getItem('studio-cookie-consent') !== 'all' && localStorage.getItem('studio-cookie-consent') !== 'essential',
            accept() {
                localStorage.setItem('studio-cookie-consent', 'all')
                this.open = false
                window.studioLoadAnalytics?.()
            },
            reject() {
                localStorage.setItem('studio-cookie-consent', 'essential')
                this.open = false
            },
        }"
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-4 left-4 right-4 z-[80] sm:right-auto sm:w-[22.5rem]"
        role="dialog"
        aria-labelledby="cookie-banner-title"
        aria-describedby="cookie-banner-copy"
    >
        <div class="rounded-xl border border-studio-line bg-studio-surface p-4">
            <div class="flex items-start gap-3">
                <span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-full bg-studio-soft text-accent-content">
                    <studio:icon name="information-circle" class="size-4" />
                </span>
                <div class="min-w-0 flex-1">
                    <p id="cookie-banner-title" class="text-[13px] font-medium text-studio-fg">{{ $title }}</p>
                    <p id="cookie-banner-copy" class="mt-1 text-xs leading-relaxed text-studio-muted">
                        {{ $message }}
                        @if ($site->privacyPublished())
                            <a href="{{ route('privacy') }}" class="text-studio-fg-soft underline decoration-studio-line underline-offset-2 transition hover:text-studio-fg">{{ __('messages.Privacy policy') }}</a>
                        @endif
                    </p>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                @if ($gdpr['show_reject'] ?? true)
                    <studio:button type="button" variant="outline" size="sm" class="flex-1" x-on:click="reject()">
                        {{ $reject }}
                    </studio:button>
                @endif
                <studio:button type="button" size="sm" class="flex-1" x-on:click="accept()">
                    {{ $accept }}
                </studio:button>
            </div>
        </div>
    </div>
@endif
