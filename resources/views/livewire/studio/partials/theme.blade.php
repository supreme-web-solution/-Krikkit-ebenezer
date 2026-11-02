<button
    type="button"
    class="inline-flex size-9 cursor-pointer items-center justify-center rounded-full text-studio-fg-soft transition hover:bg-studio-soft hover:text-studio-fg"
    aria-label="{{ __('studio.Toggle dark mode') }}"
    onclick="(() => {
        const next = document.documentElement.classList.contains('dark') ? 'light' : 'dark'
        if (window.studioTheme?.apply) {
            window.studioTheme.apply({ mode: next, persist: true })
            return
        }
        document.documentElement.classList.toggle('dark', next === 'dark')
        document.documentElement.style.colorScheme = next
        localStorage.setItem('studio-theme', next)
        document.cookie = 'studio-theme=' + next
            + '; Path=/; Max-Age=31536000; SameSite=Lax'
            + (location.protocol === 'https:' ? '; Secure' : '')
    })()"
>
    <span class="dark:hidden">
        <studio:icon name="moon" class="size-4" />
    </span>
    <span class="hidden dark:inline">
        <studio:icon name="sun" class="size-4" />
    </span>
</button>
