<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageMessage extends Model
{

    protected $table = 'image_message';

    protected $fillable = ['message', 'response', 'image'];

    public $timestamps = false;

}