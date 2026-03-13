<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use BolsaTrabajo\Models\Compra;
use BolsaTrabajo\Models\Proveedor;
use BolsaTrabajo\Models\Producto;
use BolsaTrabajo\Models\MetodoPago;

class ComprasController extends Controller
{
    public function index()
    {
        $proveedores = DB::table('proveedor')->where('estado',1)->get();
        $productos   = DB::table('productos')->where('estado',1)->get();
        $metodosPago = DB::table('metodo_pagos')->where('estado',1)->get();

        return view('auth.compras.create', compact(
            'proveedores',
            'productos',
            'metodosPago'
        ));
    }


    public function edit($id)
    {

        $compra = DB::table('compra')
        ->select(
            'id_compra',
            'id_proveedor',
            'fecha_compra',
            'tipo_documento',
            'numero_documento',
            'id_metodo_pago',
            'observacion',
            'subtotal',
            'igv',
            'total'
        )
        ->where('id_compra',$id)
        ->first();

        $detalle = DB::table('compra_detalle')
        ->join('productos','productos.id_producto','=','compra_detalle.id_producto')
        ->where('compra_detalle.id_compra',$id)
        ->select(
            'compra_detalle.*',
            'productos.descripcion'
        )
        ->get();

        $proveedores = DB::table('proveedor')->where('estado',1)->get();
        $productos   = DB::table('productos')->where('estado',1)->get();
        $metodosPago = DB::table('metodo_pagos')->where('estado',1)->get();

        return view('auth.compras.create', compact(
            'compra',
            'detalle',
            'proveedores',
            'productos',
            'metodosPago'
        ));
    }

    public function store(Request $request)
    {

        $request->validate([
            'id_proveedor' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
            'fecha_compra' => 'required|date',
            'id_metodo_pago' => 'required',
            'subtotal' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',

            'detalle' => 'required|array|min:1',
            'detalle.*.id_producto' => 'required|exists:productos,id_producto',
            'detalle.*.cantidad' => 'required|numeric|min:1',
            'detalle.*.costo' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {

            $idCompra = DB::table('compra')->insertGetId([
                'id_proveedor'     => $request->id_proveedor,
                'tipo_documento'   => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'fecha_compra'     => $request->fecha_compra,
                'id_metodo_pago'   => $request->id_metodo_pago,
                'subtotal'         => $request->subtotal,
                'igv'              => $request->igv,
                'total'            => $request->total,
                'observacion'      => $request->observacion,
                'estado'           => 1,
                'created_at'       => now()
            ]);

            foreach ($request->detalle as $item){

                $id_producto = $item['id_producto'];
                $cantidad = $item['cantidad'];
                $costo = $item['costo'];

                $producto = DB::table('productos')
                    ->where('id_producto',$id_producto)
                    ->lockForUpdate()
                    ->first();

                $stockAnterior = $producto->stock;
                $stockNuevo = $stockAnterior + $cantidad;

                $precioAnterior = $producto->precio_compra ?? 0;
                $cantidadAnterior = $stockAnterior;

                $precioNuevo = round(
                    (($precioAnterior * $cantidadAnterior) + ($costo * $cantidad)) 
                    / ($cantidadAnterior + $cantidad), 2
                );

                DB::table('compra_detalle')->insert([
                    'id_compra'=>$idCompra,
                    'id_producto'=>$id_producto,
                    'cantidad'=>$cantidad,
                    'costo_unitario'=>$costo,
                    'subtotal'=>$cantidad*$costo
                ]);

                DB::table('kardex')->insert([
                    'id_producto'=>$id_producto,
                    'fecha_movimiento'=>now(),
                    'id_tipo_movimiento'=>1,
                    'motivo'=>'COMPRA',
                    'id_origen'=>$idCompra,
                    'cantidad'=>$cantidad,
                    'stock_anterior'=>$stockAnterior,
                    'stock_nuevo'=>$stockNuevo,
                    'costo_unitario'=>$costo,
                    'costo_total'=>$cantidad*$costo
                ]);

                DB::table('productos')
                    ->where('id_producto',$id_producto)
                    ->update([
                        'stock'=>$stockNuevo,
                        'precio_compra'=>$precioNuevo
                    ]);
            }

            DB::commit();

            return redirect()->back()->with('success','Compra registrada correctamente');

        } 
        catch(\Exception $e){

            DB::rollBack();

            return back()->withErrors($e->getMessage());

        }

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_proveedor' => 'required',
            'fecha_compra' => 'required|date',
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
            'id_metodo_pago' => 'required',

            'id_producto' => 'required|array|min:1',
            'cantidad' => 'required|array|min:1',
            'costo' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            // Recuperar y revertir stock antiguo
            $detallesAntiguos = DB::table('compra_detalle')
                ->where('id_compra', $id)
                ->get();

            foreach ($detallesAntiguos as $detalle) {
                DB::table('productos')
                    ->where('id_producto', $detalle->id_producto)
                    ->decrement('stock', $detalle->cantidad);
            }

            // Borrar kardex y detalle antiguo
            DB::table('kardex')->where('id_origen', $id)->delete();
            DB::table('compra_detalle')->where('id_compra', $id)->delete();

            // Actualizar datos de la compra
            DB::table('compra')->where('id_compra', $id)->update([
                'id_proveedor' => $request->id_proveedor,
                'fecha_compra' => $request->fecha_compra,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'id_metodo_pago' => $request->id_metodo_pago,
                'observacion' => $request->observacion,
                'subtotal' => $request->subtotal,
                'igv' => $request->igv,
                'total' => $request->total
            ]);

            // Reconstruir array de detalle desde los arrays del form
            $detalles = [];
            for ($i = 0; $i < count($request->id_producto); $i++) {
                $detalles[] = [
                    'id_producto' => $request->id_producto[$i],
                    'cantidad' => $request->cantidad[$i],
                    'costo' => $request->costo[$i],
                ];
            }

            // Insertar detalle nuevo, actualizar stock y kardex
            foreach ($detalles as $item) {

                $id_producto = $item['id_producto'];
                $cantidad = $item['cantidad'];
                $costo = $item['costo'];

                // Insertar en compra_detalle
                DB::table('compra_detalle')->insert([
                    'id_compra' => $id,
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad,
                    'costo_unitario' => $costo,
                    'subtotal' => $cantidad * $costo
                ]);

                // Actualizar stock
                $producto = DB::table('productos')
                    ->where('id_producto', $id_producto)
                    ->lockForUpdate()
                    ->first();

                $stockAnterior = $producto->stock;
                $stockNuevo = $stockAnterior + $cantidad;

                $precioAnterior = $producto->precio_compra ?? 0;
                $cantidadAnterior = $stockAnterior;

                $precioNuevo = round(
                    (($precioAnterior * $cantidadAnterior) + ($costo * $cantidad)) 
                    / ($cantidadAnterior + $cantidad), 2
                );

                // Insertar en kardex
                DB::table('kardex')->insert([
                    'id_producto' => $id_producto,
                    'fecha_movimiento' => now(),
                    'id_tipo_movimiento' => 1,
                    'motivo' => 'COMPRA_EDITADA',
                    'id_origen' => $id,
                    'cantidad' => $cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockNuevo,
                    'costo_unitario' => $costo,
                    'costo_total' => $cantidad * $costo
                ]);

                // Actualizar stock y precio del producto
                DB::table('productos')
                    ->where('id_producto', $id_producto)
                    ->update([
                        'stock' => $stockNuevo,
                        'precio_compra' => $precioNuevo
                    ]);
            }

            DB::commit();

            return redirect()
                ->route('auth.compras.edit', $id)
                ->with('success', 'Compra actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    public function list_all(Request $request)
    {
        return response()->json([
            'data' => DB::table('compra')
                ->join('proveedor','proveedor.id_proveedor','=','compra.id_proveedor')
                ->select(
                    'compra.id_compra',
                    'compra.fecha_compra',
                    'compra.tipo_documento',
                    'compra.numero_documento',
                    'proveedor.razon_social',
                    'compra.total',
                    'compra.estado'
                )
                ->orderBy('compra.fecha_compra','desc')
                ->get()
        ]);
    }

    public function verlistado()
    {
        $User = Auth::guard('web')->user();
        $userId = $User->id;

        return view('auth.compras.listado', compact('userId'));
    }

    public function verCompra($id)
    {

        $compra = DB::table('compra')
        ->join('proveedor','proveedor.id_proveedor','=','compra.id_proveedor')
        ->select(
            'compra.*',
            'proveedor.razon_social'
        )
        ->where('compra.id_compra',$id)
        ->first();


        $detalle = DB::table('compra_detalle')
        ->join('productos','productos.id_producto','=','compra_detalle.id_producto')
        ->select(
            'productos.id_producto',
            'productos.descripcion',
            'compra_detalle.cantidad',
            'compra_detalle.costo_unitario',
            'compra_detalle.subtotal'
        )
        ->where('compra_detalle.id_compra',$id)
        ->get();


        return response()->json([
            'compra'=>$compra,
            'detalle'=>$detalle
        ]);
    }

    public function verKardex($id_producto)
    {

        $movimientos = DB::table('kardex')
        ->where('id_producto',$id_producto)
        ->orderBy('fecha_movimiento','desc')
        ->get();

        return response()->json([
            'movimientos'=>$movimientos
        ]);

    }

   public function delete(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();
        try {
            $detalles = DB::table('compra_detalle')
                ->where('id_compra', $id)
                ->get();

            foreach ($detalles as $detalle) {
                DB::table('productos')
                    ->where('id_producto', $detalle->id_producto)
                    ->decrement('stock', $detalle->cantidad);
            }

            DB::table('kardex')->where('id_origen', $id)->delete();
            DB::table('compra_detalle')->where('id_compra', $id)->delete();
            DB::table('compra')->where('id_compra', $id)->delete();

            DB::commit();

            return redirect()
                ->route('auth.compras.listado')
                ->with('success', 'Compra eliminada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }
}