@extends('layouts.public')

@section('content')


        <h1 class="text-center margin-0">Blog</h1>

        <table>
            <thead>
            <tr>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Slug') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Body') }}</th>
                <th>{{ __('Categories') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>
                        <a href="{{ route('post.frontend.show', $post->slug) }}">{{ $post->title }}</a>
                    </td>
                    <td>
                        {{ $post->slug }}
                    </td>
                    <td>
                        {{ $post->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td>
                        {!! $post->content !!}
                    </td>
                    <td>
                        @foreach($post->categories as $category)
                        <a href="{{ route('post.frontend.category', $category->name) }}" class="badge gray-60 text-gray-10">{{ $category->name }}</a>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $posts->links('components.global.pagination') }}


@endsection
