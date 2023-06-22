@extends('layouts.public')

@section('content')

    <h1 class="text-center margin-0">{{ $category->name }}</h1>

    <hr>

    @foreach($posts as $post)
        <article class="card">
            <h2>{{ $post->title }}</h2>
            <p>{{ $post->created_at->format('Y-m-d H:i') }}</p>
            <a href="{{ route('post.frontend.show', $post->slug) }}" class="button primary alt">{{ __('Read more') }}</a>
        </article>
    @endforeach

    {{ $posts->links('components.global.pagination') }}

@endsection
