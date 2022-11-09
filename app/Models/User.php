<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_admin'];

    /**
     * Default attribute values
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'role_id' => Role::USER,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin'          => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get if the user has Admin role
     *
     * @return Attribute
     */
    protected function isAdmin(): Attribute
    {
        return Attribute::make(get: function ($value) {
            return $this->role_id === Role::ADMIN;
        });

    }//end isAdmin()


    /**
     * Posts created by the user.
     * One to many relationship.
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);

    }//end posts()


    /**
     * Posts favourited by the user.
     * Many to many relationship.
     *
     * @return BelongsToMany
     */
    public function favouritePosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'favourite_post_user');

    }//end favouritePosts()


}//end class
