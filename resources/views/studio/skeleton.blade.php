@props([
    'animate' => true,
])

<div
    {{ $attributes->class([
        'rounded-md bg-studio-soft',
        $animate ? 'animate-pulse' : '',
    ]) }}
    aria-hidden="true"
>
    {{ $slot }}
</div>
