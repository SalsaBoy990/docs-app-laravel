<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-12 bold info" title="{{ __('Kategória szerkesztése') }}">
            <i class="fa fa-pencil" aria-hidden="true"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-12 bold info">
            <i class="fa fa-pencil" aria-hidden="true"></i>
            <span>{{ __('Szerkeszt') }}</span>
        </button>
    @endif

    <x-admin.form-modal
        trigger="isModalOpen"
        title="{{ __('Kategória szerkesztése') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="updateCategory">
            <h2>{{ $name }}</h2>
            <fieldset>
                <label for="name">{{ __('Kategória neve') }}</label>
                <input wire:model.defer="name"
                       type="text"
                       class="{{ $errors->has('name') ? 'input-error' : 'input-default' }}"
                       name="name"
                       value=""
                       placeholder="{{ __('alkategória neve') }}"
                >

                <div
                    class="{{ $errors->has('name') ? 'danger fs-14 text-red-dark' : '' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>
            </fieldset>

            <div>
                <button type="submit" class="primary">
                    <span wire:loading wire:target="updateCategory" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="updateCategory">{{ __('Frissít') }}</span>
                </button>
                <button type="button" class="primary alt" @click="isModalOpen = false">
                    Mégsem
                </button>
            </div>
        </form>

    </x-admin.form-modal>
</div>
