<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use BolsaTrabajo\Error;
use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ErrorController extends Controller
{
    public function index()
    {
        return view('auth.error.index');
    }


}
