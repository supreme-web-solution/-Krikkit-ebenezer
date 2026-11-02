@props([
    'name',
])

<button
    type="button"
    role="tab"
    @click="active = @js($name)"
    :aria-selected="active === @js($name)"
    {{ $attributes->class('rounded-lg px-3 py-1.5 text-sm font-medium transition') }}
    :class="active === @js($name)
        ? 'bg-studio-surface text-studio-fg'
        : 'text-studio-muted hover:text-studio-fg'"
>
    {{ $slot }}
</button>
