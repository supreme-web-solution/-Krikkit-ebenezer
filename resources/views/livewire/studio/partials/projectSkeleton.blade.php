@props([
    'animate' => true,
])

<div class="block min-w-0" aria-hidden="true">
    <studio:skeleton :animate="$animate" class="aspect-video w-full rounded-xl" />
    <studio:skeleton :animate="$animate" class="mt-2.5 h-3.5 w-3/4" />
    <studio:skeleton :animate="$animate" class="mt-1.5 h-2.5 w-1/3" />
</div>
