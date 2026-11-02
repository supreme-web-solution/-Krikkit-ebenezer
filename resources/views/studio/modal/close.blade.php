<button
    type="button"
    {{ $attributes->class('absolute right-3 top-3 rounded-lg p-1.5 text-studio-muted hover:bg-studio-soft hover:text-studio-fg') }}
    @click="$dispatch('studio-modal-close')"
    aria-label="{{ __('messages.Close') }}"
>
    <studio:icon name="x" class="size-4" />
</button>
