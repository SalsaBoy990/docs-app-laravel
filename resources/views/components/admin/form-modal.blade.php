@props(['trigger', 'title', 'id'])


<div x-show="{{ $trigger }} == true"
     x-init="{modalId: @js('#' . $id)}"
     x-on:keydown.escape.window="{{ $trigger }} = false;"
     class="clean-modal"
     :class="{'show': {{ $trigger }} == true}"
     x-cloak
>

    <div
        id="{{ $id }}"
        x-trap="{{ $trigger }} == true"
        {{ $attributes->merge(
            ['class' => 'clean-modal-content content-600 card card-4 animate-top relative']) }}
    >

        <!-- Modal header -->
        <div class="box primary round-top">
                <button @click="{{ $trigger }} = false;"
                        class="close-button fs-18 primary topright border-0 round-top-right text-white"
                >
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            <h3 class="text-white fs-18">{{ $title }}</h3>
        </div>

        <!-- Modal body -->
        <div class="box white padding-bottom-2">
            {{ $slot }}
        </div>


    </div>

</div>


