<x-admin-layout>

    @section('search')
        <livewire:category.search></livewire:category.search>
    @endsection

    <x-slot name="sidebar">

        <div class="padding-1">
            <!-- Create root category -->
            <livewire:category.create-root :title="'Kategória hozzáadása'" :hasSmallButton="false">
            </livewire:category.create-root>
        </div>

        <ul id="categories-tree" class="padding-left-right-0 margin-top-0 no-bullets">
            @foreach ($categories as $category)
                <li>
                    <div class="{{ $selectedCategory->id === $category->id ? 'active-category' : '' }}">

                        <div class="flex padding-0-5">
                            @if (count($category->categories) > 0)
                                <span class="caret caret-down"></span>
                            @endif
                            <h2 class="fs-16 margin-top-bottom-0">
                                <a class="underline" href="{{ route('category.selected', $category->id)}}">
                                    {{ $category->name }}
                                </a>
                            </h2>
                        </div>

                        <div class="button-group padding-left-0-5 margin-bottom-0-5">

                            <!-- Delete category -->
                            <livewire:category.delete :modalId="'m-delete-' . $category->id"
                                                      :category="$category"
                                                      :hasSmallButton="false">
                            </livewire:category.delete>

                            <!-- Create sub-category -->
                            <livewire:category.create :modalId="'m-add-' . $category->id"
                                                      :category="$category"
                                                      :hasSmallButton="false">
                            </livewire:category.create>


                            <div x-data="modalData">
                                <button @click="openModal()" class="fs-14 bold info">
                                    <i class="fa fa-upload" aria-hidden="true"></i>
                                    Fájl
                                </button>

                                <x-admin.modal :title="'Dokumentum hozzáadása'">
                                    <form
                                        action="{{ route('document.store', $category->id)}}"
                                        method="POST"
                                        enctype="multipart/form-data"
                                        accept-charset="UTF-8"
                                        autocomplete="off"
                                    >
                                        @method("POST")
                                        @csrf

                                        <div class="mb-5">
                                            <label for="view_name">{{ __('Megjelenő név*') }}</label>
                                            <input id="view_name"
                                                   class="{{ $errors->has('view_name') ? ' border-rose-400' : '' }}"
                                                   type="text"
                                                   name="view_name"
                                                   value="{{ old('view_name') ?? '' }}"
                                                   autofocus
                                            />
                                            <x-admin.input-error for="view_name"/>
                                        </div>

                                        <input type="number"
                                               class="hidden"
                                               name="category_id"
                                               value="{{ intval($category->id) }}"
                                        >

                                        <div class="margin-bottom-1">
                                            <label for="file_path">{{ __('Fájl feltöltése*') }}</label>

                                            <div>
                                                <input
                                                    class="file-input {{ $errors->has('file_path') ? ' border-rose-400' : 'border-gray-300' }}"
                                                    type="file"
                                                    id="file_path"
                                                    name="file_path"
                                                    autofocus
                                                >
                                            </div>

                                            <x-admin.input-error for="file_path" class="mt-2"/>
                                        </div>

                                        <button type="submit" class="primary">Hozzáadás</button>

                                        <button
                                            type="button"
                                            class="alt"
                                            @click="closeModal()"
                                        >
                                            {{ __('Mégsem') }}
                                        </button>

                                    </form>
                                </x-admin.modal>
                            </div>

                            <div x-data="modalData">
                                <button @click="openModal()" class="fs-14 bold success">
                                    <i class="fa fa-key" aria-hidden="true"></i>
                                    Jogok
                                </button>

                                <x-admin.modal :title="'Engedélyek kezelése'">
                                    <div>
                                        @can('authorize_upload_to_category', $category)
                                            <form
                                                action="{{ route('permission.upload.detach', ['category' => $category->id, 'user' => auth()->user()->id ])}}">
                                                @method('get')
                                                @csrf

                                                <label for="">
                                                    <input type="checkbox"
                                                           name=""
                                                           id=""
                                                           class="inactive-checkbox checked"
                                                           checked
                                                           disabled
                                                    >
                                                    Feltöltés
                                                </label>
                                                <button>
                                                    Letilt
                                                </button>
                                            </form>

                                        @else
                                            <form
                                                action="{{ route('permission.upload.attach', ['category' => $category->id, 'user' => auth()->user()->id ])}}">
                                                @method('get')
                                                @csrf

                                                <label for="">
                                                    <input
                                                        type="checkbox"
                                                        name=""
                                                        id=""
                                                        class="inactive-checkbox"
                                                        disabled
                                                    >
                                                    Feltöltés
                                                </label>

                                                <button>
                                                    Enged
                                                </button>
                                            </form>
                                        @endcan

                                        @can('authorize_download_from_category', $category)
                                            <form
                                                action="{{ route('permission.download.detach', ['category' => $category->id, 'user' => auth()->user()->id ])}}">
                                                @method('get')
                                                @csrf

                                                <label for="" class="text-sm">
                                                    <input type="checkbox" name="" id=""
                                                           class="inactive-checkbox checked" disabled
                                                           checked>
                                                    Letöltés
                                                </label>

                                                <button>
                                                    Letilt
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ route('permission.download.attach', ['category' => $category->id, 'user' => auth()->user()->id ])}}">
                                                @method('get')
                                                @csrf
                                                <label for="">
                                                    <input type="checkbox" name="" id=""
                                                           class="inactive-checkbox" disabled>
                                                    Letöltés
                                                </label>
                                                <button>Enged</button>
                                            </form>
                                        @endcan

                                        <button
                                            type="button"
                                            class="alt float-right"
                                            @click="closeModal()"
                                        >
                                            {{ __('Mégsem') }}
                                        </button>

                                    </div>
                                </x-admin.modal>
                            </div>

                        </div>

                    </div>

                    <ul class="no-bullets margin-top-0 padding-left-2 padding-right-0 nested active">
                        @foreach ($category->categories as $childCategory)
                            <x-admin.child-category-list :childCategory="$childCategory"
                                                         :selectedCategory="$selectedCategory">
                            </x-admin.child-category-list>
                        @endforeach
                    </ul>
                </li>
                <hr class="margin-bottom-0-5">
            @endforeach
            <li>
                <div class="padding-1">
                    @can('authorize_upload_to_root', auth()->user())
                        <form
                            action="{{ route('permission.root.upload.toggle', [ 'user' => auth()->user()->id ])}}">
                            @method('get')
                            @csrf

                            <div class="flex" style="
                                justify-content: space-between;
                                align-items: center;">
                                <label for="">
                                    <input type="checkbox" name="" id=""
                                           class="inactive-checkbox checked" disabled checked>
                                    Feltöltés a gyökérbe
                                </label>

                                <button style="width: fit-content; height: 36px">
                                    Tiltás
                                </button>
                            </div>
                        </form>
                    @else
                        <form
                            action="{{ route('permission.root.upload.toggle', [ 'user' => auth()->user()->id ])}}">
                            @method('get')
                            @csrf

                            <div class="upload-to-root">
                                <label for="">
                                    <input type="checkbox" name="" id=""
                                           class="inactive-checkbox" disabled>
                                    Feltöltés a gyökérbe
                                </label>
                                <button class="button primary alt">Enged</button>
                            </div>
                        </form>
                    @endcan

                </div>

            </li>
        </ul>

    </x-slot>

    @section('content')

        <main class="padding-1">
            @if($parentCategories)
                <nav class="breadcrumb">
                    <ol>
                        @foreach($parentCategories as $key => $value)
                            <li>
                                <a href="{{ route('category.selected', $key)}}">{{ $value }}</a>
                            </li>
                            <li>
                                <span>/</span>
                            </li>
                        @endforeach
                        <li>{{ $selectedCategory->name }}</li>
                    </ol>
                </nav>
            @endif


            <div class="flex padding-bottom-1" style="column-gap: 1em;align-items: center;">
                <h2 class="margin-top-bottom-0">{{ $selectedCategory->name }}</h2>
                <a href="{{ route('category.update', $selectedCategory->id)}}"
                   title="{{ __('Kategória szerkesztése') }}">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </a>
            </div>

            <div class="main-content">


                {!! $selectedCategory->content !!}

                <h4>Feltöltött dokumentumok</h4>
                <x-admin.documents-table :documents="$documents" :selectedCategory="$selectedCategory">
                </x-admin.documents-table>

            </div>
        </main>

    @endsection


</x-admin-layout>
