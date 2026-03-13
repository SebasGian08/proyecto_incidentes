<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use BolsaTrabajo\ProductoMarca;
use Illuminate\Support\Facades\Validator;

class MarcasController extends Controller
{
    public function index()
    {
        $marcas = ProductoMarca::whereNull('deleted_at')->get();

        return view('auth.marcas.index', compact('marcas'));
    }

    public function list()
    {
        $marcas = ProductoMarca::whereNull('deleted_at')->get();
        $data = $marcas->map(function($marca) {
            return [
                'id_producto_marca' => $marca->id_producto_marca,
                'descripcion'       => $marca->descripcion,
                'estado'            => $marca->estado
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function partialView($id)
    {
        $entity = null;

        if ($id != 0) {
            $entity = ProductoMarca::find($id);
        }

        return view('auth.marcas._Mantenimiento', [
            'Entity' => $entity
        ]);
    }

    public function store(Request $request)
    {
        $status = false;

        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|unique:productos_marca,descripcion,' .
                ($request->id_producto_marca ?? 'NULL') .
                ',id_producto_marca,deleted_at,NULL',
            'estado' => 'required|in:1,2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Success' => $status,
                'Errors'  => $validator->errors()
            ]);
        }

        if ($request->id_producto_marca) {
            $entity = ProductoMarca::find($request->id_producto_marca);

            if (!$entity) {
                return response()->json([
                    'Success' => $status,
                    'Errors'  => ['Marca no encontrada.']
                ]);
            }
        } else {
            $entity = new ProductoMarca();
        }

        $entity->descripcion = trim($request->descripcion);
        $entity->estado      = $request->estado;

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

        $entity = ProductoMarca::find($request->id);

        if ($entity && $entity->delete()) {
            $status = true;
        }

        return response()->json(['Success' => $status]);
    }

}
    