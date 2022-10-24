<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogPostCount = max((int)$this->command->ask('How many Blog Posts would you like?', 50), 1);
        //$blogPostCount = (int)$this->command->ask('How many Blog Posts would you like?', 50);
        $users = User::all();
        BlogPost::factory()->count($blogPostCount)->make()->each(function($post) use ($users){
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
