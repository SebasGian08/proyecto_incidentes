<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use BolsaTrabajo\Actividad_economica;
use Illuminate\Support\Facades\Auth; // Importa la clase Auth
use BolsaTrabajo\Tipo_persona;
use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function  index()
    {
        if (Auth::guard('web')->user()->profile_id == \BolsaTrabajo\App::$PERFIL_DESARROLLADOR) {
        return view('auth.home.index', ['tipo_persona' => Tipo_persona::all()]);
    } // Opcionalmente, podrías manejar el caso en que la condición no se cumple
    return redirect('/auth/error'); // Redirige a una página predeterminada si la condición no se cumple
    }
}
