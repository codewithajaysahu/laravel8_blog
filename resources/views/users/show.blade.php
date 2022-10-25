@extends('layout')

@section('content')
    <div class="row">
        <div class="col-4">
            <img src="{{ $user->image ? $user->image->url() : '' }}"
                class="img-thumbnail avatar" />
        </div>
        <div class="col-8">
            <h3>{{ $user->name }}</h3>            

            {{-- @commentForm(['route' => route('users.comments.store', ['user' => $user->id])])
            @endcommentForm

            @commentList(['comments' => $user->commentsOn])
            @endcommentList --}}

            @component('components.comment-form', ['route' => route('users.comments.store', ['user' => $user->id])])
            @endcomponent

            @component('components.comment-list',['comments' => $user->commentsOn])
            @endcomponent
        </div>
    </div>
@endsection