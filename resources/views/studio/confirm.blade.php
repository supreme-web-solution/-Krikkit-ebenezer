@props([
    'name',
    'title',
    'copy' => null,
    'size' => 'sm',
])

<studio:modal :name="$name" :size="$size" {{ $attributes }}>
    @isset($trigger)
        <x-slot:trigger>{{ $trigger }}</x-slot:trigger>
    @endisset
    <studio:modal.close />
    <h3 class="pr-8 text-lg font-semibold text-studio-fg">{{ $title }}</h3>
    @if (filled($copy))
        <p class="mt-2 text-sm text-studio-muted">{{ $copy }}</p>
    @endif
    {{ $slot }}
    <div class="mt-5 flex justify-end gap-2">
        <studio:button
            type="button"
            variant="ghost"
            @click="$dispatch('studio-modal-close', @js($name))"
        >
            {{ __('dashboard.Cancel') }}
        </studio:button>
        {{ $action }}
    </div>
</studio:modal>
