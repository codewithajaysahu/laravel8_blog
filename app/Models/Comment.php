<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    //blog_post_id
    public function blogPost(){
        //return $this->belongsTo('App\Models\BlogPost', 'post_d', 'blog_post_id');
        return $this->belongsTo('App\Models\BlogPost');
    }

    public function scopeLatest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot(){
        parent::boot();

       // static::addGlobalScope(new LatestScope);        
    }
}
