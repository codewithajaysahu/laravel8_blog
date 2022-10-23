@extends('layouts.app')

@section('title', 'Update the post')

@section('content')
    <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="post">
        @csrf
        @method('PUT')
        @include('post.partials.form')

        <div><input type="submit" value="Update"></div>
    </form>
@endsection