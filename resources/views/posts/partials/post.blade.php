 <p>   
    <h3>
        @if($post->trashed())
            <del>
        @endif
        <a class="{{ $post->trashed() ? 'text-muted' : '' }}"
            href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
        @if($post->trashed())
            </del>
        @endif
    </h3>

    {{-- <h3>    
        <a href="{{ route('posts.show', ['post' => $post->id]) }}" > {{ $post->title }}</a>
   </h3> --}}
   <p class="text-muted">
        Added {{ $post->created_at->diffForHumans() }}
        By {{ $post->user->name }}
    </p>
   @if ($post->comments_count)
    <p>{{ $post->comments_count }} Comments</p>
    @else
       <p>No comments Yes!</p>
   @endif

<div class="mb-3">
    @auth
        @can('update', $post)            
            <a href="{{ route('posts.edit', ['post' => $post->id]) }}" 
                class="btn btn-primary">
                Edit
            </a>
            @auth
                
            @endauth
        @endcan
    @endauth
    {{-- @cannot()
        <p>You can't delete this post</p>
    @endcannot --}}
    @auth           
        @if(!$post->trashed())    
            @can('delete', $post) 
                <form  class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="Submit" value="Delete!" class="btn btn-primary">
                </form>
            @endcan
        @endif
    @endauth
</div>
</p>