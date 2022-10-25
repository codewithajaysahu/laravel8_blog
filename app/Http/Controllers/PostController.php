<?php

namespace App\Http\Controllers;

use App\Events\BlogPostPosted;
use App\Models\BlogPost;
use App\Http\Requests\StorePost;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\User;
Use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

// use Illuminate\Support\Facades\DB;

// [
//     'show' => 'view',
//     'create' => 'create',
//     'store' => 'create',
//     'edit' => 'update',
//     'update' => 'update',
//     'destroy' => 'delete',
// ]

class PostController extends Controller
{

    public function __construct()
    {        
        $this->middleware('auth')
        ->only('create', 'update', 'store', 'edit', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
        // DB::connection()->enableQueryLog();
        // $posts = BlogPost::with('comments')->get();
        // foreach($posts as $post){
        //     foreach($post->comments as $comment){
        //         echo $comment->content;
        //     }
        // }
        // dd(DB::getQueryLog());

        $mostCommented = Cache::tags(['blog-posts'])->remember('blog-post-commented', 60, function() {
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActive = Cache::remember('users-most-active', 60, function() {
            return User::withMostBlogPosts()->take(5)->get();
        });

        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', 60, function() {
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });
        
        
        return view(
            'posts.index',
            [
                'posts' => BlogPost::latest()->withCount('comments')->with('user')->get(),
                'mostCommented' => $mostCommented,
                'mostActive' => $mostActive,
                'mostActiveLastMonth' => $mostActiveLastMonth,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $blogPost = BlogPost::create($validatedData);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails');
            $blogPost->image()->save(
                Image::make(['path' => $path])
            );
        }

        event(new BlogPostPosted($blogPost));

        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        //abort_if(!isset($this->posts[$id]), 404);
        
        // return view('posts.show', [
        //     'post' => BlogPost::with(['comments' => function ($query) {
        //         return $query->latest();
        //     }])->findOrFail($id),
        // ]);
        $blogPost = Cache::tags(['blog-posts'])->remember('blog-post-{$id}', 60, function() use($id) {
            return BlogPost::with('comments')->findOrFail($id);
        });
        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        $users = Cache::tags(['blog-posts'])->get($usersKey, []);
        $usersUpdate = [];
        $diffrence = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $diffrence--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if(
            !array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $diffrence++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-posts'])->forever($usersKey, $usersUpdate);

        if (!Cache::tags(['blog-posts'])->has($counterKey)) {
            Cache::tags(['blog-posts'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-posts'])->increment($counterKey, $diffrence);
        }
        
        $counter = Cache::tags(['blog-posts'])->get($counterKey);

        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {       
        $post = BlogPost::findOrFail($id);

        // if(Gate::denies('update-post', $post)){
        //     abort(403, "You can't edit this blog post!");
        // }
        $this->authorize($post);

        return view('posts.edit', ['post' => BlogPost::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // if(Gate::denies('update-post', $post)){
        //     abort(403, "You can't edit this blog post!");
        // }
        $this->authorize($post);

        $validated = $request->validated();
        $post->fill($validated);
        $post->save();
        $request->session()->flash('status', 'Blog post was updated');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        // if(Gate::denies('delete-post', $post)){
        //     abort(403, "You can't delete this blog post!");
        // } 
        $this->authorize($post);
        $post->delete();

        session()->flash('status', 'Blog post has been deleted!');
        return redirect()->route('posts.index');
    }
}
