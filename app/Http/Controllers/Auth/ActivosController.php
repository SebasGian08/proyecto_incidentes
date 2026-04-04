<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;

class ActivosController extends Controller
{
    public function index(Request $request)
    {
        return view('auth.activos.index');
    }

    public function listar(Request $request)
    {
        $query = \DB::table('activos_ti as a')
            ->leftJoin('users as u', 'a.usuario_responsable_id', '=', 'u.id')
            ->leftJoin('maestro_estado_activo as e', 'a.estado_id', '=', 'e.id')
            ->leftJoin('maestro_tipos_activos as t', 'a.tipo_id', '=', 't.id')
            ->leftJoin('maestro_ubicacion_fisica as ub', 'a.ubicacion_id', '=', 'ub.id')
            ->select(
                'a.id',
                'a.codigo_patrimonial',
                'a.nombre',
                'a.numero_serie',
                \DB::raw("COALESCE(t.nombre, 'Sin tipo') as tipo"),
                \DB::raw("COALESCE(ub.nombre, 'Sin ubicación') as ubicacion"),
                \DB::raw("COALESCE(u.nombres, 'Sin asignar') as usuario"),
                \DB::raw("COALESCE(e.nombre, 'Sin estado') as estado"),
                'a.estado_id',
                'a.tipo_id',
                'a.ubicacion_id',
                'a.created_at'
            )
            ->whereNull('a.deleted_at');


        if ($request->estado) {
            $query->where('a.estado_id', $request->estado);
        }

        if ($request->tipo) {
            $query->where('a.tipo_id', $request->tipo);
        }

        if ($request->ubicacion) {
            $query->where('a.ubicacion_id', $request->ubicacion);
        }

        if ($request->fechaInicio && $request->fechaFin) {
            $query->whereBetween('a.created_at', [
                $request->fechaInicio . ' 00:00:00',
                $request->fechaFin . ' 23:59:59'
            ]);
        }

        $data = $query->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function store(Request $request)
    {
        try {


            $request->validate([
                'codigo_patrimonial' => 'required|unique:activos_ti,codigo_patrimonial',
                'nombre' => 'required',
                'numero_serie' => 'required',
                'tipo_id' => 'required',
                'usuario_responsable_id' => 'nullable',
                'criticidad_id' => 'required',
                'confidencialidad_id' => 'required',
                'ubicacion_id' => 'required',
                'estado_id' => 'required',
            ]);


            \DB::table('activos_ti')->insert([
                'codigo_patrimonial' => $request->codigo_patrimonial,
                'nombre' => $request->nombre,
                'numero_serie' => $request->numero_serie,
                'tipo_id' => $request->tipo_id,
                'usuario_responsable_id' => $request->usuario_responsable_id,
                'criticidad_id' => $request->criticidad_id,
                'confidencialidad_id' => $request->confidencialidad_id,
                'ubicacion_id' => $request->ubicacion_id,
                'estado_id' => $request->estado_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Activo registrado correctamente'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);

        }
    }

    public function get($id)
    {
        $data = \DB::table('activos_ti')
            ->where('id', $id)
            ->first();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required',
                'codigo_patrimonial' => 'required|unique:activos_ti,codigo_patrimonial,' . $request->id,
                'nombre' => 'required',
                'numero_serie' => 'required',
                'tipo_id' => 'required',
                'criticidad_id' => 'required',
                'confidencialidad_id' => 'required',
                'ubicacion_id' => 'required',
                'estado_id' => 'required',
            ]);

            \DB::table('activos_ti')
                ->where('id', $request->id)
                ->update([
                    'codigo_patrimonial' => $request->codigo_patrimonial,
                    'nombre' => $request->nombre,
                    'numero_serie' => $request->numero_serie,
                    'tipo_id' => $request->tipo_id,
                    'usuario_responsable_id' => $request->usuario_responsable_id,
                    'criticidad_id' => $request->criticidad_id,
                    'confidencialidad_id' => $request->confidencialidad_id,
                    'ubicacion_id' => $request->ubicacion_id,
                    'estado_id' => $request->estado_id,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Activo actualizado correctamente'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);

        }
    }

    public function delete(Request $request)
    {
        try {

            if (!$request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID no recibido'
                ]);
            }

            \DB::table('activos_ti')
                ->where('id', $request->id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Activo eliminado correctamente'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);

        }
    }
}
