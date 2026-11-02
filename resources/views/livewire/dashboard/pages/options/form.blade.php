@props(['publicUrl' => null])

<form wire:submit="save" class="space-y-8">
    <studio:switch align="right" class="w-full justify-between" :label="__('dashboard.Publish this page')" wire:model="published" />

    @if ($publicUrl)
        <p class="text-xs text-studio-muted">
            {{ __('dashboard.Public URL') }}:
            <a href="{{ $publicUrl }}" class="break-all text-studio-fg underline" target="_blank" rel="noreferrer">{{ $publicUrl }}</a>
        </p>
    @endif

    <studio:field :label="__('dashboard.Title')">
        <studio:input size="md" wire:model.live="title" :placeholder="__('dashboard.Page title')" :invalid="$errors->has('title')" />
        @error('title')
            <studio:field.error>{{ $message }}</studio:field.error>
        @enderror
    </studio:field>

    <studio:field :label="__('dashboard.Slug')">
        <studio:input size="md" wire:model="slug" :placeholder="__('dashboard.about-us')" :invalid="$errors->has('slug')" />
        @error('slug')
            <studio:field.error>{{ $message }}</studio:field.error>
        @enderror
    </studio:field>

    <studio:field :label="__('dashboard.Body')">
        <studio:editor
            :value="$body"
            wire:model="body"
            :placeholder="__('dashboard.Write…')"
            :invalid="$errors->has('body')"
        />
        @error('body')
            <studio:field.error>{{ $message }}</studio:field.error>
        @enderror
    </studio:field>

    <div class="flex justify-end">
        <studio:button type="submit" wire:loading.attr="disabled">
            {{ __('dashboard.Save changes') }}
        </studio:button>
    </div>
</form>
