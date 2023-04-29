<div>
    @if (count($documents) === 0)
        <p class="py-8">Még nincs dokumentum feltöltve ehhez a kategóriához.</p>
    @else
        <table>
            <thead>
            <tr>
                <th scope="col">{{ __('Fájl') }}</th>
                <th scope="col">{{ __('Műveletek') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($documents as $document)
                <tr>
                    <td>

                        @can('authorize_download_from_category', $selectedCategory)
                            <h3>
                                <a href="{{ $document->file_link }}" download>
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    {{ $document->view_name }}</a>
                            </h3>
                            @if($document->file_link)
                                <!--<img src="{{ $document->file_link }}" class="p-1 bg-white border rounded w-20"
                            alt="{{ $document->view_name }}" />-->
                            @endif
                        @else
                            <h3>
                                {{ $document->view_name }}
                            </h3>
                            <div>Nincs letöltési jogod a kategóriához!</div>
                        @endcan

                        <ul>
                            <li>Eredeti fájlnév: <b>{{ $document->original_filename }}</b></li>
                            <li>Verzió: <b>{{ $document->version }}</b></li>
                            <li>Méret: <b>{{ $document->filesize }}</b></li>
                            <li>Feltöltve: <b>{{ $document->last_modified->diffForHumans() }}</b></li>
                        </ul>

                    </td>

                    <td>
                        <div>

                            <div x-data="{ modalOpen: false }">
                                <button @click="modalOpen = true" class="edit-button">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>

                                <x-admin.modal :title="$document->view_name">
                                    <form action="{{ route('document.update', $document->id)}}"
                                          method="POST" enctype="multipart/form-data"
                                          accept-charset="UTF-8" autocomplete="off"
                                    >
                                        @method("PUT")
                                        @csrf

                                        <div>
                                            <label for="view_name">{{ __('Megjelenő név*') }}</label>
                                            <input id="view_name"
                                                   class="{{ $errors->has('view_name') ? ' border-rose-400' : '' }}"
                                                   type="text"
                                                   name="view_name"
                                                   value="{{ old('view_name') ?? $document->view_name }}"
                                                   autofocus
                                            />
                                            <x-admin.input-error for="view_name"/>
                                        </div>

                                        <input type="number"
                                               class="hidden"
                                               name="category_id"
                                               value="{{ intval($selectedCategory->id) }}"
                                        >

                                        <div>

                                            <div>
                                                Feltöltött fájl:<br>
                                                <b>{{ $document->file_link }}</b>
                                            </div>

                                            <div class="margin-bottom-1">
                                                <label for="file_path">{{ __('Fájl cseréje (opcionális)') }}</label>
                                                <input
                                                    class="{{ $errors->has('file_path') ? ' border-rose-400' : 'border-gray-300' }}"
                                                    type="file"
                                                    id="file_path"
                                                    name="file_path"
                                                    autofocus
                                                >
                                            </div>

                                            <x-admin.input-error for="file_path"/>
                                        </div>


                                        <button type="submit" class="button">Mentés</button>
                                    </form>
                                </x-admin.modal>
                            </div>

                            <div x-data="{ modalOpen: false }">
                                <button type="button"
                                        class="button-danger"
                                        title="Törlés"
                                        @click="modalOpen = true">

                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                                <x-admin.modal :title="'Biztosan törölni akarod?'">
                                    <form action="{{ route('document.destroy', $document->id) }}"
                                          method="post"
                                          enctype="application/x-www-form-urlencoded"
                                          accept-charset="UTF-8"
                                          autocomplete="off"
                                    >
                                        @csrf
                                        @method('delete')

                                        <div>{{ $document->view_name }}</div>
                                        <button type="submit"
                                                class="button-danger"
                                                title="Törlés">
                                            Törlés
                                        </button>
                                    </form>
                                </x-admin.modal>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
