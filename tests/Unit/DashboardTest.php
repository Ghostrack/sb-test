<?php

namespace Tests\Unit;

use Inertia\Testing\AssertableInertia as Assert;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * User Dashboard test class.
 * */
class DashboardTest extends TestCase
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
     * Test that the response contains the currently
     * authenticated user object.
     *
     * @return void
     */
    public function test_response_contains_current_user_model()
    {
        $authUser = Auth::user();
        $this->get('/dashboard')
            ->assertInertia(function (Assert $page) use ($authUser) {
                $page->has('user', function (Assert $user) use ($authUser) {
                    $user->where('id', $authUser->id)
                        ->etc();
                });
            });

    }//end test_response_contains_current_user_model()


    /**
     * Test that the response contains all existing
     * post models.
     *
     * @return void
     */
    public function test_response_contains_current_posts()
    {
        $posts = Post::factory()
            ->count(3)
            ->forUser(['name' => 'Test User'])
            ->create();

        $this->get('/dashboard')
            ->assertInertia(function (Assert $page) use ($posts) {
                $page->has('posts', count($posts))
                    ->etc();
            });

    }//end test_response_contains_current_posts()


    /**
     * Test that the response contains the post user model
     *
     * @return void
     */
    public function test_response_contains_post_user_model()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Test User'])
            ->create();

        $this->get('/dashboard')
            ->assertInertia(function (Assert $page) use ($post) {
                $page->has('posts.0.user', function ($page) use ($post) {
                    $page->where('name', 'Test User')
                        ->etc();
                });
            });

    }//end test_response_contains_post_user_model()


    /**
     * Test that the response returns the list of posts
     * sorted by latest first.
     *
     * @return void
     */
    public function test_response_posts_are_sorted_by_latest_first()
    {
        $firstPost = Post::factory()
            ->forUser(['name' => 'User One'])
            ->create();

        $this->travel(2)->hours();

        $secondPost = Post::factory()
            ->forUser(['name' => 'User Two'])
            ->create();

        $this->get('/dashboard')
            ->assertInertia(function (Assert $page) use ($firstPost, $secondPost) {
                $page->has('posts.0', function ($page) use ($secondPost) {
                    $page->where('id', $secondPost->id)
                        ->etc();
                })
                ->has('posts.1', function ($page) use ($firstPost) {
                    $page->where('id', $firstPost->id)
                        ->etc();
                });
            });

    }//end test_response_posts_are_sorted_by_latest_first()


    /**
     * Test that the response does not contain hidden posts
     *
     * @return void
     */
    public function test_response_does_not_contain_hidden_posts()
    {
        $visiblePost = Post::factory(['is_hidden' => FALSE])
            ->forUser(['name' => 'Test User'])
            ->create();

        $hiddenPost = Post::factory(['is_hidden' => TRUE])
            ->forUser(['name' => 'Test User'])
            ->create();

        $this->get('/dashboard')
            ->assertInertia(function (Assert $page) use ($hiddenPost, $visiblePost) {
                $page->has('posts', 1)
                    ->has('posts.0', function ($page) use ($visiblePost) {
                        $page->where('id', $visiblePost->id)
                            ->etc();
                    });
            });

    }//end test_response_does_not_contain_hidden_posts()


    /**
     * Test that the response contains hidden posts
     * if the user is the author
     *
     * @return void
     */
    public function test_response_contains_hidden_posts_if_user_is_author()
    {
        $visiblePost = Post::factory(['is_hidden' => FALSE])
            ->forUser(['name' => 'Test User'])
            ->create();

        $this->travel(5)->minutes();

        $hiddenPost = Post::factory([
            'is_hidden' => TRUE,
            'user_id'   => $this->user->id,
        ])->create();

        $this->get('/dashboard')
            ->assertInertia(function (Assert $page) use ($hiddenPost, $visiblePost) {
                $page->has('posts', 2)
                    ->has('posts.0', function ($page) use ($hiddenPost) {
                        $page->where('id', $hiddenPost->id)
                            ->etc();
                    })
                    ->has('posts.1', function ($page) use ($visiblePost) {
                        $page->where('id', $visiblePost->id)
                            ->etc();
                    });
            });

    }//end test_response_contains_hidden_posts_if_user_is_author()


    /**
     * Test that the response contains also hidden posts
     * if the user is an admin
     *
     * @return void
     */
    public function test_response_contains_also_hidden_posts_if_user_is_admin()
    {
        $visiblePost = Post::factory(['is_hidden' => FALSE])
            ->forUser(['name' => 'Test User'])
            ->create();

        $this->travel(5)->minutes();

        $hiddenPost = Post::factory(['is_hidden' => TRUE])
            ->forUser(['name' => 'Test User'])
            ->create();

        $user = User::factory(['role_id' => Role::ADMIN])->create();

        Auth::login($user);

        $this->get('/dashboard')
            ->assertInertia(function (Assert $page) use ($hiddenPost, $visiblePost) {
                $page->has('posts', 2)
                    ->has('posts.0', function ($page) use ($hiddenPost) {
                        $page->where('id', $hiddenPost->id)
                            ->etc();
                    })
                    ->has('posts.1', function ($page) use ($visiblePost) {
                        $page->where('id', $visiblePost->id)
                            ->etc();
                    });
            });

    }//end test_response_contains_also_hidden_posts_if_user_is_admin()


}//end class
