@extends('layouts.public')

@section('content')

    <h1 class="text-center margin-0">{{ $post->title }}</h1>
    <div class="bar">
        <small>{{ $post->created_at->format('Y-m-d H:i') }}</small>
        @foreach($post->categories as $category)
            <a href="{{ route('post.frontend.category', $category->name) }}" class="badge gray-60 text-gray-10">{{ $category->name }}</a>
        @endforeach
    </div>

    <hr>

    <div>{!! $post->content !!}</div>

@endsection
