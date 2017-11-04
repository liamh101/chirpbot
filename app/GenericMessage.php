<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenericMessage extends Model
{
    protected $table = 'generic_message';

    protected $fillable = ['message', 'response'];

    public $timestamps = false;
}