<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PhotoPost
 * @package App
 */
class PhotoPost extends Model
{

    /**
     * @var string
     */
    protected $table = 'photo_posts';

    /**
     * @var array
     */
    protected $fillable = ['title', 'link'];

    /**
     * @var bool
     */
    public $timestamps = true;
}