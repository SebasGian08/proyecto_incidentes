<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;

class UbicacionFisicaController extends Controller
{
    public function index()
    {
        return view('auth.ubicacion_fisica.index');
    }

    public function listar()
    {
        $data = \DB::table('maestro_ubicacion_fisica')
            ->whereNull('deleted_at')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required'
        ]);

        \DB::table('maestro_ubicacion_fisica')->insert([
            'nombre' => $request->nombre,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Registrado correctamente']);
    }

    public function get($id)
    {
        return response()->json(
            \DB::table('maestro_ubicacion_fisica')->where('id', $id)->first()
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nombre' => 'required'
        ]);

        \DB::table('maestro_ubicacion_fisica')
            ->where('id', $request->id)
            ->update([
                'nombre' => $request->nombre,
                'updated_at' => now()
            ]);

        return response()->json(['success' => true, 'message' => 'Actualizado correctamente']);
    }

    public function delete(Request $request)
    {
        \DB::table('maestro_ubicacion_fisica')
            ->where('id', $request->id)
            ->update(['deleted_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Eliminado']);
    }
}