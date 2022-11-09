<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * Post Visibility Controller
 */
class PostVisibilityController extends Controller
{


    /**
     * Update the is_hidden attribute of the passed post.
     * The is_hidden attribute must be of type boolean.
     *
     * @param integer $postId  Post ID.
     * @param Request $request HTTP Request.
     *
     * @return \Inertia\Response
     */
    public function update(int $postId, Request $request)
    {
        $request->validate(['is_hidden' => 'boolean']);

        if (! Auth::user()->is_admin) {
            return response('This action is unauthorised', 403);
        }

        $post = Post::findOrFail($postId);

        $post->is_hidden = $request->is_hidden;
        $post->save();

        return response($post);

    }//end update()


}//end class
