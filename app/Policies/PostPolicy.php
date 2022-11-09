<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Post Model Policy
 */
class PostPolicy
{
    use HandlesAuthorization;


    /**
     * Determine if the user can create new posts.
     *
     * @param User $user Authenticated user.
     *
     * @return boolean
     */
    public function create(User $user): bool
    {
        return ! empty($user);

    }//end create()


    /**
     * Determine if the given post can be updated by the user.
     *
     * @param User $user Authenticated user.
     * @param Post $post Post to update.
     *
     * @return boolean
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;

    }//end update()


}//end class
