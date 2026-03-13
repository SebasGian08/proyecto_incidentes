<?php

namespace BolsaTrabajo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distrito extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'provincia_id', 'nombre'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public function asistentes()
    {
        return $this->hasMany(Asistentes::class);
    }
}
