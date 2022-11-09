<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * User Favourite Post relationship controller
 */
class UserFavouritePostController extends Controller
{


    /**
     * Add a post to the current user's favourites.
     * If no user is authenticated then the array of
     * favourited post ids will be stored in the session.
     *
     * @param integer $postId  Post ID.
     * @param Request $request HTTP Request.
     *
     * @return \Inertia\Response
     */
    public function store(int $postId, Request $request)
    {
        $post = Post::findOrFail($postId);

        if (Auth::hasUser()) {
            $user = Auth::user();
            $user->favouritePosts()->attach($postId);
        } else {
            $request->session()->push('favourites', $postId);
        }

        $post->increment('favourite_count');

        return response('Added to favourites');

    }//end store()


    /**
     * Remove a post from the current user's favourites.
     *
     * @param integer $postId  Post ID.
     * @param Request $request HTTP Request.
     *
     * @return \Inertia\Response
     */
    public function destroy(int $postId, Request $request)
    {
        $post = Post::findOrFail($postId);

        if (Auth::hasUser()) {
            $user = Auth::user();
            $user->favouritePosts()->detach($postId);
        } else {
            $favouriteIds = $request->session()->get('favourites');
            $favouriteIds = array_values(array_diff($favouriteIds, [$postId]));

            $request->session()->put('favourites', $favouriteIds);
        }

        $post->decrement('favourite_count');

        return response('Removed from favourites');

    }//end destroy()


}//end class
