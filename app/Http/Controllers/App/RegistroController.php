<?php

namespace BolsaTrabajo\Http\Controllers\App;

use BolsaTrabajo\User;
use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegistroController extends Controller
{
    public function index()
    {
        return view('app.registro.index');
    }

    public function store(Request $request)
    {
        $status = false;

        $validator = Validator::make($request->all(), [

            'pais' => 'required|string|max:100',

            'ecommerce' => 'required|string|max:150',

            'nombres' => 'required|string|max:150',

            'correo' => 'required|email|max:150|unique:users,email,NULL,id,deleted_at,NULL',

            'user' => 'required|string|min:4|max:100|unique:users,usuario,NULL,id,deleted_at,NULL',

            'password' => 'required|string|min:6|max:100',

            'telefono' => 'required|regex:/^[0-9]{7,15}$/'

        ], [

            'pais.required' => 'El país es obligatorio',

            'ecommerce.required' => 'El nombre del ecommerce es obligatorio',

            'nombres.required' => 'Los nombres son obligatorios',

            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'Ingrese un correo válido',
            'correo.unique' => 'Este correo ya está registrado',

            'user.required' => 'El usuario es obligatorio',
            'user.min' => 'El usuario debe tener mínimo 4 caracteres',
            'user.unique' => 'Este usuario ya existe',

            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener mínimo 6 caracteres',

            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe contener solo números (7 a 15 dígitos)'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'Success' => false,
                'Errors' => $validator->errors()
            ], 422);

        }

        $entity = new User();

        $entity->profile_id = 5;
        $entity->estado = 0;

        $entity->pais = trim($request->pais);
        $entity->ecommerce_nombre = trim($request->ecommerce);
        $entity->nombres = trim($request->nombres);
        $entity->email = trim($request->correo);
        $entity->usuario = trim($request->user);
        $entity->telefono = trim($request->telefono);

        $entity->password = Hash::make($request->password);

        $entity->online = 0;
        $entity->inicio_sesion = null;
        $entity->cerrar_sesion = null;

        if ($entity->save()) {
            $status = true;
        }

        return response()->json([
            'Success' => $status,
            'Message' => $status 
                ? 'Usuario registrado correctamente. Pendiente de aprobación.' 
                : 'Error al registrar el usuario.'
        ]);
    }
}