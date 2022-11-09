<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

/**
 * Post Model
 */
class Post extends Model
{
    use HasFactory;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_favourite'];

    /**
     * Default attribute values
     *
     * @var array
     */
    protected $attributes = [
        'favourite_count' => 0,
        'is_hidden'       => FALSE,
    ];

    /**
     * Attributes to be casted on serialisation.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'is_hidden'  => 'boolean',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'title',
    ];


    /**
     * Get if the post has been favourited by the current user
     *
     * @return Attribute
     */
    protected function isFavourite(): Attribute
    {
        return Attribute::make(get: function ($value) {
            if (! Auth::hasUser()) {
                return FALSE;
            }

            return Auth::user()
                ->favouritePosts()
                ->where('id', $this->id)
                ->exists();
        });

    }//end isFavourite()


    /**
     * Query builder helper.
     * Select only posts that are visible to the user.
     * If the user is an admin, all posts are visible.
     * If not, only non-hidden and own posts will be visible.
     *
     * @param Builder $query Query builder.
     *
     * @return Builder
     */
    public function scopeIsVisible(Builder $query): Builder
    {
        if (Auth::hasUser() && Auth::user()->is_admin) {
            return $query;
        }

        return $query->where('is_hidden', FALSE)
            ->orWhere('user_id', Auth::id());

    }//end scopeIsVisible()


    /**
     * Users that have favourited the post.
     * Many to many relationship.
     *
     * @return BelongsToMany
     */
    public function favouriteUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favourite_post_user');

    }//end favouriteUsers()


    /**
     * Post author user relationship
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);

    }//end user()


}//end class
