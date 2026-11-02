@props([
    'roles',
    'countries',
    'statuses',
    'plans' => [],
    'mode' => 'create',
])

@php
    $isEdit = $mode === 'edit';
    $avatarName = $username !== '' ? $username : __('dashboard.User');
    $avatarSrc = $avatar
        ? $avatar->temporaryUrl()
        : ($removeAvatar ? null : $avatarPreviewUrl);
    $hasAvatar = filled($avatarSrc);
    $passwordPlaceholder = $isEdit ? __('dashboard.Leave blank to keep') : __('dashboard.••••••••');
@endphp

<div class="space-y-10">
    {{-- Profile --}}
    <studio:card :padding="false" class="!border-0">
        <p class="mb-4 text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Profile') }}</p>
        <div class="flex flex-col items-start gap-2">
            <div class="relative inline-flex">
                <label class="group relative cursor-pointer">
                    <studio:avatar :name="$avatarName" :src="$avatarSrc" size="xl" />
                    <span
                        class="pointer-events-none absolute inset-0 flex items-center justify-center rounded-full bg-black/45 text-white opacity-0 transition group-hover:opacity-100"
                        aria-hidden="true"
                    >
                        <studio:icon name="arrow-up-tray" class="size-6" />
                    </span>
                    <span class="sr-only">{{ __('dashboard.Upload photo') }}</span>
                    <input type="file" class="sr-only" accept="image/*" wire:model="avatar">
                </label>
                @if ($hasAvatar)
                    <button
                        type="button"
                        wire:click="clearAvatar"
                        class="absolute -right-0.5 -top-0.5 inline-flex size-6 items-center justify-center rounded-full border border-studio-line/50 bg-studio-canvas text-studio-fg transition hover:bg-studio-soft"
                        title="{{ __('dashboard.Remove photo') }}"
                        aria-label="{{ __('dashboard.Remove photo') }}"
                    >
                        <studio:icon name="x-mark" class="size-3.5" />
                    </button>
                @endif
            </div>
            @error('avatar')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </div>
    </studio:card>

    {{-- Identity --}}
    <studio:card :padding="false" class="space-y-4 !border-0">
        <p class="mb-4 text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Identity') }}</p>

        <studio:field :label="__('dashboard.Username')">
            <studio:input size="md" wire:model="username" autocomplete="username" :placeholder="__('dashboard.jane')" :invalid="$errors->has('username')" />
            @error('username')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>

        <div class="grid gap-4 sm:grid-cols-2">
            <studio:field :label="__('dashboard.Email')">
                <studio:input size="md" wire:model="email" type="email" autocomplete="email" :placeholder="__('dashboard.you@example.com')" :invalid="$errors->has('email')" />
                @error('email')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
            <studio:field :label="__('dashboard.Phone')">
                <studio:input size="md" wire:model="phone" type="tel" autocomplete="tel" :placeholder="__('dashboard.+1 555 0100')" :invalid="$errors->has('phone')" />
                @error('phone')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </div>

        <studio:field :label="__('dashboard.Country')">
            <studio:select
                size="md"
                wire:model="country"
                :value="$country"
                searchable
                placeholder="{{ __('dashboard.Choose…') }}"
                :invalid="$errors->has('country')"
            >
                <studio:select.option value="" :selected="$country === ''">{{ __('dashboard.Choose…') }}</studio:select.option>
                @foreach ($countries as $code => $label)
                    <studio:select.option :value="$code" :selected="$country === $code">{{ $label }}</studio:select.option>
                @endforeach
            </studio:select>
            @error('country')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>
    </studio:card>

    {{-- Password --}}
    <studio:card :padding="false" class="space-y-4 !border-0">
        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Password') }}</p>
        <div class="grid gap-4 sm:grid-cols-2">
            <studio:field :label="__('dashboard.Password')">
                <studio:input
                    size="md"
                    type="password"
                    wire:model="password"
                    autocomplete="new-password"
                    :invalid="$errors->has('password')"
                    :placeholder="$passwordPlaceholder"
                />
                @error('password')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
            <studio:field :label="__('dashboard.Confirm password')">
                <studio:input
                    size="md"
                    type="password"
                    wire:model="password_confirmation"
                    autocomplete="new-password"
                    :invalid="$errors->has('password_confirmation')"
                    :placeholder="__('dashboard.••••••••')"
                />
                @error('password_confirmation')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </div>
    </studio:card>

    {{-- Access --}}
    <studio:card :padding="false" class="space-y-4 !border-0">
        <p class="text-[11px] font-normal uppercase tracking-[0.16em] text-studio-subtle">{{ __('dashboard.Access') }}</p>
        <div class="grid gap-4 sm:grid-cols-2">
            <studio:field :label="__('dashboard.Role')">
                <studio:select size="md" wire:model="accessRoleId" :value="$accessRoleId" placeholder="{{ __('dashboard.Choose…') }}" :invalid="$errors->has('accessRoleId')">
                    @foreach ($roles as $role)
                        <studio:select.option :value="(string) $role->id" :selected="$accessRoleId === (string) $role->id">
                            {{ $role->title }}
                        </studio:select.option>
                    @endforeach
                </studio:select>
                @error('accessRoleId')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>

            <studio:field :label="__('dashboard.Status')">
                <studio:select size="md" wire:model="status" :value="$status" placeholder="{{ __('dashboard.Choose…') }}" :invalid="$errors->has('status')">
                    @foreach ($statuses as $statusOption)
                        <studio:select.option :value="$statusOption->value" :selected="$status === $statusOption->value">
                            {{ $statusOption->label() }}
                        </studio:select.option>
                    @endforeach
                </studio:select>
                @error('status')
                    <studio:field.error>{{ $message }}</studio:field.error>
                @enderror
            </studio:field>
        </div>

        <studio:field :label="__('dashboard.Pack')">
            <studio:select size="md" wire:model="planPublicId" :value="$planPublicId" placeholder="{{ __('dashboard.Choose…') }}" :invalid="$errors->has('planPublicId')">
                @foreach ($plans as $plan)
                    <studio:select.option :value="$plan->public_id" :selected="$planPublicId === $plan->public_id">
                        {{ $plan->title }}{{ $plan->isLocked() ? ' ('.__('dashboard.Locked').')' : '' }}
                    </studio:select.option>
                @endforeach
            </studio:select>
            @error('planPublicId')
                <studio:field.error>{{ $message }}</studio:field.error>
            @enderror
        </studio:field>
    </studio:card>
</div>
