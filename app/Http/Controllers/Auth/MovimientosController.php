<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientosController extends Controller
{
    // =========================
    // LISTADO GENERAL
    // =========================
    public function index()
    {
        $productos = DB::table('productos')->select('id_producto', 'descripcion')->get();
        $tipos = DB::table('tipo_movimiento_kardex')->get();
        $motivos = ['COMPRA', 'DESPACHO PEDIDO', 'DEVOLUCION', 'AJUSTE'];

        return view('auth.movimientos.index', compact('productos', 'tipos', 'motivos'));
    }

    public function list_all(Request $request)
    {
        $query = DB::table('kardex')
            ->join('productos', 'kardex.id_producto', '=', 'productos.id_producto')
            ->join('tipo_movimiento_kardex', 'kardex.id_tipo_movimiento', '=', 'tipo_movimiento_kardex.id_tipo_movimiento')
            ->select(
                'kardex.id_kardex',
                'productos.descripcion as producto',
                'tipo_movimiento_kardex.nombre as tipo_movimiento',
                'tipo_movimiento_kardex.codigo',
                'kardex.motivo',
                'kardex.fecha_movimiento',
                'kardex.cantidad',
                'kardex.stock_anterior',
                'kardex.stock_nuevo',
                'kardex.costo_unitario',
                'kardex.costo_total'
            );

        if ($request->filled('fecha_inicio')) $query->whereDate('kardex.fecha_movimiento', '>=', $request->fecha_inicio);
        if ($request->filled('fecha_fin')) $query->whereDate('kardex.fecha_movimiento', '<=', $request->fecha_fin);
        if ($request->filled('producto_id')) $query->where('kardex.id_producto', $request->producto_id);
        if ($request->filled('id_tipo_movimiento')) $query->where('kardex.id_tipo_movimiento', $request->id_tipo_movimiento);
        if ($request->filled('motivo')) $query->where('kardex.motivo', $request->motivo);

        $movimientos = $query->orderBy('kardex.fecha_movimiento', 'desc')->get();
        return response()->json(['data' => $movimientos]);
    }

    // =========================
    // DEVOLUCIONES
    // =========================
    public function devolucionesCreate()
    {
        $productos = DB::table('productos')->select('id_producto', 'descripcion', 'stock','precio_compra')->get();
        return view('auth.movimientos.create_devolucion', compact('productos'));
    }

    public function devolucionesStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $producto = DB::table('productos')->where('id_producto', $request->id_producto)->lockForUpdate()->first();

            if (!$producto) {
                throw new \Exception("Producto no encontrado");
            }

            $stockAnterior = $producto->stock;
            $cantidad = $request->cantidad;
            $costoUnitario = $request->costo_unitario;

            // Determinar tipo de movimiento y stock
            switch ($request->motivo) {
                case 'DEVOLUCION_CLIENTE':
                    $idTipoMovimiento = 9; // Entrada
                    $stockNuevo = $stockAnterior + $cantidad;
                    break;

                case 'DEVOLUCION_PROVEEDOR':
                    $idTipoMovimiento = 10; // Salida
                    if ($stockAnterior < $cantidad) throw new \Exception("Stock insuficiente para devolución al proveedor");
                    $stockNuevo = $stockAnterior - $cantidad;
                    break;

                case 'ROTO':
                    $idTipoMovimiento = 7; // Salida
                    if ($stockAnterior < $cantidad) throw new \Exception("Stock insuficiente para marcar como roto");
                    $stockNuevo = $stockAnterior - $cantidad;
                    break;

                case 'ERROR_ENTREGA':
                    $idTipoMovimiento = 8; // Salida
                    if ($stockAnterior < $cantidad) throw new \Exception("Stock insuficiente para error de entrega");
                    $stockNuevo = $stockAnterior - $cantidad;
                    break;

                case 'MERMA':
                    $idTipoMovimiento = 6; // Salida
                    if ($stockAnterior < $cantidad) throw new \Exception("Stock insuficiente para merma");
                    $stockNuevo = $stockAnterior - $cantidad;
                    break;

                default:
                    throw new \Exception("Motivo de movimiento inválido");
            }

            // Insertar movimiento en kardex
            DB::table('kardex')->insert([
                'id_producto' => $producto->id_producto,
                'id_tipo_movimiento' => $idTipoMovimiento,
                'fecha_movimiento' => now(),
                'motivo' => $request->motivo,
                'id_origen' => null,
                'cantidad' => $cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockNuevo,
                'costo_unitario' => $costoUnitario,
                'costo_total' => $cantidad * $costoUnitario
            ]);

            // Actualizar stock del producto
            DB::table('productos')->where('id_producto', $producto->id_producto)->update(['stock' => $stockNuevo]);

            DB::commit();
            return redirect()->route('auth.devoluciones.create')->with('success', 'Movimiento registrado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }


    // =========================
    // AJUSTES DE CUADRE
    // =========================
    public function ajustesCreate()
    {
        $productos = DB::table('productos')->select('id_producto', 'descripcion', 'stock')->get();
        return view('auth.movimientos.create_ajuste', compact('productos'));
    }

    public function ajustesStore(Request $request)
    {
        DB::beginTransaction();
        try {

            $producto = DB::table('productos')
                ->where('id_producto', $request->id_producto)
                ->lockForUpdate()
                ->first();

            if (!$producto) {
                throw new \Exception("Producto no encontrado");
            }

            $stockAnterior = $producto->stock;
            $stockNuevo = $request->nuevo_stock;

            if ($stockAnterior == $stockNuevo) {
                throw new \Exception("No hay diferencia de stock para ajustar.");
            }

            $diferencia = $stockNuevo - $stockAnterior;
            $cantidadMovimiento = abs($diferencia);

            // Determinar tipo de ajuste automáticamente
            if ($diferencia > 0) {
                $idTipoMovimiento = 11; // AJUSTE_POSITIVO
            } else {
                $idTipoMovimiento = 12; // AJUSTE_NEGATIVO
            }

            $costoUnitario = $producto->precio_compra;

            DB::table('kardex')->insert([
                'id_producto' => $producto->id_producto,
                'id_tipo_movimiento' => $idTipoMovimiento,
                'fecha_movimiento' => now(),
                'motivo' => 'AJUSTE DE CUADRE',
                'id_origen' => null,
                'cantidad' => $cantidadMovimiento,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockNuevo,
                'costo_unitario' => $costoUnitario,
                'costo_total' => $cantidadMovimiento * $costoUnitario
            ]);

            DB::table('productos')
                ->where('id_producto', $producto->id_producto)
                ->update([
                    'stock' => $stockNuevo
                ]);

            DB::commit();

            return redirect()
                ->route('auth.ajustes.create')
                ->with('success', 'Ajuste de cuadre registrado correctamente');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

}