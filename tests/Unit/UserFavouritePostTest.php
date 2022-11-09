<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * User Favourite Post Relationship test class
 * */
class UserFavouritePostTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test that the store requests adds a post
     * to a user's favourites.
     *
     * @return void
     */
    public function test_store_add_post_to_user_favourites()
    {
        $user = User::factory()->create();
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        Auth::login($user);

        $this->assertEquals(0, $user->favouritePosts()->count());

        $response = $this->post('/posts/' . $post->id . '/favourite');

        $this->assertEquals(1, $user->favouritePosts()->count());

    }//end test_store_add_post_to_user_favourites()


    /**
     * Test that the store requests does not add an entry
     * in the user favourite posts table if no user is authenticated
     *
     * @return void
     */
    public function test_store_does_not_add_post_to_user_favourites_if_no_user_is_authenticated()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        $this->assertDatabaseCount('favourite_post_user', 0);

        $response = $this->post('/posts/' . $post->id . '/favourite');

        $this->assertDatabaseCount('favourite_post_user', 0);

    }//end test_store_does_not_add_post_to_user_favourites_if_no_user_is_authenticated()


    /**
     * Test that the store requests increments
     * a post's favourite count.
     *
     * @return void
     */
    public function test_store_increment_post_favourite_count()
    {
        $user = User::factory()->create();
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        Auth::login($user);

        $this->assertEquals(0, $post->favourite_count);

        $response = $this->post('/posts/' . $post->id . '/favourite');

        $post->refresh();

        $this->assertEquals(1, $post->favourite_count);

    }//end test_store_increment_post_favourite_count()


    /**
     * Test that the is_favourite attribute of a post
     * returns true after the post is favourited by the current user
     *
     * @return void
     */
    public function test_store_post_is_favourites_is_true_after_user_favourites_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        Auth::login($user);

        $this->assertFalse($post->is_favourite);

        $response = $this->post('/posts/' . $post->id . '/favourite');

        $post->refresh();

        $this->assertTrue($post->is_favourite);

    }//end test_store_post_is_favourites_is_true_after_user_favourites_post()


    /**
     * Test that the store requests increments
     * a post's favourite count even if no user
     * is authenticated.
     *
     * @return void
     */
    public function test_store_increment_post_favourite_count_even_if_no_user_is_authenticated()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        $this->assertEquals(0, $post->favourite_count);

        $this->post('/posts/' . $post->id . '/favourite');

        $post->refresh();

        $this->assertEquals(1, $post->favourite_count);

    }//end test_store_increment_post_favourite_count_even_if_no_user_is_authenticated()


    /**
     * Test that the destroy request removes a post
     * from the user's favourites
     *
     * @return void
     */
    public function test_destroy_removes_post_from_user_favourites()
    {
        $user = User::factory()->create();
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        Auth::login($user);

        $this->post('/posts/' . $post->id . '/favourite');

        $this->assertEquals(1, $user->favouritePosts()->count());

        $this->delete('/posts/' . $post->id . '/favourite');

        $this->assertEquals(0, $user->favouritePosts()->count());

    }//end test_destroy_removes_post_from_user_favourites()


    /**
     * Test that the destroy requests decrements
     * a post's favourite count.
     *
     * @return void
     */
    public function test_destroy_decrements_post_favourite_count()
    {
        $user = User::factory()->create();
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        Auth::login($user);

        $this->post('/posts/' . $post->id . '/favourite');

        $post->refresh();

        $this->assertEquals(1, $post->favourite_count);

        $this->delete('/posts/' . $post->id . '/favourite');

        $post->refresh();

        $this->assertEquals(0, $post->favourite_count);

    }//end test_destroy_decrements_post_favourite_count()


    /**
     * Test that the is_favourite attribute of a post
     * returns false after the post is unfavourited by the current user
     *
     * @return void
     */
    public function test_destroy_post_is_favourites_is_false_after_user_unfavourites_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        Auth::login($user);

        $this->post('/posts/' . $post->id . '/favourite');

        $post->refresh();

        $this->assertTrue($post->is_favourite);

        $this->delete('/posts/' . $post->id . '/favourite');

        $post->refresh();

        $this->assertFalse($post->is_favourite);

    }//end test_store_post_is_favourites_is_false_after_user_unfavourites_post()


    /**
     * Test that the destroy requests decrements
     * a post's favourite count even if no user
     * is authenticated.
     *
     * @return void
     */
    public function test_destroy_decrements_post_favourite_count_even_if_no_user_is_authenticated()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        $this->post('/posts/' . $post->id . '/favourite');

        $post->refresh();

        $this->assertEquals(1, $post->favourite_count);

        $this->delete('/posts/' . $post->id . '/favourite');

        $post->refresh();

        $this->assertEquals(0, $post->favourite_count);

    }//end test_destroy_decrements_post_favourite_count_even_if_no_user_is_authenticated()


    /**
     * Test that the store requests adds the post id
     * to the favourites session key if no user
     * is authenticated.
     *
     * @return void
     */
    public function test_store_adds_post_id_to_favourites_session_key_if_no_user_is_authenticated()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        $this->assertEmpty(session('favourites'));

        $this->post('/posts/' . $post->id . '/favourite');

        $this->assertCount(1, session('favourites'));
        $this->assertEquals($post->id, session('favourites')[0]);

    }//end test_store_adds_post_id_to_favourites_session_key_if_no_user_is_authenticated()


    /**
     * Test that the destroy requests removes the post id
     * from the favourites session key if no user
     * is authenticated.
     *
     * @return void
     */
    public function test_destroy_removes_post_id_from_favourites_session_key_if_no_user_is_authenticated()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        $this->post('/posts/' . $post->id . '/favourite');

        $this->assertEquals($post->id, session('favourites')[0]);

        $this->delete('/posts/' . $post->id . '/favourite');

        $this->assertEmpty(session('favourites'));

    }//end test_destroy_adds_post_id_from_favourites_session_key_if_no_user_is_authenticated()


    /**
     * Test that the destroy requests removes only the post id
     * from the favourites session key if no user
     * is authenticated, not the whole session key.
     *
     * @return void
     */
    public function test_destroy_removes_only_post_id_from_favourites_session_key()
    {
        $posts = Post::factory()
            ->count(2)
            ->forUser(['name' => 'Other User'])
            ->create();

        $this->post('/posts/' . $posts[0]->id . '/favourite');
        $this->post('/posts/' . $posts[1]->id . '/favourite');

        $this->assertEquals($posts[0]->id, session('favourites')[0]);
        $this->assertEquals($posts[1]->id, session('favourites')[1]);

        $this->delete('/posts/' . $posts[0]->id . '/favourite');

        $this->assertNotEmpty(session('favourites'));
        $this->assertEquals($posts[1]->id, session('favourites')[0]);

    }//end test_destroy_removes_only_post_id_from_favourites_session_key()


}//end class
