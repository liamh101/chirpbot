<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserJoke extends Model
{
    protected $table = 'user_joke';

    protected $fillable = ['userId', 'jokeId'];

    public $timestamps = false;
}
