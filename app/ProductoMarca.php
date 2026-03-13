<?php

namespace BolsaTrabajo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoMarca extends Model
{
    use SoftDeletes;

    protected $table = 'productos_marca';
    protected $primaryKey = 'id_producto_marca';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'descripcion',
        'estado'
    ];
}
