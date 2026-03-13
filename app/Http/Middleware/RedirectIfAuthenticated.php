<?php

namespace BolsaTrabajo\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            switch ($guard){
                case 'web':
                    return redirect('/auth/inicio');
                case 'alumnos':
                    return redirect('/alumno/avisos');
                case 'empresasw':
                    return redirect('/empresa/avisos');
            }
        }
        return $next($request);
    }
}
