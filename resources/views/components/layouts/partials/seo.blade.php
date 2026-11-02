@php
    $site = app(\App\Support\Site\SiteSettings::class);
    $seo = $site->bag('seo');
    $analytics = trim((string) ($seo['analytics_id'] ?? ''));
    $gdpr = $site->bag('gdpr');
    $waitConsent = (bool) ($gdpr['enabled'] ?? false) && (bool) ($gdpr['consent_analytics'] ?? true);
@endphp
{!! app(\App\Support\Seo\PageSeo::class)->markup() !!}
@if ($analytics !== '')
    <script>
        window.studioAnalyticsId = @js($analytics)
        window.studioWaitConsent = @js($waitConsent)
        window.studioLoadAnalytics = function () {
            if (!window.studioAnalyticsId || window.studioAnalyticsReady) return
            window.studioAnalyticsReady = true
            var s = document.createElement('script')
            s.async = true
            s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(window.studioAnalyticsId)
            document.head.appendChild(s)
            window.dataLayer = window.dataLayer || []
            function gtag(){ dataLayer.push(arguments) }
            window.gtag = gtag
            gtag('js', new Date())
            gtag('config', window.studioAnalyticsId)
        }
        if (!window.studioWaitConsent || localStorage.getItem('studio-cookie-consent') === 'all') {
            window.studioLoadAnalytics()
        }
    </script>
@endif
