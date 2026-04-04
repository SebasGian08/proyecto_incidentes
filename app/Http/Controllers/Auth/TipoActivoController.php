<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;

class TipoActivoController extends Controller
{
    public function index()
    {
        return view('auth.tipo_activos.index');
    }

    public function listar()
    {
        $data = \DB::table('maestro_tipos_activos')
            ->whereNull('deleted_at')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'nombre' => 'required',
                'codigo' => 'required|unique:maestro_tipos_activos,codigo'
            ]);

            \DB::table('maestro_tipos_activos')->insert([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Registrado correctamente']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function get($id)
    {
        return response()->json(
            \DB::table('maestro_tipos_activos')->where('id', $id)->first()
        );
    }

    public function update(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required',
                'nombre' => 'required',
                'codigo' => 'required|unique:maestro_tipos_activos,codigo,' . $request->id
            ]);

            \DB::table('maestro_tipos_activos')
                ->where('id', $request->id)
                ->update([
                    'nombre' => $request->nombre,
                    'codigo' => $request->codigo,
                    'descripcion' => $request->descripcion,
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Actualizado correctamente']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        \DB::table('maestro_tipos_activos')
            ->where('id', $request->id)
            ->update(['deleted_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Eliminado']);
    }
}