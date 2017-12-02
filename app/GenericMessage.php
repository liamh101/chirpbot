<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GenericMessage
 * @package App
 */
class GenericMessage extends Model
{
    /**
     * @var string
     */
    protected $table = 'generic_message';

    /**
     * @var array
     */
    protected $fillable = ['message', 'response'];

    /**
     * @var bool
     */
    public $timestamps = false;
}