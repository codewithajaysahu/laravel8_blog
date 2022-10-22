@extends('layouts.app')

@section('title', $post['title'])

@if ($post['is_new'])
    <div>A new Blog post ! Using if</div>

    @elseif (!$post['is_new'])
    <div>Blog post is old</div>
@endif

@unless ($post['is_new'])
<div>Blog post is old using unless</div>

@endunless

@section('content')
<h1>{{ $post['title'] }}</h1>
<p>{{ $post['content'] }}</p>
@endsection