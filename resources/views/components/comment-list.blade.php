
@forelse($comments as $comment)
    <p>
        {{ $comment->content }}
    </p>
    @component('components.tags',['tags' => $comment->tags])@endcomponent
    @component('components.updated', ['date' => $comment->created_at, 'name' => $comment->user->name, 'userId' => $comment->user->id])
    @endcomponent
@empty
    <p>No comments yet!</p>
@endforelse