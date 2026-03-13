<?php

namespace BolsaTrabajo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';
    public $timestamps = true;

    protected $fillable = [
        'codigo_pedido',
        'id_usuario',
        'nombre_cliente',
        'direccion_cliente',
        'telefono_cliente',
        'fecha_registro',
        'fecha_pedido',
        'fecha_entrega',
        'id_metodo_pago',
        'tipo_pedido',
        'direccion_envio',
        'ubigeo_envio',
        'subtotal',
        'igv',
        'total',
        'observacion',
        'estado',
        'estado_pedido',
    ];

    // Relación con detalles del pedido
    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class, 'id_pedido', 'id_pedido');
    }

    // Relación con el usuario que hizo el pedido
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    // Relación con el método de pago
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago', 'id_metodo_pago');
    }
}
