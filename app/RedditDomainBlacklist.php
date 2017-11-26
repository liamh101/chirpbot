<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RedditDomainBlacklist extends Model
{
    protected $table = 'reddit_blacklist';

    protected $fillable = ['domain'];

    public $timestamps = false;
}
