<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted;
use App\Http\Requests\StoreComment;
use App\Mail\CommentPostedMarkdown;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function store(BlogPost $post, StoreComment $request)
    {        
        // Comment::create()
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        event(new CommentPosted($comment));

        //$when = now()->addMinutes(1);

        //dd($post->user);
        // Mail::to($post->user)->send(
        //     new CommentPostedMarkdown($comment)
        // );

        // Mail::to($post->user)->queue(
        //     new CommentPostedMarkdown($comment)
        // );       

        // Mail::to($post->user)->later(
        //     $when,
        //     new CommentPostedMarkdown($comment)
        // );

        return redirect()->back()
            ->withStatus('Comment was created!');
    }
}