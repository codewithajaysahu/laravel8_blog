<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('home.index');
// })->name('home.index');

// Route::get('/contact', function () {
//     return view('home.contact');
// })->name('home.contact');

Route::get('/',  [HomeController::class, 'home'])
->name('home')
//->middleware('auth')
;

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/secret', [HomeController::class, 'secret'])
->name('secret')
->middleware('can:home.secret');

Route::resource('posts', PostController::class);

Route::get('/posts/tag/{tag}', [PostTagController::class, 'index'])->name('posts.tags.index');

Route::resource('posts.comments', PostCommentController::class)->only(['store']);
Route::resource('users.comments',UserCommentController::class)->only(['store']);
Route::resource('users', UserController::class)->only(['show', 'edit', 'update']);

Route::get('/mailable', function () {
    $comment = App\Models\Comment::find(1);
    return new App\Mail\CommentPostedMarkdown($comment);
});

Auth::routes();
// Route::get('posts', function() use($posts){
//    //dd(request()->all());
//    dd(request()->input('page'));
//    dd(request()->query('page'));
//     return view('post.index',  ['posts' =>$posts]);
// });

// Route::get('/post/{id}', function ($id)  use($posts){

//     abort_if(!isset($posts[$id]), 404);
//     return view('post.show', ['post' => $posts[$id]]);
// })
// // ->where(['id' => '[0-9]+'])
// ->name('post.show');

// Route::get('/recent-post/{days_ago?}', function ($days_ago = 20) {
//     return "Post from $days_ago  days ago";
// })->name('post.recent.index')->middleware('auth');


// Route::prefix('fun')->name('.fun')->group(function() use($posts){

//     Route::get('/back', function(){
//         return back();
//     })->name('back');
    
//     Route::get('/responses', function() use($posts){    
//         return response($posts, 200)
//         ->header('Content Type',  'application/json')
//         ->cookie('My_Cookie', 'ajay', 3600);
//     })->name('responses');
    
//     Route::get('/named-route', function(){
//         return redirect()->route('post.show', ['id' => 1]);
//     })->name('named-route');
    
//     Route::get('/away', function(){
//         return redirect()->away('https://google.com');
//     })->name('away');
    
//     Route::get('/json', function() use($posts){
//         return response()->json($posts);
//     })->name('json');
    
//     Route::get('/download', function() use($posts){
//         return response()->download(public_path('/daniel.jpg'), 'face.jpg');
//     })->name('download');
// });

