<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JokePost
 * @package App
 */
class JokePost extends Model
{
    /**
     * @var string
     */
    protected $table = 'joke_posts';

    /**
     * @var array
     */
    protected $fillable = ['title', 'punchline', 'link'];

    /**
     * @var bool
     */
    public $timestamps = true;
}
