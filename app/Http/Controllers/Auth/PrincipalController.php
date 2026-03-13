<?php

namespace BolsaTrabajo\Http\Controllers\Auth;


use Illuminate\Support\Facades\Auth; // Importa la clase Auth
use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PrincipalController extends Controller
{

    public function index()
    {
        if (Auth::guard('web')->user()->profile_id == \BolsaTrabajo\App::$PERFIL_TIMOTEO) {
            return view('auth.principal.index');
        } // Opcionalmente, podrías manejar el caso en que la condición no se cumple
        return redirect('/auth/error'); // Redirige a una página predeterminada si la condición no se cumple
    }
      

    
}