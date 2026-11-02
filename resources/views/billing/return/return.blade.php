<x-layouts.app>
    <div class="flex min-h-screen items-center justify-center bg-studio-canvas px-4">
        @if ($ok)
            <studio:empty
                :icon="false"
                :title="__('dashboard.Thank you')"
                :copy="__('dashboard.Your pack is ready. We are glad you are here.')"
            >
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <studio:button :href="route('settings.subscription')" :navigate="false">
                        {{ __('settings.Manage subscription') }}
                    </studio:button>
                    <studio:button :href="route('home')" :navigate="false" variant="outline">
                        {{ __('dashboard.Back to Studio') }}
                    </studio:button>
                </div>
            </studio:empty>
        @else
            <studio:empty
                :title="__('dashboard.No worries')"
                :copy="__('dashboard.Nothing was charged. Come back and pick a pack whenever you like.')"
            >
                <studio:button :href="route('pricing')" :navigate="false">
                    {{ __('dashboard.See packs') }}
                </studio:button>
            </studio:empty>
        @endif
    </div>
</x-layouts.app>
