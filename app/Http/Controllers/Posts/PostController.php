<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StorePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Base Post Controller
 */
class PostController extends Controller
{


    /**
     * Display the create post form.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $this->authorize('create', Post::class);

        return Inertia::render('Posts/Editor');

    }//end create()


    /**
     * Store a new post
     *
     * @param StorePostRequest $request Validated request data.
     *
     * @return \Inertia\Response
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $post          = new Post();
        $post->title   = $validated['title'];
        $post->content = $validated['content'];
        $post->user_id = Auth::id();
        $post->save();

        return Redirect::route('dashboard');

    }//end store()


    /**
     * Display the edit post form.
     *
     * @param integer $id Post ID.
     *
     * @return \Inertia\Response
     */
    public function edit(int $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        return Inertia::render(
            'Posts/Editor',
            ['initialPostData' => $post]
        );

    }//end edit()


    /**
     * Update an existing post
     *
     * @param integer          $id      Post ID.
     * @param StorePostRequest $request Validated request data.
     *
     * @return \Inertia\Response
     */
    public function update(int $id, StorePostRequest $request)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $validated = $request->validated();

        $post->title   = $validated['title'];
        $post->content = $validated['content'];
        $post->save();

        return Redirect::route('dashboard');

    }//end update()


    /**
     * Delete a post
     *
     * @param integer $postId Post ID.
     *
     * @return \Inertia\Response
     */
    public function destroy(int $postId)
    {
        $post = Post::findOrFail($postId);

        $this->authorize('update', $post);

        $post->delete();

        return response('Post Deleted');

    }//end destroy()


}//end class
