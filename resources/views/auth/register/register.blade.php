<x-layouts.guest :title="__('messages.Register')">
    <x-auth.heading :copy="__('messages.Start from a brief. Lab writes the rest.')">
        {{ __('messages.Create account') }}
    </x-auth.heading>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        <x-form.input :label="__('messages.Name')" name="name" autocomplete="name" :placeholder="__('messages.Jane Doe')" />
        <x-form.input :label="__('messages.Email')" type="email" name="email" autocomplete="username" :value="request('email')" :placeholder="__('messages.you@example.com')" />
        <x-form.input :label="__('messages.Password')" type="password" name="password" autocomplete="new-password" :value="''" :placeholder="__('messages.••••••••')" />
        <x-form.input :label="__('messages.Confirm password')" type="password" name="password_confirmation" autocomplete="new-password" :value="''" :placeholder="__('messages.••••••••')" />

        <studio:button type="submit" class="w-full">{{ __('messages.Register') }}</studio:button>
    </form>

    <p class="mt-8 border-t border-studio-line pt-5 text-sm text-studio-muted">
        {{ __('messages.Already registered?') }}
        <a href="{{ route('login') }}" class="font-medium text-studio-fg transition hover:text-accent-content">{{ __('messages.Log in') }}</a>
    </p>
</x-layouts.guest>
