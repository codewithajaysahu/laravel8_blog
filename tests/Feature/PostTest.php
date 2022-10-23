<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

    public function testSee1BlogPostWhenThereIs1(){
            //Arrange
            $post = $this->createDummyBlogPost();

            //Act

            $response = $this->get('/posts');

            //Assert

            $response->assertSeeText('This is blog title');

            $this->assertDatabaseHas('blog_posts', [
                'title' => 'This is blog title'
            ]);
    }

    public  function testStoreValid(){
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 character'
        ];

        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

            $this->assertEquals(session('status'), 'The blog post was created.');
    }

    public function testStoreFail(){
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];

        $this->post('/posts', $params)
        ->assertStatus(302)
        ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();
        
        $this->assertEquals($messages['title'][0] , 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0] , 'The content must be at least 10 characters.');
    }

    public function testUpdateValid() {
           //Arrange
            $post = $this->createDummyBlogPost();
            
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

        $this->put("/posts/{$post->id}", $params)
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
         $post = $this->createDummyBlogPost();

         $this->assertDatabaseHas('blog_posts', [
            'title' => 'This is blog title',
            'content' => 'This is blog content',
            'id' => $post->id
           ]);

         $this->delete("/posts/{$post->id}")
         ->assertStatus(302)
         ->assertSessionHas('status');

         $this->assertEquals(session('status'), 'Blog post has been deleted!');

         $this->assertDatabaseMissing('blog_posts', [
            'title' => 'This is blog title',
            'content' => 'This is blog content',
            'id' => $post->id
           ]);

    }

    private function createDummyBlogPost(): BlogPost {
        $post = new BlogPost();
         $post->title = 'This is blog title';
         $post->content = 'This is blog content';
         $post->save();

         return $post;
    }
}
