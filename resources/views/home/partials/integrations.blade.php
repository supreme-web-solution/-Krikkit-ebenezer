@php
    $brands = [
        ['id' => 'claude', 'label' => 'Claude'],
        ['id' => 'openai', 'label' => 'OpenAI'],
        ['id' => 'github', 'label' => 'GitHub'],
        ['id' => 'deepseek', 'label' => 'DeepSeek'],
        ['id' => 'xai', 'label' => 'xAI'],
        ['id' => 'mistral', 'label' => 'Mistral'],
        ['id' => 'zhipu', 'label' => 'Zhipu'],
        ['id' => 'gemini', 'label' => 'Google AI Studio'],
        ['id' => 'groq', 'label' => 'Groq'],
    ];
@endphp

@php
    $integrations = $landing->section('integrations');
@endphp

<section id="integrations" class="border-b border-studio-line">
    <div class="relative mx-auto max-w-7xl overflow-hidden border-x border-studio-line">
        <div class="relative flex flex-col items-center px-6 pt-20 text-center sm:px-10 md:pt-28">
            <span class="inline-flex items-center gap-2 border border-studio-line bg-studio-soft px-3 py-1.5">
                <span class="size-1.5 bg-accent"></span>
                <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-studio-fg-soft">{{ $integrations['eyebrow'] }}</span>
            </span>
            <h2 class="mt-6 max-w-2xl text-3xl font-medium leading-[1.15] tracking-tight text-studio-fg sm:text-5xl">
                {{ $integrations['title'] }}
            </h2>
        </div>
        <div class="relative mt-12 grid grid-cols-3 gap-px border-t border-studio-line bg-studio-line">
            @foreach ($brands as $brand)
                <div class="flex h-40 flex-col items-center justify-center gap-3 bg-studio-canvas sm:h-52">
                    @include('home.partials.brand', ['name' => $brand['id'], 'iconClass' => 'size-12 sm:size-16'])
                    <span class="px-2 text-center text-sm font-medium tracking-wide text-studio-fg-soft">{{ $brand['label'] }}</span>
                </div>
            @endforeach
        </div>
        <div class="relative border-t border-studio-line px-6 py-8 text-center">
            <a href="{{ $landing->href($integrations['cta_href'] ?? '', '#questions') }}" class="text-sm font-medium text-studio-fg hover:text-accent-content">{{ $integrations['cta_label'] }}</a>
        </div>
    </div>
</section>
