<?php

namespace BolsaTrabajo\Http\Middleware;

use BolsaTrabajo\Alumno;
use Closure;
use Illuminate\Support\Facades\Auth;

class AlumnoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $errors = Alumno::ValidatePerfilAlumno();

        if(count($errors) <= 0) return $next($request);

        return redirect(route('alumno.perfil'));
    }
}
