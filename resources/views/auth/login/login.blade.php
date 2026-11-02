<x-layouts.guest :title="__('messages.Log in')">
    <x-auth.heading :copy="__('messages.Sign in to continue.')">
        {{ __('messages.Welcome back') }}
    </x-auth.heading>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <x-form.input :label="__('messages.Email')" type="email" name="email" autocomplete="username" :placeholder="__('messages.you@example.com')" />
        <x-form.input :label="__('messages.Password')" type="password" name="password" autocomplete="current-password" :value="''" :placeholder="__('messages.••••••••')" />

        <div class="flex items-center justify-between gap-3">
            <studio:checkbox name="remember" value="1" :label="__('messages.Remember me')" class="text-xs text-studio-muted" />
            <a href="{{ route('password.request') }}" class="shrink-0 text-xs font-medium text-studio-muted transition hover:text-studio-fg">{{ __('messages.Forgot your password?') }}</a>
        </div>

        <studio:button type="submit" class="w-full">{{ __('messages.Log in') }}</studio:button>
    </form>

    @if (app(\App\Support\Site\SiteSettings::class)->allowRegistration())
        <p class="mt-8 border-t border-studio-line pt-5 text-sm text-studio-muted">
            {{ __('messages.No account?') }}
            <a href="{{ route('register') }}" class="font-medium text-studio-fg transition hover:text-accent-content">{{ __('messages.Register') }}</a>
        </p>
    @endif
</x-layouts.guest>
