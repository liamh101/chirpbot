<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RedditDomainBlacklist
 * @package App
 */
class RedditDomainBlacklist extends Model
{
    /**
     * @var string
     */
    protected $table = 'reddit_blacklist';

    /**
     * @var array
     */
    protected $fillable = ['domain'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
