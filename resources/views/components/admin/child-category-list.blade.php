<li>
    <div class="{{ $selectedCategory->id === $childCategory->id ? 'active-category' : '' }}">

        <div class="flex padding-0-5">
            @if (count($childCategory->categories) > 0)
                <span class="caret caret-down"></span>
            @endif
            <h3 class="fs-14 margin-top-bottom-0">
                <a href="{{ route('category.selected', $childCategory->id)}}">
                    {{ $childCategory->name }}
                </a>
            </h3>
        </div>

        <div class="button-group padding-left-0-5">
            <!-- Update sub-category -->
            <livewire:category.update :modalId="'m-update-' . $childCategory->id"
                                      :category="$childCategory"
                                      :hasSmallButton="true">
            </livewire:category.update>

            <!-- Delete sub-category -->
            <livewire:category.delete :modalId="'m-delete-' . $childCategory->id"
                                      :category="$childCategory"
                                      :hasSmallButton="true">
            </livewire:category.delete>

            <!-- Create sub-category -->
            <livewire:category.create :modalId="'m-new-' . $childCategory->id"
                                      :category="$childCategory"
                                      :hasSmallButton="true">
            </livewire:category.create>


            <div x-data="modalData">
                <button @click="openModal()" class="fs-14 bold info" title="Feltöltés">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                </button>

                <x-admin.modal :title="'Dokumentum hozzáadása'">
                    <form
                        action="{{ route('document.store', $childCategory->id)}}"
                        method="POST"
                        enctype="multipart/form-data"
                        accept-charset="UTF-8"
                        autocomplete="off"
                    >
                        @method("POST")
                        @csrf

                        <div>
                            <label for="view_name">{{ __('Megjelenő név*') }}</label>
                            <input id="view_name"
                                   class="{{ $errors->has('view_name') ? ' border-rose-400' : '' }}"
                                   type="text"
                                   name="view_name"
                                   value="{{old('view_name') ?? '' }}"
                                   autofocus
                            />
                            <x-admin.input-error for="view_name"/>
                        </div>

                        <input type="number"
                               class="hidden"
                               name="category_id"
                               value="{{ intval($childCategory->id) }}"
                        >

                        <div class="margin-bottom-1">
                            <label for="file_path">{{ __('Fájl feltöltése*') }}</label>
                            <input
                                class="{{ $errors->has('file_path') ? ' border-rose-400' : 'border-gray-300' }}"
                                type="file"
                                id="file_path"
                                name="file_path"
                                autofocus
                            >
                            <x-admin.input-error for="file_path"/>
                        </div>


                        <button type="submit" class="button">
                            Hozzáadás
                        </button>

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
                <button @click="openModal()" class="fs-14 bold success" title="Fel-letöltési jogok">
                    <i class="fa fa-key" aria-hidden="true"></i>
                </button>

                <x-admin.modal :title="'Engedélyek kezelése'">
                    <div>
                        @can('authorize_upload_to_category', $childCategory)
                            <form
                                action="{{ route('permission.upload.detach', ['category' => $childCategory->id, 'user' => auth()->user()->id ])}}">
                                @method('get')
                                @csrf

                                <label for="" class="text-sm">
                                    <input type="checkbox" name="" id="" class="inactive-checkbox" checked
                                           disabled>
                                    Feltöltés
                                </label>
                                <button class="button small ml-1">
                                    Tiltás
                                </button>
                            </form>

                        @else

                            <form
                                action="{{ route('permission.upload.attach', ['category' => $childCategory->id, 'user' => auth()->user()->id ])}}">
                                @method('get')
                                @csrf

                                <label for="" class="text-sm">
                                    <input type="checkbox" name="" id="" class="inactive-checkbox" disabled>
                                    Feltöltés
                                </label>

                                <button class="button small ml-1">
                                    Enged
                                </button>
                            </form>

                        @endcan

                        @can('authorize_download_from_category', $childCategory)

                            <form
                                action="{{ route('permission.download.detach', ['category' => $childCategory->id, 'user' => auth()->user()->id ])}}">
                                @method('get')
                                @csrf

                                <label for="" class="text-sm">
                                    <input type="checkbox" name="" id="" class="inactive-checkbox" disabled
                                           checked>
                                    Letöltés
                                </label>

                                <button class="button small ml-1">
                                    Tiltás
                                </button>
                            </form>
                        @else
                            <form
                                action="{{ route('permission.download.attach', ['category' => $childCategory->id, 'user' => auth()->user()->id ])}}">
                                @method('get')
                                @csrf
                                <label for="" class="text-sm">
                                    <input type="checkbox" name="" id="" class="inactive-checkbox" disabled>
                                    Letöltés
                                </label>
                                <button class="button small ml-1">Enged</button>
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


    <ul class="no-bullets padding-left-1-5 margin-top-0 nested active">
        @if (count($childCategory->categories) > 0)
            @foreach ($childCategory->categories as $childCategory)
                <x-child-category-list :childCategory="$childCategory"
                                       :selectedCategory="$selectedCategory"></x-child-category-list>
            @endforeach
        @endif
    </ul>
</li>

