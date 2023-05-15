<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold primary button margin-top-0" title="{{ __('Új kategória') }}">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold primary button margin-top-0">
            <i class="fa fa-plus" aria-hidden="true"></i>
            Új kategória
        </button>
    @endif

    <x-admin.form-modal trigger="isModalOpen" title="{{ $title }}" id="{{ $modalId }}">
        <form wire:submit.prevent="createCategory">

            <fieldset class="margin-top-1">
                <input wire:model.defer="name"
                       type="text"
                       class="{{ $errors->has('name') ? 'input-error' : 'input-default' }}"
                       name="name"
                       value=""
                       placeholder="kategória neve">

                <div class="{{ $errors->has('name') ? 'text-rose-500' : 'text-gray-500' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>
            </fieldset>

            <div>
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createCategory" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createCategory">{{ __('Hozzáad') }}</span>
                </button>
                <button type="button" class="alt" @click="isModalOpen = false">
                    Mégsem
                </button>
            </div>
        </form>

    </x-admin.form-modal>

</div>
