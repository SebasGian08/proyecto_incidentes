<?php

namespace BolsaTrabajo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_producto',
        'id_producto_marca',
        'codigo_producto',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock',
        'estado',
        'imagen',
    ];

    protected $dates = ['deleted_at'];

    public function marca()
    {
        return $this->belongsTo(ProductoMarca::class, 'id_producto_marca');
    }
}
