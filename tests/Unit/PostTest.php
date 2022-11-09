<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Base Post Model test class
 * */
class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Main User for the Tests
     *
     * @var User
     */
    protected User $user;


    /**
     * Authenticate the user before every test
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Auth::login($this->user);

    }//end setUp()


    /**
     * Test that the Create Post view is not accessible
     * if there is no authenticated user.
     *
     * @return void
     */
    public function test_create_post_screen_cant_be_accessed_if_no_user_is_authenticated()
    {
        Auth::logout();

        $response = $this->get('/posts/create');

        $response->assertStatus(302);

        $response->assertRedirect('/login');

    }//end test_create_post_screen_cant_be_accessed_if_no_user_is_authenticated()


    /**
     * Test that the Create Post view is rendered.
     *
     * @return void
     */
    public function test_create_post_screen_can_be_rendered()
    {
        $response = $this->get('/posts/create');

        $response->assertStatus(200);

    }//end test_create_post_screen_can_be_rendered()


    /**
     * Test that the store post request creates
     * a new Post Model.
     *
     * @return void
     */
    public function test_store_post_request_creates_new_post_model()
    {
        $this->assertEquals(0, Post::count());

        $response = $this->post('/posts', [
            'title'   => fake()->realText(50),
            'content' => fake()->realText(200),
        ]);

        $this->assertEquals(1, Post::count());

    }//end test_store_post_request_creates_new_post_model()


    /**
     * Test that the store post request creates
     * a new Post Model related to the authenticated User.
     *
     * @return void
     */
    public function test_store_post_request_creates_new_post_model_for_current_user()
    {
        $this->assertEquals(0, $this->user->posts()->count());

        $response = $this->post('/posts', [
            'title'   => fake()->realText(50),
            'content' => fake()->realText(200),
        ]);

        $this->assertEquals(1, $this->user->posts()->count());

    }//end test_store_post_request_creates_new_post_model_for_current_user()


    /**
     * Test that the store post request is rejected
     * if no title is passed.
     *
     * @return void
     */
    public function test_store_post_request_is_rejected_if_title_is_empty()
    {
        $response = $this->post('/posts', [
            'title'   => NULL,
            'content' => fake()->realText(200),
        ]);

        $response->assertSessionHasErrors(['title']);

    }//end test_store_post_request_is_rejected_if_title_is_empty()


    /**
     * Test that the request is rejected if no post content is passed
     *
     * @return void
     */
    public function test_store_post_request_is_rejected_if_content_is_empty()
    {
        $response = $this->post('/posts', [
            'title'   => fake()->realText(50),
            'content' => NULL,
        ]);

        $response->assertSessionHasErrors(['content']);

    }//end test_store_post_request_is_rejected_if_content_is_empty()


    /**
     * Test that the store post request is rejected
     * if the passed title is shorter than 5 characters
     *
     * @return void
     */
    public function test_store_post_request_is_rejected_if_title_is_shorter_than_5_chars()
    {
        $response = $this->post('/posts', [
            'title'   => '1234',
            'content' => fake()->realText(200),
        ]);

        $response->assertSessionHasErrors(['title']);

    }//end test_store_post_request_is_rejected_if_title_is_shorter_than_5_chars()


    /**
     * Test that the store post request is rejected
     * if the passed title is longer than 255 chars.
     *
     * @return void
     */
    public function test_store_post_request_is_rejected_if_title_is_longer_than_255_chars()
    {
        $response = $this->post('/posts', [
            'title'   => fake()->realTextBetween(256, 300),
            'content' => fake()->text(200),
        ]);

        $response->assertSessionHasErrors(['title']);

    }//end test_store_post_request_is_rejected_if_title_is_longer_than_255_chars()


    /**
     * Test that the store post request is rejected
     * if the passed content is shorter than 5 characters
     *
     * @return void
     */
    public function test_store_post_request_is_rejected_if_content_is_shorter_than_5_chars()
    {
        $response = $this->post('/posts', [
            'title'   => fake()->realText(50),
            'content' => '1234',
        ]);

        $response->assertSessionHasErrors(['content']);

    }//end test_store_post_request_is_rejected_if_content_is_shorter_than_5_chars()


    /**
     * Test that the store post request is rejected
     * if the passed title is not a string.
     *
     * @return void
     */
    public function test_store_post_request_is_rejected_if_title_is_not_a_string()
    {
        $response = $this->post('/posts', [
            'title'   => 123456,
            'content' => fake()->realText(200),
        ]);

        $response->assertSessionHasErrors(['title']);

    }//end test_store_post_request_is_rejected_if_title_is_not_a_string()


    /**
     * Test that the store post request is rejected
     * if the passed content is not a string.
     *
     * @return void
     */
    public function test_store_post_request_is_rejected_if_content_is_not_a_string()
    {
        $response = $this->post('/posts', [
            'title'   => fake()->realText(50),
            'content' => 123456,
        ]);

        $response->assertSessionHasErrors(['content']);

    }//end test_store_post_request_is_rejected_if_content_is_not_a_string()


    /**
     * Test that a successfull store post request
     * redirects back to the dashboard.
     *
     * @return void
     */
    public function test_store_post_request_success_redirects_to_dashboard()
    {
        $response = $this->post('/posts', [
            'title'   => fake()->realText(50),
            'content' => fake()->realText(200),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard');

    }//end test_store_post_request_success_redirects_to_dashboard()


    /**
     * Test that destroy post request deletes a post
     * if the currently authenticated user is the post author.
     *
     * @return void
     */
    public function test_destroy_post_request_deletes_post_if_user_is_author()
    {
        $this->assertEquals(0, Post::count());

        $post = Post::factory(['user_id' => $this->user->id])->create();

        $this->assertEquals(1, Post::count());

        $response = $this->delete('/posts/' . $post->id);

        $this->assertEquals(0, Post::count());

    }//end test_destroy_post_request_deletes_post_if_user_is_author()


    /**
     * Test that destroy post request is rejected
     * if the currently authenticated user is not the post author.
     *
     * @return void
     */
    public function test_destroy_post_request_is_rejected_if_user_is_not_author()
    {
        $this->assertEquals(0, Post::count());

        $post = Post::factory()
            ->forUser(['name' => 'Not the auth user'])
            ->create();

        $this->assertEquals(1, Post::count());

        $response = $this->delete('/posts/' . $post->id);

        $response->assertStatus(403);
        $this->assertEquals(1, Post::count());

    }//end test_destroy_post_request_is_rejected_if_user_is_not_author()


    /**
     * Test that update post request is rejected
     * if the currently authenticated user is not the post author.
     *
     * @return void
     */
    public function test_update_post_request_is_rejected_if_user_is_not_author()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Not the auth user'])
            ->create();

        $response = $this->put('/posts/' . $post->id, [
            'title'   => 'New Title',
            'content' => 'New Content',
        ]);

        $response->assertStatus(403);

        $post->refresh();
        $this->assertNotEquals('New Title', $post->title);
        $this->assertNotEquals('New Content', $post->content);

    }//end test_update_post_request_is_rejected_if_user_is_not_author()


    /**
     * Test that update post request updates the post model
     * if the currently authenticated user is the post author.
     *
     * @return void
     */
    public function test_update_post_request_updates_post_if_user_is_author()
    {
        $post = Post::factory(['user_id' => $this->user->id])->create();

        $response = $this->put('/posts/' . $post->id, [
            'title'   => 'New Title',
            'content' => 'New Content',
        ]);

        $response->assertSessionHasNoErrors();

        $post->refresh();
        $this->assertEquals('New Title', $post->title);
        $this->assertEquals('New Content', $post->content);

    }//end test_update_post_request_updates_post_if_user_is_author()


    /**
     * Test that the Edit Post view is not accessible
     * if there is no authenticated user.
     *
     * @return void
     */
    public function test_edit_post_screen_cant_be_accessed_if_no_user_is_authenticated()
    {
        Auth::logout();

        $post = Post::factory()
            ->forUser(['name' => 'Test User'])
            ->create();

        $response = $this->get('/posts/' . $post->id . '/edit');

        $response->assertStatus(302);

        $response->assertRedirect('/login');

    }//end test_edit_post_screen_cant_be_accessed_if_no_user_is_authenticated()


    /**
     * Test that the Edit Post request returns 404 error
     * if a post with the passed id is not found
     *
     * @return void
     */
    public function test_edit_post_returns_not_found_if_post_does_not_exist()
    {
        $post = Post::factory(['user_id' => $this->user->id])->create();

        $response = $this->get('/posts/999/edit');

        $response->assertStatus(404);

    }//end test_edit_post_returns_not_found_if_post_does_not_exist()


    /**
     * Test that the Edit Post request returns unauthorised error
     * if the authenticated user is not the post author
     *
     * @return void
     */
    public function test_edit_post_returns_unauthorised_if_user_is_not_author()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Not auth user'])
            ->create();

        $response = $this->get('/posts/' . $post->id . '/edit');

        $response->assertStatus(403);

    }//end test_edit_post_returns_unauthorised_if_user_is_not_author()


    /**
     * Test that the Edit Post view is rendered.
     *
     * @return void
     */
    public function test_edit_post_screen_can_be_rendered()
    {
        $post = Post::factory(['user_id' => $this->user->id])->create();

        $response = $this->get('/posts/' . $post->id . '/edit');

        $response->assertStatus(200);

    }//end test_edit_post_screen_can_be_rendered()


}//end class
