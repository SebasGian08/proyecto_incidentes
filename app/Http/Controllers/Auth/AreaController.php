<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use BolsaTrabajo\Area;
use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index()
    {
        return view('auth.area.index');
    }

    public function list_all()
    {
        return response()->json(['data' => Area::orderby('id', 'desc')->get()]);
    }

    public function partialView($id)
    {
        $entity = null;

        if($id != 0) $entity = Area::find($id);

        return view('auth.area._Mantenimiento', ['Entity' => $entity]);
    }

    public function store(Request $request)
    {
        $status = false;

        if($request->id != 0)
            $entity = Area::find($request->id);
        else
            $entity = new Area();

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:areas,nombre,'.($request->id != 0 ? $request->id : "NULL").',id,deleted_at,NULL'
        ]);

        if (!$validator->fails()){
            $entity->nombre = trim($request->nombre);
            if($entity->save()) $status = true;
        }

        return response()->json(['Success' => $status, 'Errors' => $validator->errors()]);
    }

    public function delete(Request $request)
    {
        $status = false;

        $entity = Area::find($request->id);

        if($entity->delete()) $status = true;

        return response()->json(['Success' => $status]);
    }
}
