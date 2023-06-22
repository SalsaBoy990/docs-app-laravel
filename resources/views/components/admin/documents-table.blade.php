<div>
    @if (count($documents) === 0)
        <p>{{ __('There are no documents uploaded in this category yet.') }}</p>
    @else
        <table class="fs-14">
            <thead>
            <tr>
                <th scope="col">{{ __('File') }}</th>
                <th scope="col">{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($documents as $document)
                <tr>
                    <td>
                        @can('authorize_download_from_category', $selectedCategory)
                            <h3 class="margin-top-0 margin-bottom-0-5 fs-16">
                                <a href="{{ $document->file_link }}" download>
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    {{ $document->view_name }}</a>
                            </h3>

                            @if($document->file_link)
                                <!--<img src="{{ $document->file_link }}" class="p-1 bg-white border rounded w-20"
                            alt="{{ $document->view_name }}" />-->
                            @endif

                        @else

                            <h3 class="margin-top-0 margin-bottom-0-5 fs-16">{{ $document->view_name }}</h3>
                            <div>{{ __('You have no download right in this category.') }}</div>

                        @endcan

                        <ul class="margin-top-bottom-0-5">
                            <li>{{ __('Original filename: ') }}<b>{{ $document->original_filename }}</b></li>
                            <li>{{ __('Version: ') }}<b>{{ $document->version }}</b></li>
                            <li>{{ __('Filesize: ') }}<b>{{ $document->filesize }}</b></li>
                            <li>{{ __('Uploaded at: ') }}<b>{{ $document->last_modified->diffForHumans() }}</b></li>
                        </ul>

                    </td>

                    <td>
                        <div class="button-group">

                            <div x-data="modalData">
                                <button @click="openModal()" class="primary">
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

                                            <div class="margin-top-1">
                                                {{ __('Uploaded file:') }}<br>
                                                <b>{{ $document->file_link }}</b>
                                            </div>

                                            <div class="margin-bottom-1">
                                                <label for="file_path">{{ __('Change file (optional)') }}</label>
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


                                        <button type="submit" class="primary">{{ __('Save') }}</button>
                                        <button
                                            type="button"
                                            class="alt"
                                            @click="closeModal()"
                                        >
                                            {{ __('Cancel') }}
                                        </button>

                                    </form>
                                </x-admin.modal>
                            </div>

                            <div x-data="modalData">
                                <button type="button"
                                        class="danger alt"
                                        title="{{ __('Delete') }}"
                                        @click="openModal()">

                                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                </button>

                                <x-admin.modal title="{{ __('Are you sure you want to delete it') }}">

                                    <form action="{{ route('document.destroy', $document->id) }}"
                                          method="post"
                                          enctype="application/x-www-form-urlencoded"
                                          accept-charset="UTF-8"
                                          autocomplete="off"
                                    >
                                        @csrf
                                        @method('delete')

                                        <p class="fs-18"><strong>{{ $document->view_name }}</strong></p>

                                        <button type="submit" class="danger" title="{{ __('Delete') }}">{{ __('Delete') }}</button>
                                        <button type="button" class="alt" @click="closeModal()">{{ __('Cancel') }}</button>
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
