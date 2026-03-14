<?php

namespace BolsaTrabajo\Http\Controllers\App;

use BolsaTrabajo\User;
use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use BolsaTrabajo\TicketsIncidente;

class IncidenteController extends Controller
{
    public function index()
    {
        return view('app.incidentes.create');
    }

    public function store(Request $request)
    {
        $status = false;

        // Validaciones
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'severidad' => 'required|string|exists:maestro_severidad,nombre',
            'estado' => 'required|string|exists:maestro_estado_ticket,nombre',
            'activo_id' => 'nullable|integer|exists:activos_ti,id',
            'usuario_reporta_id' => 'nullable|integer|exists:users,id',
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
        $incidente->usuario_reporta_id = $request->usuario_reporta_id;
        $incidente->tecnico_asignado_id = $request->tecnico_asignado_id;

        // Guardar incidente
        if ($incidente->save()) {
            $status = true;
        }

        return response()->json([
            'Success' => $status,
            'Message' => $status 
                ? 'Incidente registrado correctamente.' 
                : 'Error al registrar el incidente.'
        ]);
    }
}