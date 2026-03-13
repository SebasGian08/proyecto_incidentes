<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

// EXPORTS
use BolsaTrabajo\RotacionInventarioExport;
use BolsaTrabajo\StockCriticoExport;
use BolsaTrabajo\KardexValorizadoExport;
use BolsaTrabajo\MargenProductoExport;

class ReportesController extends Controller
{
    /* =======================
       VISTA PRINCIPAL
       ======================= */
    public function index()
    {
        return view('auth.reportes.index');
    }

    /* =======================
       REPORTES JSON (GRÁFICOS)
       ======================= */

    public function rotacionInventario()
    {
        $data = DB::select('CALL sp_reporte_rotacion_inventario()');
        return response()->json(['data' => $data]);
    }

    public function stockCritico()
    {
        $data = DB::select('CALL sp_reporte_stock_critico()');
        return response()->json(['data' => $data]);
    }

    public function kardexValorizado()
    {
        $data = DB::select('CALL sp_reporte_kardex_valorizado()');
        return response()->json(['data' => $data]);
    }

    public function margenProducto()
    {
        $data = DB::select('CALL sp_reporte_margen_producto()');
        return response()->json(['data' => $data]);
    }

    /* =======================
       EXPORTACIÓN A EXCEL
       ======================= */

    public function exportarExcel(Request $request, $tipo)
    {
        $inicio = $request->inicio;
        $fin    = $request->fin;
        $tipoMovimiento = $request->tipo_movimiento ?? null; // nuevo parámetro opcional

        if (empty($inicio) || empty($fin)) {
            abort(400, 'Debe seleccionar un rango de fechas');
        }

        switch ($tipo) {

            case 'inventario':
                return Excel::download(
                    new RotacionInventarioExport($inicio, $fin),
                    'reporte_inventario.xlsx'
                );

            case 'kardex':
                return Excel::download(
                    new KardexValorizadoExport($inicio, $fin, $tipoMovimiento),
                    'reporte_kardex.xlsx'
                );

            case 'margen':
                return Excel::download(
                    new MargenProductoExport($inicio, $fin),
                    'reporte_rentabilidad.xlsx'
                );

            case 'sin-rotacion':
                return Excel::download(
                    new StockCriticoExport($inicio, $fin),
                    'reporte_sin_rotacion.xlsx'
                );

            default:
                abort(404);
        }
    }

}