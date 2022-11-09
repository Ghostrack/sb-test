<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

/**
 * Single action controller that renders the main Dashboard
 */
class UserDashboard extends Controller
{


    /**
     * Render the main Dashboard Page
     *
     * @return InertiaResponse
     */
    public function __invoke(): InertiaResponse
    {
        $posts = Post::with('user:id,name')
            ->isVisible()
            ->orderBy('id', 'desc')
            ->get();

        $sessionFavourites = Auth::hasUser()
            ? []
            : session('favourites') ?? [];

        return Inertia::render('Dashboard', [
            'favourites' => fn () => $sessionFavourites,
            'user'       => fn () => Auth::user() ?? [],
            'posts'      => fn () => $posts,
        ]);

    }//end __invoke()


}//end class
