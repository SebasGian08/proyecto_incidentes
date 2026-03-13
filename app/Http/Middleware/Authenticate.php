<?php

namespace BolsaTrabajo\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                switch ($guard) {
                    case 'web':
                        $login = '/auth/login';
                        break;
                    case 'empresasw':
                    case 'alumnos':
                        $login = '/';
                        break;
                    default:
                        $login = '/login';
                        break;
                }
                return redirect()->guest($login);
            }
        }
        return $next($request);
    }
}
