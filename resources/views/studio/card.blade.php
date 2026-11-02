@props(['padding' => true])

<div {{ $attributes->class([
    'rounded-lg border border-studio-line bg-transparent',
    $padding ? 'p-4 sm:p-5' : '',
]) }}>
    {{ $slot }}
</div>
