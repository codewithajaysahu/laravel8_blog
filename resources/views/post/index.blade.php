@extends('layouts.app')

@section('title', 'Blog post')


@section('content')
    {{-- @foreach ($posts as $post )
        <div>{{ $post['title'] }}</div>
    @endforeach --}}

    @forelse ( $posts as $key => $post)
        @include('post.partials.post', [])
        @empty
    <div>No data found</div>
    @endforelse
    
@endsection