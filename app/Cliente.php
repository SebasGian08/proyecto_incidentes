<?php

namespace BolsaTrabajo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
use SoftDeletes;

protected $table = 'clientes';
protected $primaryKey = 'id_cliente';

protected $fillable = [
'user_id',
'documento',
'nombres',
'direccion',
'telefono',
'email',
'estado'
];

public function user()
{
return $this->belongsTo(User::class,'user_id');
}
}
