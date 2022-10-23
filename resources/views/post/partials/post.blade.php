@if ($loop->even)
<div>{{ $key }}.{{ $post->title }}</div>
@else
<div style="background-color: silver;">
    <div>{{ $key }}.{{ $post->title}}</div>
</div>
@endif

<div>
    <form action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="Submit" value="Delete!">
    </form>
</div>