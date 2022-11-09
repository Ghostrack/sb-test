<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Post Visibility Test Class
 * */
class PostVisibilityTest extends TestCase
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

        $this->user = User::factory(['role_id' => Role::ADMIN])->create();

        Auth::login($this->user);

    }//end setUp()


    /**
     * Test that the update requests updates
     * the value of the is_hidden attribute
     * of the passed post.
     *
     * @return void
     */
    public function test_update_request_updates_is_hidden_value_of_post()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        $this->assertFalse($post->is_hidden);

        $response = $this->put(
            '/posts/' . $post->id . '/visibility',
            ['is_hidden' => TRUE]
        );

        $post->refresh();

        $this->assertTrue($post->is_hidden);

    }//end test_update_request_updates_is_hidden_value_of_post()


    /**
     * Test that only a user with an Admin Role can
     * make a post visibility update request.
     *
     * @return void
     */
    public function test_update_request_can_only_be_made_by_an_admin_user()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        $adminUser = User::factory(['role_id' => Role::ADMIN])->create();

        $nonAdminUser = User::factory(['role_id' => Role::USER])->create();

        Auth::login($adminUser);

        $response = $this->put(
            '/posts/' . $post->id . '/visibility',
            ['is_hidden' => TRUE]
        );

        $response->assertStatus(200);

        Auth::login($nonAdminUser);

        $response = $this->put(
            '/posts/' . $post->id . '/visibility',
            ['is_hidden' => TRUE]
        );

        $response->assertStatus(403);

    }//end test_update_request_can_only_be_made_by_an_admin_user()


    /**
     * Test that update request is rejected if
     * the passed is_hidden value is not a boolean
     *
     * @return void
     */
    public function test_update_request_is_rejected_if_new_is_hidden_is_not_boolean()
    {
        $post = Post::factory()
            ->forUser(['name' => 'Other User'])
            ->create();

        $response = $this->put(
            '/posts/' . $post->id . '/visibility',
            ['is_hidden' => 'true']
        );

        $response->assertStatus(302);

    }//end test_update_request_is_rejected_if_new_is_hidden_is_not_boolean()


}//end class
