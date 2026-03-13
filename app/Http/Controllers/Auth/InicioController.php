<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;

class InicioController extends Controller
{
    public function index(Request $request)
    {
        return view('auth.inicio.index');
    }
}
