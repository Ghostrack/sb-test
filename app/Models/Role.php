<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User Role Model
 */
class Role extends Model
{
    use HasFactory;

    public const ADMIN = 1;

    public const USER = 2;

    /**
     * Attributes fillable on mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * Disable Timestamps
     *
     * @var boolean
     */
    public $timestamps = FALSE;


    /**
     * User relationship
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);

    }//end users()


}//end class
