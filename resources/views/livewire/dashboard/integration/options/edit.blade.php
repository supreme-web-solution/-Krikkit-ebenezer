@php
    $isOauthApp = $isGithub || $isDatastore;
    $keySource = $isGithub
        ? ($githubApp['source'] ?? 'missing')
        : ($isDatastore ? ($supabaseApp['source'] ?? 'missing') : ($isVisualCatalog ? ($visualKeySource ?? 'missing') : $settings->keySource($provider)));
    $maskedKey = $isOauthApp ? null : ($isVisualCatalog ? ($visualMaskedKey ?? null) : $settings->maskedKey($provider));
    $pricing = is_array($project) ? ($project['pricing'] ?? null) : null;
    $activeScenario = is_array($project) ? ($project['scenario'] ?? null) : null;
    $unitCostJs = is_array($project) && $project['unit_cost_usd'] !== null
        ? (float) $project['unit_cost_usd']
        : null;
@endphp

<div class="space-y-8">
    <div>
        <studio:breadcrumbs class="mb-3">
            <studio:breadcrumbs.item href="{{ route('dashboard.integration.index') }}">{{ __('dashboard.API Integration') }}</studio:breadcrumbs.item>
            <studio:breadcrumbs.item current>{{ $providerLabel }}</studio:breadcrumbs.item>
        </studio:breadcrumbs>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ $providerLabel }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">
            @if ($isGithub)
                {{ __('dashboard.OAuth credentials for Lab imports.') }}
            @elseif ($isVisualCatalog)
                {{ __('dashboard.Server-side key so Lab can fetch matching photographs.') }}
            @elseif ($isDatastore)
                {{ __('dashboard.OAuth credentials so Lab users can connect Supabase from the + menu.') }}
            @else
                {{ __('dashboard.API key, models, and project-volume cost for this provider.') }}
            @endif
        </p>
    </div>

    <form wire:submit="save" class="space-y-5" autocomplete="off">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-studio-fg">{{ __('dashboard.Credentials') }}</p>
                    <p class="mt-0.5 text-xs text-studio-muted">
                        @if ($isOauthApp)
                            @if ($keySource === 'workspace')
                                {{ __('dashboard.Configured') }}
                            @elseif ($keySource === 'env')
                                {{ __('dashboard.Using .env') }}
                            @else
                                {{ __('dashboard.Not configured') }}
                            @endif
                        @elseif ($keySource === 'workspace')
                            {{ __('dashboard.Workspace key :masked', ['masked' => $maskedKey]) }}
                        @elseif ($keySource === 'env')
                            {{ __('dashboard.Using .env fallback :masked', ['masked' => $maskedKey]) }}
                        @else
                            {{ __('dashboard.No key configured') }}
                        @endif
                    </p>
                </div>
                <studio:switch :label="__('dashboard.Enabled')" wire:model="enabled" />
            </div>

            @if ($unreadableStoredKey)
                <studio:callout tone="warning">
                    <studio:callout.heading>{{ __('dashboard.Saved key cannot be read') }}</studio:callout.heading>
                    <studio:callout.text>{{ __('dashboard.The saved secret can no longer be read. Paste it again to restore this provider.') }}</studio:callout.text>
                </studio:callout>
            @endif

            @if ($isGithub)
                {{-- Decoy fields: stop browsers from injecting saved login credentials. --}}
                <div class="sr-only" aria-hidden="true">
                    <input type="text" name="prevent_autofill_user" autocomplete="username" tabindex="-1" value="">
                    <input type="password" name="prevent_autofill_pass" autocomplete="current-password" tabindex="-1" value="">
                </div>

                <studio:field :label="__('dashboard.Client ID')">
                    <studio:input
                        type="text"
                        wire:model="clientId"
                        name="github_oauth_client_id"
                        autocomplete="off"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        data-1p-ignore
                        data-lpignore="true"
                        data-form-type="other"
                        readonly
                        x-on:focus="$el.removeAttribute('readonly')"
                        :placeholder="__('dashboard.Client ID')"
                    />
                </studio:field>

                <studio:field :label="__('dashboard.Client secret')">
                    <studio:input
                        type="password"
                        wire:model="clientSecret"
                        name="github_oauth_client_secret"
                        autocomplete="new-password"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        data-1p-ignore
                        data-lpignore="true"
                        data-form-type="other"
                        readonly
                        x-on:focus="$el.removeAttribute('readonly')"
                        :placeholder="$githubHasStoredSecret ? __('dashboard.•••• keep current') : __('dashboard.Client secret')"
                    />
                </studio:field>

                @if ($githubHasStoredSecret)
                    <studio:checkbox :label="__('dashboard.Clear stored secret')" wire:model="clearSecret" />
                @endif

                <studio:field :label="__('dashboard.Callback URL')">
                    <studio:input
                        type="text"
                        copyable
                        readonly
                        :value="$githubCallbackUrl"
                        autocomplete="off"
                        name="github_oauth_callback"
                        tabindex="-1"
                    />
                </studio:field>

                <studio:separator />

                <div>
                    <p class="text-sm font-semibold text-studio-fg">{{ __('dashboard.Import limits') }}</p>
                    <p class="mt-0.5 text-xs text-studio-muted">
                        {{ __('dashboard.Limits applied when Lab imports a GitHub repository archive.') }}
                    </p>
                </div>

                <studio:field :label="__('dashboard.Max archive size (MB)')">
                    <studio:input
                        type="number"
                        wire:model="maxZipMb"
                        min="{{ $maxZipMbMin }}"
                        max="{{ $maxZipMbMax }}"
                        step="1"
                        inputmode="numeric"
                        autocomplete="off"
                        name="github_max_zip_mb"
                        :placeholder="__('dashboard.50')"
                    />
                    <x-slot:description>
                        {{ __('dashboard.Allowed range: :min–:max MB. Default is :default MB.', [
                            'min' => $maxZipMbMin,
                            'max' => $maxZipMbMax,
                            'default' => \App\Lab\Github\LabGithubImportLimits::MAX_ZIP_MB_DEFAULT,
                        ]) }}
                    </x-slot:description>
                </studio:field>

                <studio:field :label="__('dashboard.Max files')">
                    <studio:input
                        type="number"
                        wire:model="maxFiles"
                        min="{{ $maxFilesMin }}"
                        max="{{ $maxFilesMax }}"
                        step="100"
                        inputmode="numeric"
                        autocomplete="off"
                        name="github_max_files"
                        :placeholder="__('dashboard.5000')"
                    />
                    <x-slot:description>
                        {{ __('dashboard.Allowed range: :min–:max files. Default is :default. For monorepos set Root directory (e.g. apps/www).', [
                            'min' => number_format($maxFilesMin),
                            'max' => number_format($maxFilesMax),
                            'default' => number_format(\App\Lab\Github\LabGithubImportLimits::MAX_FILES_DEFAULT),
                        ]) }}
                    </x-slot:description>
                </studio:field>
            @elseif ($isDatastore)
                <div class="sr-only" aria-hidden="true">
                    <input type="text" name="prevent_autofill_user" autocomplete="username" tabindex="-1" value="">
                    <input type="password" name="prevent_autofill_pass" autocomplete="current-password" tabindex="-1" value="">
                </div>

                <studio:field :label="__('dashboard.OAuth client ID')">
                    <studio:input
                        type="text"
                        wire:model="clientId"
                        name="supabase_oauth_client_id"
                        autocomplete="off"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        data-1p-ignore
                        data-lpignore="true"
                        data-form-type="other"
                        readonly
                        x-on:focus="$el.removeAttribute('readonly')"
                        :placeholder="__('dashboard.OAuth client ID')"
                    />
                </studio:field>

                <studio:field :label="__('dashboard.OAuth client secret')">
                    <studio:input
                        type="password"
                        wire:model="clientSecret"
                        name="supabase_oauth_client_secret"
                        autocomplete="new-password"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        data-1p-ignore
                        data-lpignore="true"
                        data-form-type="other"
                        readonly
                        x-on:focus="$el.removeAttribute('readonly')"
                        :placeholder="$supabaseHasStoredSecret ? __('dashboard.•••• keep current') : __('dashboard.OAuth client secret')"
                    />
                </studio:field>

                @if ($supabaseHasStoredSecret)
                    <studio:checkbox :label="__('dashboard.Clear stored secret')" wire:model="clearSecret" />
                @endif

                @if (str_starts_with(strtolower((string) $supabaseCallbackUrl), 'https://'))
                    <studio:field :label="__('dashboard.Callback URL')">
                        <studio:input
                            type="text"
                            copyable
                            readonly
                            :value="$supabaseCallbackUrl"
                            autocomplete="off"
                            name="supabase_oauth_callback"
                            tabindex="-1"
                        />
                        <x-slot:description>
                            {{ __('dashboard.Add this callback URL in Supabase Dashboard → Organization → OAuth Apps. Enable Organizations and Projects read (and write if chat should apply SQL).') }}
                        </x-slot:description>
                    </studio:field>
                @else
                    <studio:callout tone="warning">
                        <studio:callout.heading>{{ __('dashboard.Supabase requires HTTPS') }}</studio:callout.heading>
                        <studio:callout.text>{{ __('dashboard.Do not paste http://127.0.0.1 into Supabase. Use your Cloudflare tunnel callback, then save it as the redirect override.') }}</studio:callout.text>
                    </studio:callout>
                @endif

                <studio:field :label="__('dashboard.OAuth redirect URI (optional override)')">
                    <studio:input
                        type="url"
                        wire:model="redirectUri"
                        name="supabase_oauth_redirect"
                        autocomplete="off"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        data-1p-ignore
                        data-lpignore="true"
                        data-form-type="other"
                        :placeholder="$supabaseCallbackUrl"
                    />
                </studio:field>
            @elseif ($isUnsplash)
                <div class="sr-only" aria-hidden="true">
                    <input type="text" name="prevent_autofill_user" autocomplete="username" tabindex="-1" value="">
                    <input type="password" name="prevent_autofill_pass" autocomplete="current-password" tabindex="-1" value="">
                </div>

                <studio:field :label="__('dashboard.Application ID')">
                    <studio:input
                        type="text"
                        wire:model="applicationId"
                        name="unsplash_application_id"
                        autocomplete="off"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        data-1p-ignore
                        data-lpignore="true"
                        data-form-type="other"
                        :placeholder="__('dashboard.Application ID')"
                    />
                    <x-slot:description>{{ __('dashboard.Unsplash Application ID. From your Unsplash developers page. Lab does not send it with photograph searches.') }}</x-slot:description>
                </studio:field>

                <studio:field :label="__('dashboard.Access Key')">
                    <studio:input
                        type="password"
                        wire:model="apiKey"
                        name="unsplash_access_key"
                        autocomplete="new-password"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        data-1p-ignore
                        data-lpignore="true"
                        data-form-type="other"
                        readonly
                        x-on:focus="$el.removeAttribute('readonly')"
                        :placeholder="$maskedKey ? __('dashboard.•••• keep current') : __('dashboard.Paste Access Key')"
                    />
                    <x-slot:description>{{ __('dashboard.Unsplash Access Key (Client-ID). Lab uses this to search photographs on the server.') }}</x-slot:description>
                </studio:field>

                @if ($maskedKey)
                    <studio:checkbox :label="__('dashboard.Clear stored key')" wire:model="clearKey" />
                @endif

                <studio:field :label="__('dashboard.Secret key')">
                    <studio:input
                        type="password"
                        wire:model="apiSecret"
                        name="unsplash_secret_key"
                        autocomplete="new-password"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        data-1p-ignore
                        data-lpignore="true"
                        data-form-type="other"
                        readonly
                        x-on:focus="$el.removeAttribute('readonly')"
                        :placeholder="($visualHasStoredSecret ?? false) ? __('dashboard.•••• keep current') : __('dashboard.Paste Secret key')"
                    />
                    <x-slot:description>{{ __('dashboard.Unsplash Secret key. Stored encrypted. Photograph search uses the Access Key, not this secret.') }}</x-slot:description>
                </studio:field>

                @if ($visualHasStoredSecret ?? false)
                    <studio:checkbox :label="__('dashboard.Clear stored secret')" wire:model="clearSecret" />
                @endif
            @else
                <studio:field :label="__('dashboard.API key')">
                    <studio:input
                        type="password"
                        wire:model="apiKey"
                        autocomplete="off"
                        :placeholder="$maskedKey ? __('dashboard.•••• keep current') : __('dashboard.Paste API key')"
                    />
                    @if ($isVisualCatalog && filled($visualKeyHint ?? null))
                        <x-slot:description>{{ $visualKeyHint }}</x-slot:description>
                    @endif
                </studio:field>

                @if ($maskedKey)
                    <studio:checkbox :label="__('dashboard.Clear stored key')" wire:model="clearKey" />
                @endif
            @endif

            <studio:button type="submit" wire:loading.attr="disabled">{{ __('dashboard.Save provider') }}</studio:button>
        </form>

    @unless ($isGithub || $isVisualCatalog || $isDatastore)
        <studio:separator />

        <section class="space-y-4">
            <div>
                <h2 class="text-lg font-semibold tracking-tight">{{ __('dashboard.Models') }}</h2>
                <p class="mt-1 text-sm text-studio-muted">
                    {{ __('dashboard.Only one model can be the Lab default. Turning a switch on replaces the current default.') }}
                </p>
                @if ($workspaceDefault && $workspaceDefault->provider !== $provider)
                    <p class="mt-1 text-sm text-studio-muted">
                        {{ __('dashboard.Current default: :model.', ['model' => $workspaceDefault->label]) }}
                    </p>
                @endif
            </div>

            @if (count($models) > 0)
                <div class="divide-y divide-studio-line rounded-xl border border-studio-line">
                    @foreach ($models as $model)
                        @php $isDefault = $defaultModelId === $model->id; @endphp
                        <div class="flex items-center justify-between gap-4 px-4 py-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-studio-fg">{{ $model->label }}</p>
                                @if ($isDefault)
                                    <p class="mt-0.5 text-xs text-studio-muted">{{ __('dashboard.Used by Lab until the chat picker chooses another.') }}</p>
                                @endif
                            </div>
                            <studio:switch
                                :label="__('dashboard.Default')"
                                align="right"
                                wire:click="setAsDefault('{{ $model->id }}')"
                                :checked="$isDefault"
                                :disabled="$isDefault"
                            />
                        </div>
                    @endforeach
                </div>
            @else
                <studio:callout tone="warning">
                    <studio:callout.text>{{ __('dashboard.No models registered for this provider in :file.', ['file' => 'config/ai.php']) }}</studio:callout.text>
                </studio:callout>
            @endif
        </section>

        <studio:separator />

        <section class="space-y-4">
            <div>
                <h2 class="text-lg font-semibold tracking-tight">{{ __('dashboard.Project cost') }}</h2>
                <p class="mt-1 text-sm text-studio-muted">
                    {{ __('dashboard.Pick a project type, then set how many projects (1–:max).', ['max' => number_format($projectCountMax)]) }}
                </p>
            </div>

            <div class="space-y-5">
                @if (count($models) > 0)
                    <div class="grid gap-4 sm:grid-cols-2">
                        <studio:field :label="__('dashboard.Model')">
                            <studio:select wire:model.live="modelId" :value="$modelId" placeholder="{{ __('dashboard.Choose a model…') }}">
                                @foreach ($models as $model)
                                    <studio:select.option :value="$model->id" :selected="$modelId === $model->id">
                                        {{ $model->label }}
                                    </studio:select.option>
                                @endforeach
                            </studio:select>
                        </studio:field>

                        <studio:field :label="__('dashboard.Project type')">
                            <studio:select wire:model.live="scenarioId" :value="$scenarioId" placeholder="{{ __('dashboard.Choose a type…') }}">
                                @foreach ($scenarios as $scenario)
                                    <studio:select.option :value="$scenario['id']" :selected="$scenarioId === $scenario['id']">
                                        {{ $scenario['label'] }}
                                    </studio:select.option>
                                @endforeach
                            </studio:select>
                        </studio:field>
                    </div>

                    @if ($project && $activeScenario)
                        <div
                            wire:key="cost-{{ $modelId }}-{{ $scenarioId }}"
                            class="space-y-3"
                            x-data="{
                                count: {{ (int) $projectCount }},
                                unitCost: {{ $unitCostJs === null ? 'null' : $unitCostJs }},
                                inputTokens: {{ (int) $activeScenario['input_tokens'] }},
                                outputTokens: {{ (int) $activeScenario['output_tokens'] }},
                                projectLabel: @js(__('dashboard.project')),
                                projectsLabel: @js(__('dashboard.projects')),
                                inputTokensTpl: @js(__('dashboard.~:count input tokens')),
                                outputTokensTpl: @js(__('dashboard.~:count output tokens')),
                                cost() {
                                    if (this.unitCost === null) return null
                                    const n = this.safeCount()
                                    return Math.round(this.unitCost * n * 10000) / 10000
                                },
                                safeCount() {
                                    return Math.max(1, Math.min(1000, Math.round(Number(this.count) || 0)))
                                },
                                costLabel() {
                                    const c = this.cost()
                                    if (c === null) return '-'
                                    return '$' + c.toLocaleString(undefined, {
                                        minimumFractionDigits: c < 0.01 ? 4 : 2,
                                        maximumFractionDigits: c < 0.01 ? 4 : 2,
                                    })
                                },
                                countLabel() {
                                    const n = this.safeCount()
                                    return n.toLocaleString() + ' ' + (n === 1 ? this.projectLabel : this.projectsLabel)
                                },
                                inputTokensLabel() {
                                    return this.inputTokensTpl.replace(':count', (this.inputTokens * this.safeCount()).toLocaleString())
                                },
                                outputTokensLabel() {
                                    return this.outputTokensTpl.replace(':count', (this.outputTokens * this.safeCount()).toLocaleString())
                                },
                                sync() {
                                    $wire.$set('projectCount', this.safeCount(), false)
                                },
                            }"
                        >
                            <div>
                                <p class="text-xs uppercase tracking-wide text-studio-muted">{{ __('dashboard.Estimated API cost') }}</p>
                                <p
                                    class="mt-1 text-3xl font-semibold tracking-tight tabular-nums text-studio-fg"
                                    x-text="costLabel()"
                                ></p>
                            </div>

                            <studio:field :label="__('dashboard.Projects')">
                                <studio:slider
                                    :min="$projectCountMin"
                                    :max="$projectCountMax"
                                    step="1"
                                    :value="$projectCount"
                                    x-model.number="count"
                                    x-on:input="sync()"
                                />
                                <x-slot:description>
                                    <span x-text="countLabel()"></span>
                                </x-slot:description>
                            </studio:field>

                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-studio-muted">
                                <span x-text="inputTokensLabel()"></span>
                                <span x-text="outputTokensLabel()"></span>
                                @if ($pricing)
                                    <span>
                                        ${{ number_format($pricing['input_per_mtok'], 2) }}/M in ·
                                        ${{ number_format($pricing['output_per_mtok'], 2) }}/M out
                                    </span>
                                @endif
                            </div>

                            <p class="text-xs leading-relaxed text-studio-muted">
                                {{ $activeScenario['blurb'] }}
                            </p>
                        </div>
                    @endif
                @else
                    <studio:callout tone="warning">
                        <studio:callout.text>{{ __('dashboard.No models registered for this provider in :file.', ['file' => 'config/ai.php']) }}</studio:callout.text>
                    </studio:callout>
                @endif
            </div>
        </section>
    @endunless
</div>
