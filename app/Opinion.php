<?php

namespace BolsaTrabajo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opinion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'opinion','rating'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];
}
