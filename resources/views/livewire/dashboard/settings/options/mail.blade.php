<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-studio-fg">{{ __('dashboard.Settings') }}</h1>
        <p class="mt-0.5 text-xs text-studio-muted">
            {{ __('dashboard.From address, SMTP, and a test send.') }}
        </p>
    </div>

    @include('livewire.dashboard.settings.options.nav')

    <form wire:submit="save" class="space-y-10" autocomplete="off">
        <div class="sr-only" aria-hidden="true">
            <input type="text" name="prevent_autofill_user" autocomplete="username" tabindex="-1" value="">
            <input type="password" name="prevent_autofill_pass" autocomplete="current-password" tabindex="-1" value="">
        </div>

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Sender') }}</p>

            <studio:field :label="__('dashboard.Mailer')">
                <studio:select size="md" wire:model.live="mailer" :value="$mailer">
                    @foreach ($mailers as $value => $label)
                        <studio:select.option :value="$value" :selected="$mailer === $value">{{ $label }}</studio:select.option>
                    @endforeach
                </studio:select>
                <x-slot:description>{{ __('dashboard.Log writes messages to the app log instead of sending them.') }}</x-slot:description>
            </studio:field>

            <div class="grid gap-4 sm:grid-cols-2">
                <studio:field :label="__('dashboard.From name')">
                    <studio:input size="md" wire:model="fromName" :placeholder="__('dashboard.Supreme')" :invalid="$errors->has('fromName')" />
                    @error('fromName')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:field :label="__('dashboard.From address')">
                    <studio:input size="md" type="email" wire:model="fromAddress" :placeholder="__('dashboard.hello@example.com')" :invalid="$errors->has('fromAddress')" />
                    @error('fromAddress')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
            </div>

            <studio:field :label="__('dashboard.Reply-to')">
                <studio:input size="md" type="email" wire:model="replyTo" :placeholder="__('dashboard.hello@example.com')" :invalid="$errors->has('replyTo')" />
                @error('replyTo')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </studio:card>

        @if ($mailer === 'smtp')
            <studio:card :padding="false" class="space-y-4 !border-0">
                <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.SMTP') }}</p>

                <div class="grid gap-4 sm:grid-cols-2">
                    <studio:field :label="__('dashboard.Host')">
                        <studio:input size="md" wire:model="host" placeholder="smtp.example.com" :invalid="$errors->has('host')" />
                        @error('host')
                            <studio:field.error>{{ $message }}</studio:field.error>
                        @enderror
                    </studio:field>
                    <studio:field :label="__('dashboard.Port')">
                        <studio:input size="md" type="number" wire:model="port" :placeholder="__('dashboard.587')" :invalid="$errors->has('port')" />
                        @error('port')
                            <studio:field.error>{{ $message }}</studio:field.error>
                        @enderror
                    </studio:field>
                </div>

                <studio:field :label="__('dashboard.Encryption')">
                    <studio:select size="md" wire:model="encryption" :value="$encryption">
                        @foreach ($encryptions as $value => $label)
                            <studio:select.option :value="$value" :selected="$encryption === $value">{{ $label }}</studio:select.option>
                        @endforeach
                    </studio:select>
                </studio:field>

                <div class="grid gap-4 sm:grid-cols-2">
                    <studio:field :label="__('dashboard.Username')">
                        <studio:input
                            size="md"
                            wire:model="username"
                            autocomplete="off"
                            data-1p-ignore
                            data-lpignore="true"
                            :placeholder="__('dashboard.SMTP username')"
                            :invalid="$errors->has('username')"
                        />
                        @error('username')
                            <studio:field.error>{{ $message }}</studio:field.error>
                        @enderror
                    </studio:field>
                    <studio:field :label="__('dashboard.Password')">
                        <studio:input
                            size="md"
                            type="password"
                            wire:model="password"
                            autocomplete="new-password"
                            data-1p-ignore
                            data-lpignore="true"
                            readonly
                            x-on:focus="$el.removeAttribute('readonly')"
                            :placeholder="$hasPassword ? __('dashboard.•••• keep current') : __('dashboard.SMTP password')"
                            :invalid="$errors->has('password')"
                        />
                        @error('password')
                            <studio:field.error>{{ $message }}</studio:field.error>
                        @enderror
                    </studio:field>
                </div>

                @if ($hasPassword)
                    <studio:checkbox :label="__('dashboard.Clear stored password')" wire:model="clearPassword" />
                @endif
            </studio:card>
        @endif

        <studio:card :padding="false" class="space-y-4 !border-0">
            <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Test send') }}</p>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <studio:field :label="__('dashboard.Send a test to')" class="min-w-0 flex-1">
                    <studio:input size="md" type="email" wire:model="testRecipient" :placeholder="__('dashboard.you@example.com')" :invalid="$errors->has('testRecipient')" />
                    @error('testRecipient')
                        <studio:field.error>{{ $message }}</studio:field.error>
                    @enderror
                </studio:field>
                <studio:button type="button" variant="outline" wire:click="sendTest" wire:loading.attr="disabled">
                    {{ __('dashboard.Send test') }}
                </studio:button>
            </div>
        </studio:card>

        <div class="flex justify-end">
            <studio:button type="submit" wire:loading.attr="disabled">
                {{ __('dashboard.Save changes') }}
            </studio:button>
        </div>
    </form>
</div>
