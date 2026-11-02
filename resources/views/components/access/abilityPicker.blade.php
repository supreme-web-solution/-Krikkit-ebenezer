@props(['clusters', 'selected' => []])

<div class="space-y-4">
    @foreach ($clusters as $cluster => $abilities)
        <studio:card :padding="false" class="!border-0">
            <header class="border-b border-studio-line/50 px-5 py-2.5">
                <h3 class="text-xs font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ $cluster }}</h3>
            </header>
            <ul class="divide-y divide-studio-line/50">
                @foreach ($abilities as $ability)
                    <li class="flex items-start gap-3 px-5 py-3">
                        <studio:checkbox
                            :id="'ability-'.$ability->code"
                            name="abilities[]"
                            :value="$ability->code"
                            :checked="in_array($ability->code, $selected, true)"
                        />
                        <label for="ability-{{ $ability->code }}" class="min-w-0 flex-1 cursor-pointer">
                            <span class="block text-sm font-medium text-studio-fg">{{ $ability->title }}</span>
                            <span class="mt-0.5 block font-mono text-xs text-studio-muted">{{ $ability->code }}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </studio:card>
    @endforeach
</div>
