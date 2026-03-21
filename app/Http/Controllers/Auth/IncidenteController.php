<?php

namespace BolsaTrabajo\Http\Controllers\Auth;


use Illuminate\Support\Facades\Auth; 
use BolsaTrabajo\User;
use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use BolsaTrabajo\TicketsIncidente;
use Carbon\Carbon;

class IncidenteController extends Controller
{
    public function store(Request $request)
    {
        // Validaciones
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'severidad' => 'required|string|exists:maestro_severidad,nombre',
            'estado' => 'required|string|exists:maestro_estado_ticket,nombre',
            'activo_id' => 'nullable|integer|exists:activos_ti,id',
            'tecnico_asignado_id' => 'nullable|integer|exists:users,id',
        ], [
            'titulo.required' => 'El título del incidente es obligatorio',
            'severidad.required' => 'Debe seleccionar la severidad',
            'severidad.exists' => 'La severidad seleccionada no es válida',
            'estado.required' => 'Debe seleccionar el estado',
            'estado.exists' => 'El estado seleccionado no es válido',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Success' => false,
                'Errors' => $validator->errors()
            ], 422);
        }

        // Obtener IDs de severidad y estado
        $severidadId = \DB::table('maestro_severidad')
            ->where('nombre', $request->severidad)
            ->value('id');

        $estadoId = \DB::table('maestro_estado_ticket')
            ->where('nombre', $request->estado)
            ->value('id');

        // Crear incidente
        $incidente = new TicketsIncidente();
        $incidente->titulo = trim($request->titulo);
        $incidente->descripcion = trim($request->descripcion);
        $incidente->severidad_id = $severidadId;
        $incidente->estado_id = $estadoId;
        $incidente->activo_id = $request->activo_id;
        $incidente->tecnico_asignado_id = $request->tecnico_asignado_id;
        $incidente->usuario_reporta_id = Auth::id();

        // Guardar incidente
        if ($incidente->save()) {
            \DB::table('historial_incidentes')->insert([
                'incidente_id' => $incidente->id,
                'usuario_id' => Auth::id(),
                'accion' => 'Creación',
                'comentario' => 'Incidente registrado por el usuario',
                'fecha_accion' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'Success' => true,
                'Message' => 'Incidente registrado correctamente.'
            ]);
        } else {
            return response()->json([
                'Success' => false,
                'Message' => 'Error al registrar el incidente.'
            ]);
        }
    }

    public function list_all(Request $request)
    {
        $query = \DB::table('tickets_incidentes as t')
            ->join('maestro_estado_ticket as e', 't.estado_id', '=', 'e.id')
            ->leftJoin('activos_ti as a', 't.activo_id', '=', 'a.id')
            ->select(
                't.id',
                't.titulo',
                'e.nombre as estado',
                'a.nombre as activo',
                't.created_at'
            )
            ->where('t.usuario_reporta_id', Auth::id())
            ->whereNull('t.deleted_at');

        if ($request->estado) {
            $query->where('e.nombre', $request->estado);
        }

        if ($request->activo_id) {
            $query->where('t.activo_id', $request->activo_id);
        }

        if ($request->fecha_inicio && $request->fecha_fin) {
            $query->whereBetween('t.created_at', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }

        $incidentes = $query->orderBy('t.id', 'desc')->get();

        return response()->json([
            'data' => $incidentes
        ]);
    }

    public function list_historial($id)
    {
        $historial = \DB::table('historial_incidentes as h')
            ->join('users as u', 'h.usuario_id', '=', 'u.id')
            ->select(
                'h.accion',
                'h.comentario',
                'h.fecha_accion',
                'u.nombres as usuario'
            )
            ->where('h.incidente_id', $id)
            ->orderBy('h.fecha_accion', 'asc')
            ->get();

        return response()->json($historial);
    }
}