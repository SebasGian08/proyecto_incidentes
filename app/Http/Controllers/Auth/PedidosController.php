<?php

namespace BolsaTrabajo\Http\Controllers\Auth;


use Illuminate\Support\Facades\Auth; // Importa la clase Auth
use Illuminate\Http\Request;
use BolsaTrabajo\User;
use BolsaTrabajo\MetodoPago;
use BolsaTrabajo\Ubigeo;
use BolsaTrabajo\Producto;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use PDF;

class PedidosController extends Controller
{
    public function index()
    {
        // Usuario autenticado
        $User = Auth::guard('web')->user();
        $userId = $User->id;

        // Traer todos los usuarios activos
        $users = User::where('estado', 1)
                    ->whereNull('deleted_at')
                    ->get();

        // Métodos de pago activos
        $metodosPago = MetodoPago::where('estado', 1)
                                ->whereNull('deleted_at')
                                ->get();

        // Ubigeos activos
        $ubigeos = Ubigeo::where('estado', 1)
                        ->whereNull('deleted_at')
                        ->get();

        // Productos activos con stock disponible y reservado
        $productos = Producto::where('estado', 1)
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($p) {

                $reservado = DB::table('stock_reservado')
                    ->where('id_producto', $p->id_producto)
                    ->whereIn('estado', ['RESERVADO', 'PREPARADO'])
                    ->sum('cantidad');

                $p->stock_reservado = $reservado;
                $p->stock_disponible = $p->stock - $reservado;

                return $p;
            });


        return view('auth.pedidos.index', compact('userId', 'users', 'metodosPago', 'ubigeos', 'productos'));
    }


    public function verlistado(){
        $User = Auth::guard('web')->user();
        $userId = $User->id;
        return view('auth.pedidos.listado' , compact('userId'));
    }

    public function gestiondepedidos(){
        $motorizados = User::where('profile_id', 7)
                   ->where('estado', 1)
                   ->whereNull('deleted_at')
                   ->get();
        return view('auth.pedidos.gestion', compact('motorizados'));
    }

    public function store(Request $request)
    {
        // ===============================
        // DEBUG INICIAL
        // ===============================
        //dd($request->productos);

        // ===============================
        // VALIDACIONES DEL FORMULARIO
        // ===============================
        $validator = Validator::make($request->all(), [
            'id_usuario' => 'required|exists:users,id',
            'razon_social' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'fecha_pedido' => 'required|date',
            'fecha_entrega' => 'required|date',
            'metodo_pago' => 'required|exists:metodo_pagos,id_metodo_pago',
            'direccion_envio' => 'required|string|max:255',
            'ubigeo_envio' => 'required|exists:ubigeos,id_ubigeo',
            'latitud_envio' => 'required|numeric|between:-90,90',
            'longitud_envio' => 'required|numeric|between:-180,180',


            // ===============================
            // VALIDACIÓN DE PRODUCTOS
            // ===============================
                'productos' => 'required|array|min:1',
                'productos.*.id_producto' => [
                    'required',
                    Rule::exists('productos', 'id_producto')
                        ->whereNull('deleted_at')
                ],

                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ===============================
        // VALIDACIÓN DE STOCK (NEGOCIO)
        // ===============================
        $productosIds = collect($request->productos)->pluck('id_producto');

        $productos = DB::table('productos')
            ->whereIn('id_producto', $productosIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id_producto');

        $reservas = DB::table('stock_reservado')
            ->whereIn('id_producto', $productosIds)
            ->where('estado', 'RESERVADO')
            ->select('id_producto', DB::raw('SUM(cantidad) as reservado'))
            ->groupBy('id_producto')
            ->pluck('reservado', 'id_producto');

        foreach ($request->productos as $p) {
            $prod = $productos[$p['id_producto']];
            $stockDisponible = $prod->stock - ($reservas[$p['id_producto']] ?? 0);
            if ($p['cantidad'] > $stockDisponible) {
                return back()->withErrors([
                    'productos' => "Stock insuficiente para {$prod->descripcion}"
                ]);
            }
        }



        // ===============================
        // TRANSACCIÓN
        // ===============================
        DB::beginTransaction();

        try {
            // ===============================
            // CÁLCULOS
            // ===============================
            $codigoPedido = 'PED-' . time();

            $subtotal = 0;
            foreach ($request->productos as $p) {
                $subtotal += $p['cantidad'] * $p['precio'];
            }

            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;

            // ===============================
            // REGISTRAR PEDIDO
            // ===============================
            $pedidoId = DB::table('pedidos')->insertGetId([
                'codigo_pedido' => $codigoPedido,
                'id_usuario' => $request->id_usuario,
                'nombre_cliente' => $request->razon_social,
                'direccion_cliente' => $request->direccion,
                'telefono_cliente' => $request->telefono,
                'fecha_pedido' => $request->fecha_pedido,
                'fecha_entrega' => $request->fecha_entrega,
                'id_metodo_pago' => $request->metodo_pago,
                'tipo_pedido' => $request->punto_llegada ?? 'cliente',
                'direccion_envio' => $request->direccion_envio,
                'ubigeo_envio' => $request->ubigeo_envio,
                'latitud_envio' => $request->latitud_envio,
                'longitud_envio' => $request->longitud_envio,
                'subtotal' => $subtotal,
                'igv' => $igv,
                'total' => $total,
                'observacion' => $request->referencia,
                'estado' => 1,
                'estado_pedido' => 'PENDIENTE',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ===============================
            // DETALLE DEL PEDIDO
            // ===============================
            foreach ($request->productos as $p) {
                DB::table('pedidos_detalle')->insert([
                    'id_pedido' => $pedidoId,
                    'id_producto' => $p['id_producto'],
                    'cantidad' => $p['cantidad'],
                    'precio_unitario' => $p['precio'],
                    'total' => $p['cantidad'] * $p['precio'],
                ]);

                // RESERVAR STOCK (NO DESCONTAR)
                DB::table('stock_reservado')->insert([
                    'id_producto' => $p['id_producto'],
                    'id_pedido'   => $pedidoId,
                    'cantidad'    => $p['cantidad'],
                    'estado'      => 'RESERVADO',
                    'created_at'  => now()
                ]);
            }


            // ===============================
            // SEGUIMIENTO INICIAL
            // ===============================
            DB::table('pedido_seguimiento')->insert([
                'id_pedido' => $pedidoId,
                'id_estado_seguimiento' => 1,
                'id_motorizado' => null,
                'id_usuario_registro' => auth()->id(),
                'comentario' => 'Pedido creado automáticamente.',
                'evidencia_chat' => 0,
                'evidencia_llamada_chat' => 0,
                'evidencia_entrega' => 0,
                'evidencia_guia' => 0,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('auth.pedidos')
                ->with('success', 'Pedido registrado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Ocurrió un error al registrar el pedido: ' . $e->getMessage());
        }
    }


    public function list_all(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;

        $query = DB::table('pedidos')
            ->leftJoin('ubigeos', 'ubigeos.id_ubigeo', '=', 'pedidos.ubigeo_envio')

            ->leftJoin(DB::raw('(
                SELECT 
                    ps1.id_pedido,
                    es.nombre AS estado_seguimiento,
                    ps1.comentario
                FROM pedido_seguimiento ps1
                JOIN estado_seguimiento es 
                    ON es.id_estado_seguimiento = ps1.id_estado_seguimiento
                WHERE ps1.deleted_at IS NULL
                AND ps1.id_seguimiento = (
                    SELECT MAX(ps2.id_seguimiento)
                    FROM pedido_seguimiento ps2
                    WHERE ps2.id_pedido = ps1.id_pedido
                        AND ps2.deleted_at IS NULL
                )
            ) AS seguimiento'), 'seguimiento.id_pedido', '=', 'pedidos.id_pedido')


            ->leftJoin(DB::raw('(
                SELECT 
                    pd.id_pedido,
                    GROUP_CONCAT(CONCAT(p.descripcion, " (", pd.cantidad, ")")) AS productos
                FROM pedidos_detalle pd
                JOIN productos p ON p.id_producto = pd.id_producto
                GROUP BY pd.id_pedido
            ) AS pdp'), 'pdp.id_pedido', '=', 'pedidos.id_pedido')

            ->select(
                'pedidos.id_pedido',
                'pedidos.codigo_pedido',
                'pedidos.nombre_cliente',
                'pedidos.fecha_entrega',
                'pedidos.fecha_pedido',
                'pedidos.telefono_cliente',
                'pedidos.total',

                'ubigeos.departamento',
                'ubigeos.provincia',
                'ubigeos.distrito',

                'seguimiento.estado_seguimiento',
                'seguimiento.comentario',
                'pdp.productos'
            )
            ->where('pedidos.id_usuario', $userId);


        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('pedidos.fecha_pedido', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }

        if ($request->filled('estado')) {
            $query->where('seguimiento.estado_seguimiento', $request->estado);
        }

        $pedidos = $query
            ->orderBy('pedidos.created_at', 'desc')
            ->get()
            ->map(function ($pedido) {


            $pedido->productos = collect(explode('|||', $pedido->productos ?? ''))
                    ->map(function ($p) {
                        preg_match('/^(.*)\s\((\d+)\)$/', trim($p), $m);
                        return [
                            'descripcion' => $m[1] ?? trim($p),
                            'cantidad' => $m[2] ?? 0
                        ];
            });


                return $pedido;
            });

        return response()->json(['data' => $pedidos]);
    }



    public function gestionList(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;

        $pedidos = DB::table('pedidos as p')
            ->leftJoin('users as u', 'u.id', '=', 'p.id_usuario')
            ->leftJoin('ubigeos as ub', 'ub.id_ubigeo', '=', 'p.ubigeo_envio')
            ->leftJoin(DB::raw('(SELECT pd.id_pedido,
                    GROUP_CONCAT(CONCAT(prod.descripcion, " (", pd.cantidad, ")")) as productos
                FROM pedidos_detalle pd
                JOIN productos prod ON pd.id_producto = prod.id_producto
                GROUP BY pd.id_pedido) as pdp'),
                'pdp.id_pedido', '=', 'p.id_pedido'
            )
            ->where('p.id_usuario', $userId)

            ->when($request->estado, function ($q) use ($request) {
                $q->where('p.estado_pedido', $request->estado);
            })

            ->when($request->fecha_inicio, function ($q) use ($request) {
                $q->whereDate('p.created_at', '>=', $request->fecha_inicio);
            })
            ->when($request->fecha_fin, function ($q) use ($request) {
                $q->whereDate('p.created_at', '<=', $request->fecha_fin);
            })

            ->select(
                'p.id_pedido',
                'p.codigo_pedido',
                'p.fecha_pedido',
                'p.fecha_entrega',
                'p.total',
                'p.estado_pedido',
                'u.nombres as nombre_usuario',
                'ub.departamento',
                'ub.provincia',
                'ub.distrito',
                'pdp.productos'
            )
            ->orderByDesc('p.created_at')
            ->get();

        return response()->json(['data' => $pedidos]);
    }



    public function gestionGet(Request $request)
    {
        $pedido = DB::table('pedidos as p')
            ->leftJoin('users as u', 'u.id', '=', 'p.id_usuario')
            ->leftJoin('ubigeos as ub', 'ub.id_ubigeo', '=', 'p.ubigeo_envio')
            ->where('p.id_pedido', $request->id_pedido)
            ->select(
                'p.id_pedido',
                'p.codigo_pedido',
                'p.nombre_cliente as cliente',
                'p.total',
                'p.fecha_pedido',
                'p.fecha_entrega',
                'u.nombres as nombre_usuario',
                'u.email as email_usuario',
                'u.telefono as telefono_usuario',
                'ub.departamento',
                'ub.provincia',
                'ub.distrito'
            )
            ->first();

        // Último seguimiento (solo info necesaria)
        $seguimiento = DB::table('pedido_seguimiento')
            ->where('id_pedido', $request->id_pedido)
            ->orderByDesc('id_seguimiento')
            ->first(['id_seguimiento','id_estado_seguimiento','id_motorizado','comentario']);

        // Detalles de productos (agregando código y opcionalmente imagen)
        $detalles = DB::table('pedidos_detalle as pd')
            ->join('productos as prod', 'prod.id_producto', '=', 'pd.id_producto')
            ->where('pd.id_pedido', $request->id_pedido)
            ->select(
                'prod.id_producto',
                'prod.codigo_producto',
                'prod.descripcion',
                'prod.imagen', // si quieres mostrar imagen en el detalle
                'pd.cantidad',
                'pd.precio_unitario' // si quieres subtotal
            )
            ->get();

        return response()->json([
            'data' => [
                'id_pedido' => $pedido->id_pedido,
                'codigo_pedido' => $pedido->codigo_pedido,
                'cliente' => $pedido->cliente,
                'departamento' => $pedido->departamento,
                'provincia' => $pedido->provincia,
                'distrito' => $pedido->distrito,
                'total' => $pedido->total,
                'fecha_pedido' => $pedido->fecha_pedido,
                'fecha_entrega' => $pedido->fecha_entrega,
                'seguimiento' => $seguimiento,
                'detalles' => $detalles
            ]
        ]);
    }



   public function gestionUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $pedido = DB::table('pedidos')
                ->where('id_pedido', $request->id_pedido)
                ->first();

            if (!$pedido) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido no encontrado'
                ], 404);
            }

            // BLOQUEO TOTAL si ya está ANULADO
            if ($pedido->estado_pedido === 'ANULADO') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este pedido ya está ANULADO y no puede modificarse'
                ], 400);
            }

            $estado = (int) $request->id_estado_seguimiento;
            $estadosPermitidos = [3, 4, 7]; // VALIDADO, REPROGRAMADO, ANULADO

            if (!in_array($estado, $estadosPermitidos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estado no permitido'
                ], 400);
            }

            if ($estado === 3 && $pedido->estado_pedido !== 'PENDIENTE') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden validar pedidos en estado PENDIENTE'
                ], 400);
            }

            $userId = Auth::guard('web')->user()->id;
            $estado = (int)$request->id_estado_seguimiento;

            $evidenciaChat = null;
            $evidenciaLlamada = null;
            $evidenciaSoporte = null;

            if ($request->hasFile('evidencia_chat')) {
                $evidenciaChat = $this->subirEvidenciaPublic($request->file('evidencia_chat'));
            }

            if ($request->hasFile('evidencia_llamada_chat')) {
                $evidenciaLlamada = $this->subirEvidenciaPublic($request->file('evidencia_llamada_chat'));
            }

            /* if ($request->hasFile('evidencia_guia')) {
                $evidenciaSoporte = $this->subirEvidenciaPublic($request->file('evidencia_guia'));
            } */

            DB::table('pedido_seguimiento')->insert([
                'id_pedido' => $request->id_pedido,
                'id_estado_seguimiento' => $request->id_estado_seguimiento,
                'id_motorizado' => $request->id_motorizado ?? null,
                'id_usuario_registro' => $userId,
                'comentario' => $request->comentario,

                'evidencia_chat' => $evidenciaChat,
                'evidencia_llamada_chat' => $evidenciaLlamada,
                /* 'evidencia_guia' => $evidenciaSoporte, */

                'created_at' => now(),
                'updated_at' => now()
            ]);


            // 2. Lógica según estado
            if ($estado === 3) { // VALIDADO

                DB::table('stock_reservado')
                    ->where('id_pedido', $request->id_pedido)
                    ->where('estado', 'RESERVADO')
                    ->update([
                        'estado' => 'PREPARADO',
                        'updated_at' => now()
                    ]);

                DB::table('pedidos')
                    ->where('id_pedido', $request->id_pedido)
                    ->update([
                        'estado_pedido' => 'VALIDADO',
                        'updated_at' => now()
                    ]);

            } elseif ($estado === 4) { // REPROGRAMADO

                DB::table('pedidos')
                    ->where('id_pedido', $request->id_pedido)
                    ->update([
                        /* 'fecha_entrega' => $request->fecha_entrega, */
                        'direccion_cliente' => $request->direccion,
                        'estado_pedido' => 'REPROGRAMADO',
                        'updated_at' => now()
                    ]);
            } elseif ($estado === 7) { // ANULADO

                // 1. Liberar stock reservado
                DB::table('stock_reservado')
                    ->where('id_pedido', $request->id_pedido)
                    ->whereIn('estado', ['RESERVADO', 'PREPARADO'])
                    ->update([
                        'estado' => 'LIBERADO',
                        'updated_at' => now()
                    ]);

                // 2. Cambiar estado del pedido
                DB::table('pedidos')
                    ->where('id_pedido', $request->id_pedido)
                    ->update([
                        'estado_pedido' => 'ANULADO',
                        'updated_at' => now()
                    ]);
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar pedido',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function descargarGuia($id_pedido)
    {
        $pedido = DB::table('pedidos as p')
            ->leftJoin('users as u', 'u.id', '=', 'p.id_usuario')
            ->leftJoin('ubigeos as ub', 'ub.id_ubigeo', '=', 'p.ubigeo_envio')
            ->where('p.id_pedido', $id_pedido)
            ->select(
                'p.id_pedido',
                'p.codigo_pedido',
                'p.nombre_cliente',
                'p.direccion_cliente',
                'p.telefono_cliente',
                'p.fecha_entrega',
                'p.direccion_envio',
                'ub.departamento',
                'ub.provincia',
                'ub.distrito',
                'p.total'
            )
            ->first();

        $detalles = DB::table('pedidos_detalle as pd')
            ->join('productos as prod', 'prod.id_producto', '=', 'pd.id_producto')
            ->where('pd.id_pedido', $id_pedido)
            ->select(
                'prod.codigo_producto',
                'prod.descripcion',
                'pd.cantidad',
                'pd.precio_unitario'
            )
            ->get();

        // 📅 Formato seguro para nombre de archivo
        $fecha = \Carbon\Carbon::parse($pedido->fecha_entrega)->format('Ymd');

        $pdf = PDF::loadView('auth.pedidos.guia', compact('pedido', 'detalles'));

        return $pdf->download("Guia_{$pedido->codigo_pedido}_{$fecha}.pdf");
    }


    /* public function entregar(Request $request)
    {
        $idPedido = $request->id_pedido;

        DB::beginTransaction();
        try {

            // 1. Cambiar estado del pedido a ENTREGADO
            DB::table('pedidos')
                ->where('id_pedido', $idPedido)
                ->update([
                    'estado_pedido' => 'ENTREGADO',
                    'updated_at' => now()
                ]);

            // 2. Obtener reservas preparadas
            $reservas = DB::table('stock_reservado')
                ->where('id_pedido', $idPedido)
                ->where('estado', 'PREPARADO')
                ->get();

            foreach ($reservas as $r) {

                $producto = DB::table('productos')
                    ->where('id_producto', $r->id_producto)
                    ->lockForUpdate()
                    ->first();

                $stockAnterior = $producto->stock;
                $stockNuevo = $stockAnterior - $r->cantidad;

                if ($stockNuevo < 0) {
                    throw new \Exception("Stock insuficiente para el producto: {$producto->descripcion}");
                }

                // Actualizar stock real
                DB::table('productos')
                    ->where('id_producto', $r->id_producto)
                    ->update(['stock' => $stockNuevo]);

                // Kardex de SALIDA
                DB::table('kardex')->insert([
                    'id_producto' => $r->id_producto,
                    'fecha_movimiento' => now(),
                    'tipo_movimiento' => 'S',
                    'motivo' => 'ENTREGA PEDIDO',
                    'id_origen' => $idPedido,
                    'cantidad' => $r->cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockNuevo,
                    'costo_unitario' => 0,
                    'costo_total' => 0,
                ]);
            }

            // 3. Confirmar reservas
            DB::table('stock_reservado')
                ->where('id_pedido', $idPedido)
                ->where('estado', 'PREPARADO')
                ->update(['estado' => 'CONFIRMADO', 'updated_at' => now()]);

            // 4. Seguimiento
            DB::table('pedido_seguimiento')->insert([
                'id_pedido' => $idPedido,
                'id_estado_seguimiento' => 6,
                'id_usuario_registro' => auth()->id(),
                'comentario' => 'Pedido entregado y stock descontado.',
                'evidencia_entrega' => 1,
                'created_at' => now(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Pedido entregado correctamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    } */

    public function entregar(Request $request)
    {
        $idPedido = $request->id_pedido;

        DB::beginTransaction();

        try {

            $rutaGuia = null;

            if ($request->hasFile('evidencia_guia')) {
                $rutaGuia = $this->subirEvidenciaPublic(
                    $request->file('evidencia_guia')
                );
            }

            // 1. Cambiar estado del pedido
            DB::table('pedidos')
                ->where('id_pedido', $idPedido)
                ->update([
                    'estado_pedido' => 'ENTREGADO',
                    'updated_at' => now()
                ]);

            // 2. Reservas
            $reservas = DB::table('stock_reservado')
                ->where('id_pedido', $idPedido)
                ->where('estado', 'PREPARADO')
                ->get();

            foreach ($reservas as $r) {

                $producto = DB::table('productos')
                    ->where('id_producto', $r->id_producto)
                    ->lockForUpdate()
                    ->first();

                $stockNuevo = $producto->stock - $r->cantidad;

                if ($stockNuevo < 0) {
                    throw new \Exception(
                        "Stock insuficiente: {$producto->descripcion}"
                    );
                }

                DB::table('productos')
                    ->where('id_producto', $r->id_producto)
                    ->update(['stock' => $stockNuevo]);

                DB::table('kardex')->insert([
                    'id_producto' => $r->id_producto,
                    'fecha_movimiento' => now(),
                    'id_tipo_movimiento' => 2, //SALIDA_ENTREGA
                    'motivo' => 'ENTREGA PEDIDO',
                    'id_origen' => $idPedido,
                    'cantidad' => $r->cantidad,
                    'stock_anterior' => $producto->stock,
                    'stock_nuevo' => $stockNuevo,
                    'costo_unitario' => 0,
                    'costo_total' => 0,
                ]);
            }

            // 3. Confirmar reservas
            DB::table('stock_reservado')
                ->where('id_pedido', $idPedido)
                ->where('estado', 'PREPARADO')
                ->update([
                    'estado' => 'CONFIRMADO',
                    'updated_at' => now()
                ]);

            // 4. Seguimiento
            DB::table('pedido_seguimiento')->insert([
                'id_pedido' => $idPedido,
                'id_estado_seguimiento' => 6, // ENTREGADO
                'id_usuario_registro' => auth()->id(),
                'comentario' => 'Pedido entregado.',
                'evidencia_guia' => $rutaGuia,
                'created_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pedido entregado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function stockActualizado()
    {
        $productos = Producto::where('estado', 1)
            ->whereNull('deleted_at')
            ->get()
            ->map(function($p) {
                $reservado = DB::table('stock_reservado')
                    ->where('id_producto', $p->id_producto)
                    ->whereIn('estado', ['RESERVADO', 'PREPARADO'])
                    ->sum('cantidad');

                $p->stock_disponible = $p->stock - $reservado;
                $p->stock_reservado = $reservado;
                return $p;
            });

        return response()->json($productos);
    }

    public function seguimiento(Request $request)
    {
        return DB::table('pedido_seguimiento as ps')
            ->join('estado_seguimiento as es', 'es.id_estado_seguimiento', '=', 'ps.id_estado_seguimiento')
            ->where('ps.id_pedido', $request->id_pedido)
            ->whereNull('ps.deleted_at')
            ->orderBy('ps.created_at', 'asc')
            ->select(
                'ps.id_seguimiento',
                'es.nombre as estado',
                'ps.comentario',
                'ps.evidencia_chat',
                'ps.evidencia_llamada_chat',
                'ps.evidencia_entrega',
                'ps.evidencia_guia',
                DB::raw("DATE_FORMAT(ps.created_at, '%d/%m/%Y %H:%i') as created_at")
            )
            ->get();
    }

   private function subirEvidenciaPublic($file)
    {
        $nombre = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/evidencias'), $nombre);


        return '/uploads/evidencias/' . $nombre;
    }


}