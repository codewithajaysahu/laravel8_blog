<h3>    <a href="{{ route('posts.show', ['post' => $post->id]) }}" > {{ $post->title }}</a>
   </h3>

   @if ($post->commets_count)
    <p>{{ $post->comments_count }} Comments</p>
    @else
       <p>No comments Yes!</p>
   @endif

<div class="mb-3">
    <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
    <form  class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="Submit" value="Delete!" class="btn btn-primary">
    </form>
</div>