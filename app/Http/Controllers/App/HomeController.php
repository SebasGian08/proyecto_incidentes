<?php

namespace BolsaTrabajo\Http\Controllers\App;

use BolsaTrabajo\Alumno;
use BolsaTrabajo\App;
use BolsaTrabajo\TipoPrograma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    public function __construct()
    {
    }

    public function filtro_distritos($id)
    {
        return response()->json(Distrito::where('provincia_id', $id)->orderBy('nombre', 'asc')->get());
    }

    public function index()
    {
        return view('app.home.index');
    }

    


    
}