@props([
    'orientation' => 'horizontal',
])

@php
    $classes = $orientation === 'vertical'
        ? 'w-px self-stretch bg-studio-line'
        : 'h-px w-full bg-studio-line';
@endphp

<div role="separator" {{ $attributes->class($classes) }}></div>
