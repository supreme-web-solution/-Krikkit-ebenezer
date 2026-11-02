<studio:modal name="rename-project" size="sm">
    <studio:modal.close />
    <h3 class="pr-8 text-lg font-semibold text-studio-fg">{{ __('studio.Rename') }}</h3>
    <form wire:submit="saveRename" class="mt-4">
        <studio:field :label="__('studio.Name')">
            <studio:input wire:model="renameTitle" maxlength="120" :invalid="$errors->has('renameTitle')" autofocus />
            @error('renameTitle')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </studio:field>
        <div class="mt-5 flex justify-end gap-2">
            <studio:button type="button" variant="ghost" @click="$dispatch('studio-modal-close', 'rename-project')">
                {{ __('dashboard.Cancel') }}
            </studio:button>
            <studio:button type="submit">{{ __('studio.Save') }}</studio:button>
        </div>
    </form>
</studio:modal>

<studio:confirm name="delete-project" :title="__('studio.Delete project')" :copy="__('studio.Delete this project? This cannot be undone.')">
    <x-slot:action>
        <studio:button type="button" variant="danger" wire:click="confirmDelete">
            {{ __('studio.Delete') }}
        </studio:button>
    </x-slot:action>
</studio:confirm>
