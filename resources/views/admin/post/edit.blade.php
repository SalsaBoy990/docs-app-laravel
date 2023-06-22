<x-admin-nosidebar-layout>

    @section('head')
        <x-admin.head.tinymce-config/>
    @endsection


@section('content')
        <main class="padding-1" style="max-width: 900px; margin: 0 auto">
            <a href="{{ route('category.selected', $categoryId) }}" class="button alt bg-white">{{ __('Back') }}</a>

            <h1 class="margin-0 h2">
                {{ $post->title }}
            </h1>

            <form action="{{ route('post.update', [$post->id, $categoryId])}}"
                  method="POST"
                  enctype="application/x-www-form-urlencoded"
                  accept-charset="UTF-8"
                  autocomplete="off"
            >
                @method("PUT")
                @csrf

                <x-admin.validation-errors/>

                <div class="mb-5">
                    <label for="title">{{ __('Post title') }}</label>
                    <input id="title"
                           class="{{ $errors->has('title') ? ' border border-red' : '' }}"
                           type="text"
                           name="title"
                           value="{{ old('title') ?? $post->title }}"
                           autofocus
                    />
                    <x-admin.input-error for="title"/>
                </div>

                <div class="mb-5">
                    <label for="slug">{{ __('Post slug') }}</label>
                    <input id="slug"
                           class="{{ $errors->has('slug') ? ' border border-red' : '' }}"
                           type="text"
                           name="slug"
                           value="{{ old('slug') ?? $post->slug }}"
                           autofocus
                    />
                    <x-admin.input-error for="slug"/>
                </div>

                <div class="mb-5">
                    <label for="content">{{ __('Body') }}</label>
                    <div wire:ignore>
                    <textarea wire:model="content" name="content" rows="5" id="update-content-editor"
                              class="{{ $errors->has('content') ? 'border border-red' : '' }}"
                    >
                        {!! $post->content !!}
                    </textarea>
                    </div>
                    <div
                        class="fs-14 {{ $errors->has('content') ? 'red' : 'red' }}">
                        {{ $errors->has('content') ? $errors->first('content') : '' }}
                    </div>
                </div>

                <div>
                    <button type="submit" class="primary">{{ __("Update") }}
                    </button>

                    <a href="{{ route('dashboard')}}"
                       class="button alt bg-white">{{ __('Cancel') }}</a>
                </div>
            </form>
        </main>

    @endsection

</x-admin-nosidebar-layout>
