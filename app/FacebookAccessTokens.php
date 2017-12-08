<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FacebookAccessTokens
 * @package App
 */
class FacebookAccessTokens extends Model
{
    /**
     * @var string
     */
    protected $table = 'facebook_access_token';

    /**
     * @var array
     */
    protected $fillable = ['token'];

    /**
     * @var bool
     */
    public $timestamps = true;
}
