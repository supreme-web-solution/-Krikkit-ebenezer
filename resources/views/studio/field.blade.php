@props(['label' => null])

<div {{ $attributes->class('space-y-1.5') }}>
    @if ($label)
        <studio:field.label>{{ $label }}</studio:field.label>
    @endif

    {{ $slot }}

    @isset($description)
        <studio:field.description>{{ $description }}</studio:field.description>
    @endisset

    @isset($error)
        <studio:field.error>{{ $error }}</studio:field.error>
    @endisset
</div>
