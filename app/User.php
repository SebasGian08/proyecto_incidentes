<?php

namespace BolsaTrabajo;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'profile_id', 'nombres', 'email', 'usuario', 'password',
        'telefono', 'pais', 'ecommerce_nombre', 'estado',
        'online', 'inicio_sesion', 'cerrar_sesion'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function profile()
    {
        return $this->belongsTo('\BolsaTrabajo\Profile', 'profile_id');
    }
}