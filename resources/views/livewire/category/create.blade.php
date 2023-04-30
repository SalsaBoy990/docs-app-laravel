<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold primary" title="{{ __('Új alkategória') }}">
            <i class="fa fa-plus"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold primary">
            <i class="fa fa-plus"></i>{{ __('Új') }}
        </button>
    @endif

    <x-admin.form-modal
        trigger="isModalOpen"
        title="{{ __('Alkategória hozzáadása') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="createCategory">

            <fieldset>
                <label for="name">{{ __('Alkategória neve') }}</label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="{{ $errors->has('name') ? 'input-error' : 'input-default' }}"
                    name="name"
                    value=""
                >

                <div class="{{ $errors->has('name') ? 'red' : 'gray-80' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>
            </fieldset>

            <input
                wire:model.defer="categoryId"
                disabled
                type="number"
                class="hidden"
                name="categoryId"
                value="{{ $categoryId }}"
            >


            <div>
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createCategory" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createCategory">{{ __('Hozzáad') }}</span>
                </button>

                <button
                    type="button"
                    class="alt"
                    @click="isModalOpen = false"
                >
                    {{ __('Mégsem') }}
                </button>
            </div>

        </form>

    </x-admin.form-modal>
</div>
