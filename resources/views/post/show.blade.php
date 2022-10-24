@extends('layouts.app')

@section('title', $post->title)

@section('content')
<h1>
    {{ $post->title }}
    @component('components.badge', ['show' => now()->diffInMinutes($post->created_at) < 30])
        Brand new Post!
    @endcomponent   
</h1>

<p>{{ $post->content }}</p>
<p>Added {{ $post->created_at->diffForHumans() }}</p>

@if(now()->diffInMinutes($post->created_at) < 5)
    
@endif

<h4>Comments</h4>
@forelse ($post->comments as $comment)
    <p class="text-muted">{{ $comment->content }}, </p>
    <p>added {{ $comment->created_at->diffForHumans() }}</p>
@empty
    <p>No comments yet!</p>
@endforelse
@endsection