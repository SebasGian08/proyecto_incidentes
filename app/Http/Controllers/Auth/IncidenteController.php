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
            'severidad' => 'required|integer|exists:maestro_severidad,id',
            'estado' => 'required|integer|exists:maestro_estado_ticket,id',
            'activo_id' => 'nullable|integer|exists:activos_ti,id',
            'tecnico_asignado_id' => 'nullable|integer|exists:users,id',
            'evidencia' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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

        $severidadId = $request->severidad;
        $estadoId = $request->estado;

        // Crear incidente
        $incidente = new TicketsIncidente();
        $incidente->titulo = trim($request->titulo);
        $incidente->descripcion = trim($request->descripcion);
        $incidente->severidad_id = $severidadId;
        $incidente->estado_id = $estadoId;
        $incidente->activo_id = $request->activo_id;
        $incidente->tecnico_asignado_id = $request->tecnico_asignado_id;
        $incidente->usuario_reporta_id = Auth::id();
        if ($request->hasFile('evidencia')) {
            $file = $request->file('evidencia');
            $fileName = uniqid('INC_') . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/incidentes/';

            if (!file_exists(public_path($filePath))) {
                mkdir(public_path($filePath), 0777, true);
            }

            $file->move(public_path($filePath), $fileName);

            $incidente->evidencia = $filePath . $fileName;
        }

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

    public function agregarComentario(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'incidente_id' => 'required|integer|exists:tickets_incidentes,id',
            'comentario' => 'required|string|max:1000'
        ], [
            'comentario.required' => 'El comentario es obligatorio'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            \DB::table('historial_incidentes')->insert([
                'incidente_id' => $request->incidente_id,
                'usuario_id' => Auth::id(),
                'accion' => 'Comentario',
                'comentario' => trim($request->comentario),
                'fecha_accion' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comentario agregado correctamente'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar comentario',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function list_all_filtros(Request $request)
    {
        $query = \DB::table('tickets_incidentes as t')
            ->join('maestro_estado_ticket as e', 't.estado_id', '=', 'e.id')
            ->join('maestro_severidad as s', 't.severidad_id', '=', 's.id')
            ->leftJoin('activos_ti as a', 't.activo_id', '=', 'a.id')
            ->select(
                't.id',
                't.titulo',
                'e.nombre as estado',
                's.nombre as severidad',
                'a.nombre as activo',
                't.created_at'
            )
            ->whereNull('t.deleted_at');

        // filtros
        if ($request->estado) {
            $query->where('e.nombre', $request->estado);
        }

        if ($request->severidad) {
            $query->where('s.id', $request->severidad);
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

        return response()->json([
            'data' => $query->orderBy('t.id', 'desc')->get()
        ]);
    }

    public function index(Request $request)
    {
        return view('auth.incidentes.index');
    }

    public function get($id)
    {
        $incidente = \DB::table('tickets_incidentes as t')
            ->join('maestro_estado_ticket as e', 't.estado_id', '=', 'e.id')
            ->join('maestro_severidad as s', 't.severidad_id', '=', 's.id')
            ->leftJoin('activos_ti as a', 't.activo_id', '=', 'a.id')
            ->select(
                't.*',
                't.evidencia',
                'e.nombre as estado_nombre',
                's.nombre as severidad_nombre',
                'a.nombre as activo_nombre',
                'e.nombre as estado_nombre',
                's.nombre as severidad_nombre',
                'a.nombre as activo_nombre'
            )
            ->where('t.id', $id)
            ->first();

        return response()->json($incidente);
    }

    public function update(Request $request)
    {
        $incidente = TicketsIncidente::find($request->id);

        $comentarios = [];

        $estados = [
            1 => 'Pendiente',
            2 => 'En Proceso',
            3 => 'Cerrado'
        ];

        $estadoAnterior = $incidente->estado_id;

        if ($request->tecnico_id) {
            $tecnicoAnterior = $incidente->tecnico_asignado_id;

            $incidente->tecnico_asignado_id = $request->tecnico_id;

            $tecnico = User::find($request->tecnico_id);

            if ($tecnicoAnterior) {
                $tecnicoOld = User::find($tecnicoAnterior);
                $comentarios[] = 'Reasignado de ' . ($tecnicoOld->nombres ?? '-') . ' a ' . ($tecnico->nombres ?? '-');
            } else {
                $comentarios[] = 'Asignado a ' . ($tecnico->nombres ?? 'Técnico');
            }
        }

        if ($request->estado_id && $request->estado_id != $estadoAnterior) {

            $nuevoEstado = $estados[$request->estado_id] ?? 'Desconocido';
            $estadoOld = $estados[$estadoAnterior] ?? 'Desconocido';

            $incidente->estado_id = $request->estado_id;

            $comentarios[] = "Cambio de estado de $estadoOld a $nuevoEstado";

            if ($request->estado_id == 3) {
                $incidente->fecha_cierre = now();
            }
        }

        // GUARDAR EVIDENCIA
        if ($request->hasFile('evidencia')) {

            $file = $request->file('evidencia');
            $fileName = uniqid('SOL_') . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/incidentes/';

            if (!file_exists(public_path($filePath))) {
                mkdir(public_path($filePath), 0777, true);
            }

            $file->move(public_path($filePath), $fileName);

            $incidente->evidencia = $filePath . $fileName;
        }

        // AGREGAR COMENTARIO
        if ($request->comentario) {
            $comentarios[] = 'Solución: ' . $request->comentario;
        }

        $incidente->save();

        if (count($comentarios) > 0) {

            \DB::table('historial_incidentes')->insert([
                'incidente_id' => $incidente->id,
                'usuario_id' => Auth::id(),
                'accion' => 'Actualización',
                'comentario' => implode(' | ', $comentarios),
                'fecha_accion' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function gestion(Request $request)
    {
        return view('auth.incidentes.gestion');
    }
    
    public function misIncidentes()
    {
        $data = \DB::table('tickets_incidentes as t')
            ->join('maestro_estado_ticket as e', 't.estado_id', '=', 'e.id')
            ->join('maestro_severidad as s', 't.severidad_id', '=', 's.id')
            ->leftJoin('activos_ti as a', 't.activo_id', '=', 'a.id')
            ->select(
                't.id',
                't.titulo',
                't.descripcion',
                'e.nombre as estado',
                's.nombre as severidad',
                'a.nombre as activo',
                't.created_at'
            )
            ->where('t.tecnico_asignado_id', Auth::id())
            ->whereNull('t.deleted_at')
            ->orderBy('t.id', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }

    // Método para guardar calificación y comentario
    public function calificar(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:tickets_incidentes,id',
            'rating' => 'required|numeric|min:0.5|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Success' => false,
                'Errors' => $validator->errors()
            ]);
        }

        \DB::table('incidente_calificaciones')->insert([
            'incidente_id' => $request->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comentario' => $request->comentario,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'Success' => true,
            'Message' => 'Calificación enviada correctamente.'
        ]);
    }
}