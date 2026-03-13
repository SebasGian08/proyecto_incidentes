<?php

namespace BolsaTrabajo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketsIncidente extends Model
{
    use HasFactory;

    protected $table = 'tickets_incidentes';

    protected $fillable = [
        'titulo',
        'descripcion',
        'severidad_id',
        'estado_id',
        'activo_id',
        'usuario_reporta_id',
        'tecnico_asignado_id',
    ];
}