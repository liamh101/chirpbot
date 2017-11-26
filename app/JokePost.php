<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JokePost extends Model
{
    protected $table = 'joke_posts';

    protected $fillable = ['title', 'punchline', 'link'];

    public $timestamps = true;
}
