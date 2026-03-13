<?php

namespace BolsaTrabajo;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetodoPago extends Model
{
    use SoftDeletes;

    protected $table = 'metodo_pagos';
    protected $fillable = ['descripcion', 'estado'];
    protected $dates = ['deleted_at'];
}
