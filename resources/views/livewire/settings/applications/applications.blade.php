<x-settings.frame section="applications">
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-studio-fg">{{ __('settings.Applications') }}</h1>
            <p class="mt-1 text-sm text-studio-muted">{{ __('settings.Connect other tools to extend what you can build with Supreme.') }}</p>
        </div>

        <div class="space-y-3">
            <studio:card class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div class="flex min-w-0 flex-1 items-start gap-4">
                    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg border border-studio-line bg-studio-soft text-studio-fg">
                        <studio:icon name="circle-stack" class="size-5" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('settings.Database') }}</p>
                        <p class="mt-1 text-sm font-semibold text-studio-fg">Supabase</p>
                        <p class="mt-1 text-sm text-studio-muted">{{ __('settings.Connect a database for authentication and data storage.') }}</p>
                    </div>
                </div>
                <div class="shrink-0 sm:self-center">
                    @if ($supabaseLinked)
                        <studio:button type="button" variant="outline" x-on:click="$dispatch('studio-modal-open', 'unlink-supabase')">{{ __('settings.Disconnect') }}</studio:button>
                    @else
                        <studio:button :href="$supabaseConnect" :navigate="false" variant="outline">{{ __('settings.Connect') }}</studio:button>
                    @endif
                </div>
            </studio:card>

            <studio:card class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div class="flex min-w-0 flex-1 items-start gap-4">
                    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg border border-studio-line bg-studio-soft text-studio-fg">
                        <studio:icon name="github" class="size-5" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-studio-subtle">{{ __('settings.Code') }}</p>
                        <p class="mt-1 text-sm font-semibold text-studio-fg">GitHub @if ($githubLogin)<span class="font-normal text-studio-muted">· {{ $githubLogin }}</span>@endif</p>
                        <p class="mt-1 text-sm text-studio-muted">{{ __('settings.Import repositories and push Lab projects from your GitHub account.') }}</p>
                        @if ($githubLinked)
                            <p class="mt-2 text-xs text-studio-subtle">{{ __('settings.To revoke GitHub authorization, visit github.com/settings/applications, find Supreme, and click Revoke.') }}</p>
                        @endif
                    </div>
                </div>
                <div class="shrink-0 sm:self-center">
                    @if ($githubLinked)
                        <studio:button type="button" variant="outline" x-on:click="$dispatch('studio-modal-open', 'unlink-github')">{{ __('settings.Disconnect') }}</studio:button>
                    @else
                        <studio:button :href="$githubConnect" :navigate="false" variant="outline">{{ __('settings.Connect') }}</studio:button>
                    @endif
                </div>
            </studio:card>
        </div>
    </div>

    <studio:confirm name="unlink-github" :title="__('settings.Disconnect GitHub')" :copy="__('settings.Disconnect this GitHub account from Supreme?')">
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="unlinkGithub">{{ __('settings.Disconnect') }}</studio:button>
        </x-slot:action>
    </studio:confirm>
    <studio:confirm name="unlink-supabase" :title="__('settings.Disconnect Supabase')" :copy="__('settings.Disconnect this Supabase account from Supreme?')">
        <x-slot:action>
            <studio:button type="button" variant="danger" wire:click="unlinkSupabase">{{ __('settings.Disconnect') }}</studio:button>
        </x-slot:action>
    </studio:confirm>
</x-settings.frame>
