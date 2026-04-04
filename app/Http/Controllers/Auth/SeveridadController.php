<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;

class SeveridadController extends Controller
{
    public function index()
    {
        return view('auth.severidad.index');
    }

    public function listar()
    {
        $data = \DB::table('maestro_severidad')
            ->whereNull('deleted_at')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'nombre' => 'required',
                'sla_minutos' => 'required|numeric'
            ]);

            \DB::table('maestro_severidad')->insert([
                'nombre' => $request->nombre,
                'sla_minutos' => $request->sla_minutos,
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
            \DB::table('maestro_severidad')->where('id', $id)->first()
        );
    }

    public function update(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required',
                'nombre' => 'required',
                'sla_minutos' => 'required|numeric'
            ]);

            \DB::table('maestro_severidad')
                ->where('id', $request->id)
                ->update([
                    'nombre' => $request->nombre,
                    'sla_minutos' => $request->sla_minutos,
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Actualizado correctamente']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        \DB::table('maestro_severidad')
            ->where('id', $request->id)
            ->update(['deleted_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Eliminado']);
    }
}