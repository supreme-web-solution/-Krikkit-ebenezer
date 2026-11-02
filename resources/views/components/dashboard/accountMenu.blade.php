@allows('security.self')
    <studio:dropdown.item href="{{ route('dashboard.profile.index') }}" wire:navigate icon="user" x-on:click="open = false">
        {{ __('dashboard.Profile') }}
    </studio:dropdown.item>
    <studio:dropdown.item href="{{ route('dashboard.profile.password') }}" wire:navigate icon="key" x-on:click="open = false">
        {{ __('dashboard.Change password') }}
    </studio:dropdown.item>
    <studio:dropdown.item href="{{ route('dashboard.profile.two-factor') }}" wire:navigate icon="lock-closed" x-on:click="open = false">
        {{ __('dashboard.Two-factor') }}
    </studio:dropdown.item>
    <div class="my-1 border-t border-studio-line/60" role="separator"></div>
@endallows
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <studio:dropdown.item type="submit" danger icon="arrow-right-start-on-rectangle">
        {{ __('dashboard.Log out') }}
    </studio:dropdown.item>
</form>
