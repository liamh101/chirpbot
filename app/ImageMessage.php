<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ImageMessage
 * @package App
 */
class ImageMessage extends Model
{

    /**
     * @var string
     */
    protected $table = 'image_message';

    /**
     * @var array
     */
    protected $fillable = ['message', 'response', 'image'];

    /**
     * @var bool
     */
    public $timestamps = false;

}