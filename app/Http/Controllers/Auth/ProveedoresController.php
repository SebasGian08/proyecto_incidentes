<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use BolsaTrabajo\Proveedor;
use Illuminate\Support\Facades\Validator;

class ProveedoresController extends Controller
{
    public function index()
    {
        return view('auth.proveedores.index');
    }

    public function list()
    {
        $proveedores = Proveedor::whereNull('deleted_at')->get();

        $data = $proveedores->map(function ($p) {
            return [
                'id_proveedor' => $p->id_proveedor,
                'ruc'          => $p->ruc,
                'razon_social' => $p->razon_social,
                'direccion'    => $p->direccion,
                'telefono'     => $p->telefono,
                'email'        => $p->email,
                'estado'       => $p->estado
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function partialView($id)
    {
        $entity = null;

        if ($id != 0) {
            $entity = Proveedor::find($id);
        }

        return view('auth.proveedores._Mantenimiento', [
            'Entity' => $entity
        ]);
    }

    public function store(Request $request)
    {
        $status = false;

        $validator = Validator::make($request->all(), [
            'ruc' => 'required|unique:proveedor,ruc,' .
                ($request->id_proveedor ?? 'NULL') .
                ',id_proveedor,deleted_at,NULL',

            'razon_social' => 'required',
            'direccion'    => 'nullable',
            'telefono'     => 'nullable',
            'email'        => 'nullable|email',
            'estado'       => 'required|in:1,2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Success' => $status,
                'Errors'  => $validator->errors()
            ]);
        }

        if ($request->id_proveedor) {
            $entity = Proveedor::find($request->id_proveedor);

            if (!$entity) {
                return response()->json([
                    'Success' => $status,
                    'Errors'  => ['Proveedor no encontrado']
                ]);
            }
        } else {
            $entity = new Proveedor();
        }

        $entity->ruc          = trim($request->ruc);
        $entity->razon_social = trim($request->razon_social);
        $entity->direccion    = trim($request->direccion);
        $entity->telefono     = trim($request->telefono);
        $entity->email        = trim($request->email);
        $entity->estado       = $request->estado;

        if ($entity->save()) {
            $status = true;
        }

        return response()->json([
            'Success' => $status,
            'Errors'  => $validator->errors()
        ]);
    }

    public function delete(Request $request)
    {
        $status = false;

        $entity = Proveedor::find($request->id);

        if ($entity && $entity->delete()) {
            $status = true;
        }

        return response()->json(['Success' => $status]);
    }
}
