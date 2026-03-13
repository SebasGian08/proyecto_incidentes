<?php

namespace BolsaTrabajo;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ubigeo extends Model
{
    use SoftDeletes;

    protected $table = 'ubigeos';
    protected $fillable = ['codigo', 'departamento', 'provincia', 'distrito', 'estado'];
    protected $dates = ['deleted_at'];
}
