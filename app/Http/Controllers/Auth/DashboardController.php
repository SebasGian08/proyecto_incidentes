<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $fechaDesde = $request->input('fecha_desde', "2000-01-01");
        $fechaHasta = $request->input('fecha_hasta', Carbon::now()->addDay()->format('Y-m-d'));

        $incidenciasPorEstado = $this->getIncidenciasPorEstado($fechaDesde, $fechaHasta);
        $incidenciasResumen = $this->getIncidenciasResumen($fechaDesde, $fechaHasta);
        $mttr = $this->getMTTR($fechaDesde, $fechaHasta);
        $incidenciasSLA = $this->getIncidenciasDentroSLA($fechaDesde, $fechaHasta);
        $satisfaccion = $this->getSatisfaccion($fechaDesde, $fechaHasta);

        return view('auth.dashboard.index', compact(
            'incidenciasPorEstado',
            'incidenciasResumen',
            'mttr',
            'incidenciasSLA',
            'satisfaccion',
            'fechaDesde',
            'fechaHasta'
        ));
    }

    private function getIncidenciasPorEstado($fecha_desde, $fecha_hasta)
    {
        $data = DB::table('tickets_incidentes as t')
            ->join('maestro_estado_ticket as e', 't.estado_id', '=', 'e.id')
            ->whereBetween('t.created_at', [$fecha_desde, $fecha_hasta])
            ->whereNull('t.deleted_at')
            ->select(
                'e.nombre as estado',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('e.nombre')
            ->get();

        $series = [];

        foreach ($data as $item) {
            $series[] = [
                'name' => $item->estado,
                'y' => (int) $item->total
            ];
        }

        return $series;
    }

    private function getIncidenciasResumen($fecha_desde, $fecha_hasta)
    {
        $data = DB::table('tickets_incidentes as t')
            ->join('maestro_estado_ticket as e', 't.estado_id', '=', 'e.id')
            ->whereBetween('t.created_at', [$fecha_desde, $fecha_hasta])
            ->whereNull('t.deleted_at')
            ->select(
                'e.nombre as estado',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('e.nombre')
            ->get();

        $resueltas = 0;
        $pendientes = 0;

        foreach ($data as $item) {
            if (strtolower($item->estado) == 'cerrado') {
                $resueltas += $item->total;
            } else {
                $pendientes += $item->total;
            }
        }

        return [
            ['name' => 'Resueltas', 'y' => $resueltas],
            ['name' => 'Pendientes', 'y' => $pendientes]
        ];
    }

    private function getMTTR($fecha_desde, $fecha_hasta)
    {
        $data = DB::table('tickets_incidentes as t')
            ->whereBetween('t.created_at', [$fecha_desde, $fecha_hasta])
            ->whereNotNull('t.fecha_cierre')
            ->whereNull('t.deleted_at')
            ->select(
                DB::raw("DATE_FORMAT(t.created_at, '%Y-%m') as periodo"),
                DB::raw("AVG(TIMESTAMPDIFF(MINUTE, t.created_at, t.fecha_cierre)) as promedio_minutos")
            )
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();

        return [
            'categories' => $data->pluck('periodo')->toArray(),
            'series' => $data->pluck('promedio_minutos')->map(function($m) {
                return $m ? round($m, 2) : 0;
            })->toArray()
        ];
    }

    private function getIncidenciasDentroSLA($fecha_desde, $fecha_hasta)
    {
        $data = DB::table('tickets_incidentes as t')
            ->join('maestro_severidad as s', 't.severidad_id', '=', 's.id')
            ->where('t.estado_id', 3) // Cerrado
            ->whereNull('t.deleted_at')
            ->whereNotNull('t.fecha_cierre')
            ->whereBetween('t.created_at', [$fecha_desde, $fecha_hasta])
            ->select(
                DB::raw("DATE_FORMAT(t.created_at, '%Y-%m') as periodo"),
                DB::raw("COUNT(*) as total_cerradas"),
                DB::raw("SUM(CASE WHEN TIMESTAMPDIFF(MINUTE, t.created_at, t.fecha_cierre) <= s.sla_minutos THEN 1 ELSE 0 END) as dentro_sla")
            )
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();

        $categories = [];
        $series = [];

        foreach ($data as $row) {
            $categories[] = $row->periodo;
            $porcentaje = $row->total_cerradas > 0 ? round(($row->dentro_sla / $row->total_cerradas) * 100, 2) : 0;
            $series[] = $porcentaje;
        }

        return [
            'categories' => $categories,
            'series' => $series
        ];
    }
    private function getSatisfaccion($fecha_desde, $fecha_hasta)
    {
        $data = DB::table('incidente_calificaciones as c')
            ->join('tickets_incidentes as t', 'c.incidente_id', '=', 't.id')
            ->whereBetween('t.created_at', [$fecha_desde, $fecha_hasta])
            ->select(
                DB::raw("DATE_FORMAT(t.created_at, '%Y-%m') as periodo"),
                DB::raw("SUM(c.rating) as total_puntos"),
                DB::raw("COUNT(c.id) as total_encuestas")
            )
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();

        $categories = [];
        $series = [];

        foreach ($data as $row) {
            $categories[] = $row->periodo;
            $satisfaccion = $row->total_encuestas > 0 ? round(($row->total_puntos / ($row->total_encuestas * 5)) * 100, 2) : 0;
            $series[] = $satisfaccion;
        }

        return [
            'categories' => $categories,
            'series' => $series
        ];
    }
}