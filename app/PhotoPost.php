<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class PhotoPost extends Model
{

    protected $table = 'photo_posts';

    protected $fillable = ['title', 'link'];

    public $timestamps = true;
}