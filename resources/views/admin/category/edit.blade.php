<x-admin-nosidebar-layout>

    @section('content')
        <main class="padding-1">
            <a href="{{ route('dashboard')}}"
               class="button alt bg-white">{{ __('Vissza') }}</a>
            <p class="margin-bottom-0">{{ __('Kategória szerkesztése') }}</p>
            <h1 class="margin-0 h2">
                {{ $category->name }}
            </h1>

            <form action="{{ route('category.update', $category->id)}}"
                  method="POST"
                  enctype="application/x-www-form-urlencoded"
                  accept-charset="UTF-8"
                  autocomplete="off"
            >
                @method("PUT")
                @csrf

                <x-admin.validation-errors/>

                <div class="mb-5">
                    <label for="name">{{ __('Kategória neve') }}</label>
                    <input id="name"
                           class="{{ $errors->has('name') ? ' border border-red' : '' }}"
                           type="text"
                           name="name"
                           value="{{ old('name') ?? $category->name }}"
                           autofocus
                    />
                    <x-admin.input-error for="name"/>
                </div>

                <div class="mb-5">
                    <label for="content">{{ __('Szövegtörzs') }}</label>
                    <div wire:ignore>
                    <textarea wire:model="content" name="content" rows="5" id="update-content-editor"
                              class="{{ $errors->has('content') ? 'border border-red' : '' }}"
                    >
                        {!! $category->content !!}
                    </textarea>
                    </div>
                    <div
                        class="fs-14 {{ $errors->has('content') ? 'red' : 'red' }}">
                        {{ $errors->has('content') ? $errors->first('content') : '' }}
                    </div>
                </div>

                <div>
                    <button type="submit" class="primary">{{ __("Frissít") }}
                    </button>

                    <a href="{{ route('dashboard')}}"
                       class="button alt bg-white">{{ __('Mégsem') }}</a>
                </div>
            </form>
        </main>

    @endsection

</x-admin-nosidebar-layout>
