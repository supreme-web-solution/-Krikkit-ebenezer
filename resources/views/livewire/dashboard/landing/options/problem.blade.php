<studio:card :padding="false" class="space-y-4 !border-0">
    <studio:field :label="__('dashboard.Title')">
        <studio:textarea rows="5" wire:model="problem.title" />
    </studio:field>
    <studio:field :label="__('dashboard.Copy')">
        <studio:textarea rows="3" wire:model="problem.copy" />
    </studio:field>
</studio:card>
