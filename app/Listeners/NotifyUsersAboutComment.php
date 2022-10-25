<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottleMail;
use App\Mail\CommentPostedMarkdown;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUsersAboutComment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(CommentPosted $event)
    {
        // dd('I was called in response to an event');
        ThrottleMail::dispatch(
            new CommentPostedMarkdown($event->comment), 
            $event->comment->commentable->user
        )->onQueue('low');
        NotifyUsersPostWasCommented::dispatch($event->comment)
            ->onQueue('high');
    }
}
