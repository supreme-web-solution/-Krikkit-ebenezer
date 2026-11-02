@props([
    'label',
    'type' => 'text',
    'name',
    'autocomplete' => null,
    'value' => '',
    'required' => true,
    'size' => 'lg',
])

@php
    $attributes = $attributes->merge(array_filter([
        'type' => $type,
        'name' => $name,
        'id' => $name,
        'value' => old($name, $value),
        'autocomplete' => $autocomplete,
        'required' => $required ? true : null,
    ], fn ($value) => $value !== null && $value !== false));
@endphp

<studio:field :label="$label">
    <studio:input :size="$size" :invalid="$errors->has($name)" {{ $attributes }} />

    @error($name)
        <studio:field.error>{{ $message }}</studio:field.error>
    @enderror
</studio:field>
