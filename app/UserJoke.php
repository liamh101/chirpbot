<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserJoke
 * @package App
 */
class UserJoke extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_joke';

    /**
     * @var array
     */
    protected $fillable = ['userId', 'jokeId'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
