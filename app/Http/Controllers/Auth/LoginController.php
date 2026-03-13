<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use BolsaTrabajo\User;
use BolsaTrabajo\Asistentes;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/auth/inicio';
    protected $redirectAfterLogout = '/auth/login';
    protected $loginView = 'auth.login.index';

    public function __construct()
    {
        $this->middleware('guest:web', ['except' => ['logout', 'partialView_change_password', 'change_password'] ]);
        if(isset($_POST['validacion'])){
            User::where('email', $_POST['validacion'])->update(['online' => 0]);
            User::where('email', $_POST['validacion'])->update(['cerrar_sesion' => date('y-m-d h:i:s')]);
        }
 
    }

    public function partialView_change_password()
    {
        return view('auth.login._ChangePassword');
    }

    public function view_publicar_oferta()
    {
        return view ('auth.login-oferta.index');
    }

    protected function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $this->getCredentials($request);

        $usuario = User::where('estado', 1)->where('email', $credentials['email'])->first();

        if ($usuario != null && Hash::check($credentials['password'], $usuario->password)) {
            Auth::guard($this->getGuard())->login($usuario, $request->has('remember'));
            $usuario->update([
                'online' => 1,
                'inicio_sesion' => now()
            ]);
            return $this->handleUserWasAuthenticated($request, null);
        }

        // Enviar error a la sesión para mostrar en la vista
        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'Credenciales incorrectas o usuario inactivo');
    }


    public function change_password(Request $request)
    {
        $status = false;

        $validator = Validator::make($request->all(), [
            'password_old' => 'required',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6'
        ]);

        if (!$validator->fails()){

            $usuario = User::find(Auth::guard('web')->user()->id);

            if(Hash::check($request->password_old, $usuario->password)) {
                $usuario->password = Hash::make($request->password);
                if($usuario->save()) $status = true;
            }else{
                return response()->json(['Success' => $status, 'Message' => "La contraseña actual ingresada no es la correcta"]);
            }
        }

        return response()->json(['Success' => $status, 'Errors' => $validator->errors()]);
    }

    
}