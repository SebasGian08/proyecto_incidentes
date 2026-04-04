<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;

class ConfidencialidadController extends Controller
{
    public function index()
    {
        return view('auth.confidencialidad.index');
    }

    public function listar()
    {
        $data = \DB::table('maestro_confidencialidad')
            ->whereNull('deleted_at')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required'
        ]);

        \DB::table('maestro_confidencialidad')->insert([
            'nombre' => $request->nombre,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Registrado correctamente']);
    }

    public function get($id)
    {
        return response()->json(
            \DB::table('maestro_confidencialidad')->where('id', $id)->first()
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nombre' => 'required'
        ]);

        \DB::table('maestro_confidencialidad')
            ->where('id', $request->id)
            ->update([
                'nombre' => $request->nombre,
                'updated_at' => now()
            ]);

        return response()->json(['success' => true, 'message' => 'Actualizado correctamente']);
    }

    public function delete(Request $request)
    {
        \DB::table('maestro_confidencialidad')
            ->where('id', $request->id)
            ->update(['deleted_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Eliminado']);
    }
}