<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Comment;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNoBlogPostWhenNothingInDatabase()
    {
        $response = $this->get('/posts');
        $response->assertSeeText('No data found');
        //$this->assertTrue(true);
    }

    public function testSee1BlogPostWhenThereIs1WithNComments(){
            //Arrange
            $post = $this->createDummyBlogPost();
            //dd($post);
            //Act

            $response = $this->get('/posts');

            //Assert

            $response->assertSeeText('This is blog title');
            $response->assertSeeText('No comments Yes!');

            $this->assertDatabaseHas('blog_posts', [
                'title' => 'This is blog title'
            ]);
    }

    public function testSee1BlogPostwithComments(){
         //Arrange
         $post = $this->createDummyBlogPost();
         Comment::factory()->count(3)->create([
            'blog_post_id' => $post->id
        ]);
          //Act
            $response = $this->get('/posts');
           // dd($response);
            $this->assertDatabaseCount('comments', 3);
            $response->assertSeeText('3 Comments', $escaped = true);
            //$response->assertSeeText('3 Comments', $escaped = true);
    }

    public  function testStoreValid(){
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 character'
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

            $this->assertEquals(session('status'), 'The blog post was created.');
    }

    public function testStoreFail(){
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();
        
        $this->assertEquals($messages['title'][0] , 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0] , 'The content must be at least 10 characters.');
    }

    public function testUpdateValid() {
           //Arrange
           $user = $this->user();
            $post = $this->createDummyBlogPost($user->id);
            
           //$this->assertDatabaseHas('blog_posts', $post->toArray());
           $this->assertDatabaseHas('blog_posts', [
            'title' => 'This is blog title',
            'content' => 'This is blog content',
            'id' => $post->id
           ]);


           $params = [
                'title' => 'A new named title',
                'content' => 'Content was changed'
            ];

        $this->actingAs($user)
            ->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was updated');

        $this->assertDatabaseMissing('blog_posts', [
            'title' => 'This is blog title',
            'content' => 'This is blog content',
            'id' => $post->id
           ]);

           
           $this->assertDatabaseHas('blog_posts', [
            'title' => 'A new named title',
            'content' => 'Content was changed',
            'id' => $post->id
           ]);
    }

    public function testDelete() {
         //Arrange
         $user = $this->user();        
         $post = $this->createDummyBlogPost($user->id);

         $this->assertDatabaseHas('blog_posts', [
            'title' => 'This is blog title',
            'content' => 'This is blog content',
            'id' => $post->id
           ]);

        $this->actingAs($user)
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

         $this->assertEquals(session('status'), 'Blog post has been deleted!');

        //  $this->assertDatabaseMissing('blog_posts', [
        //     'title' => 'This is blog title',
        //     'content' => 'This is blog content',
        //     'id' => $post->id
        //    ]);

          // $this->assertSoftDeleted('blog_posts', $post->toArray());
              $this->assertSoftDeleted('blog_posts', [
               'title' => 'This is blog title',
               'content' => 'This is blog content',
               'id' => $post->id
              ]);

    }

    private function createDummyBlogPost($userId = null) :BlogPost {
        // $post = new BlogPost();
        //  $post->title = 'This is blog title';
        //  $post->content = 'This is blog content';
        //  $post->user_id = $userId ?? $this->user()->id;
        //  $post->save();
        
        // return $post;
        return BlogPost::factory()
            ->suspended()
            ->create([
                'user_id' => $userId ?? $this->user()->id
            ]);       
        
    }
}
