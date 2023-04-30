<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold danger" title="{{ __('Kategória törlése') }}">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold danger">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
            <span>{{ __('Törlés') }}</span>
        </button>
    @endif

    <x-admin.form-modal trigger="isModalOpen" title="{{ __('Biztosan törölni akarod?') }}"
                        id="{{ $modalId }}">
        <form wire:submit.prevent="deleteCategory">
            <h2>{{ $name }}</h2>

            <input wire:model.defer="categoryId"
                   disabled
                   type="number"
                   class="hidden"
                   name="categoryId"
                   value="{{ $categoryId }}"
            >

            <div>
                <button type="submit" class="danger">
                    <span wire:loading wire:target="deleteCategory" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="deleteCategory">{{ __('Töröl') }}</span>
                </button>
                <button type="button" class="danger alt" @click="isModalOpen = false">
                    Mégsem
                </button>
            </div>
        </form>

    </x-admin.form-modal>
</div>
