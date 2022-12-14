<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use SoftDeletes, Taggable, HasFactory;

    protected $fillable = ['user_id', 'content'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function (Comment $comment) {
    //         // dump($comment);
    //         // dd(BlogPost::class);
    //         if ($comment->commentable_type === BlogPost::class) {
    //             Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
    //             Cache::tags(['blog-post'])->forget('mostCommented');
    //         }
    //     });

    //     // static::addGlobalScope(new LatestScope);
    // }
}