@props(['for' => null])

<label @if ($for) for="{{ $for }}" @endif {{ $attributes->class('block text-xs font-medium text-studio-fg-soft') }}>
    {{ $slot }}
</label>
