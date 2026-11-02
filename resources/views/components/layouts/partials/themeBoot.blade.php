@php
    use App\Support\Site\SiteSettings;
    use App\Support\Ui\ThemePalette;

    $siteTheme = app(SiteSettings::class)->theme();
    $userTheme = ThemePalette::normalize(auth()->user()?->appearance);
    $savedTheme = ($siteTheme['lock_members'] ?? false) ? null : $userTheme;
    $fallbackTheme = ThemePalette::normalize([
        'accent' => $siteTheme['accent'],
        'base' => $siteTheme['base'],
        'mode' => in_array($siteTheme['mode'], ['light', 'dark'], true) ? $siteTheme['mode'] : 'light',
    ]);
@endphp
<style>
    html { background-color: var(--color-studio-canvas, #fff); color-scheme: light; }
    html.dark { background-color: var(--color-studio-canvas, #0a0a0a); color-scheme: dark; }
</style>
<script type="text/javascript">
    (() => {
        const root = document.documentElement;
        var saved = @js($savedTheme);
        var site = @js($fallbackTheme);
        var siteMode = @js($siteTheme['mode']);
        var locked = @js((bool) $siteTheme['lock_members']);
        var cookieKey = @js(ThemePalette::MODE_COOKIE);

        if (saved && !locked) {
            localStorage.setItem('studio-theme', saved.mode)
            localStorage.setItem('studio-theme-accent', saved.accent)
            localStorage.setItem('studio-theme-base', saved.base)
        } else if (site) {
            if (locked || !localStorage.getItem('studio-theme-accent')) {
                localStorage.setItem('studio-theme-accent', site.accent)
                localStorage.setItem('studio-theme-base', site.base)
            }
            if (locked || !localStorage.getItem('studio-theme')) {
                if (siteMode === 'light' || siteMode === 'dark') {
                    localStorage.setItem('studio-theme', siteMode)
                }
            }
        }

        var modeStored = localStorage.getItem('studio-theme')
        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        var mode = modeStored === 'dark' || modeStored === 'light'
            ? modeStored
            : (prefersDark ? 'dark' : 'light')

        root.classList.toggle('dark', mode === 'dark')
        root.style.colorScheme = mode

        try {
            document.cookie = cookieKey + '=' + mode
                + '; Path=/; Max-Age=31536000; SameSite=Lax'
                + (location.protocol === 'https:' ? '; Secure' : '')
        } catch (e) {}
    })()
</script>
