<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use BolsaTrabajo\Profile;
use Illuminate\Support\Facades\Validator;

class ProfilesController extends Controller
{
    public function index()
    {
        return view('auth.profiles.index');
    }

    public function list_all()
    {
        $data = Profile::whereNull('deleted_at')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function partialView($id)
    {
        $entity = $id != 0 ? Profile::find($id) : null;

        return view('auth.profiles._Mantenimiento', [
            'Entity' => $entity
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:profiles,name,' .
                ($request->id ?? 'NULL') .
                ',id,deleted_at,NULL'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Success' => false,
                'Errors'  => $validator->errors()
            ]);
        }

        $entity = $request->id
            ? Profile::find($request->id)
            : new Profile();

        if (!$entity) {
            return response()->json([
                'Success' => false,
                'Errors' => ['Perfil no encontrado']
            ]);
        }

        $entity->name = trim($request->name);
        $entity->save();

        return response()->json([
            'Success' => true,
            'Errors'  => []
        ]);
    }

    public function delete(Request $request)
    {
        $entity = Profile::find($request->id);

        if ($entity && $entity->delete()) {
            return response()->json(['Success' => true]);
        }

        return response()->json(['Success' => false]);
    }
}
