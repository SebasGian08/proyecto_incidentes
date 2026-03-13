<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class MotorizadoController extends Controller
{
    // Vista principal del motorizado
    public function index()
    {
        return view('auth.pedidos.motorizado');
    }

    // Listado de pedidos asignados al motorizado
    public function list(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;

        $pedidos = DB::table('pedidos as p')
            ->leftJoin(DB::raw('(SELECT pd.id_pedido,
                    GROUP_CONCAT(CONCAT(prod.descripcion, " (", pd.cantidad, ")")) as productos
                FROM pedidos_detalle pd
                JOIN productos prod ON pd.id_producto = prod.id_producto
                GROUP BY pd.id_pedido) as pdp'),
                'pdp.id_pedido', '=', 'p.id_pedido'
            )
            

            ->where('p.estado', 1)
            ->when($request->estado, function ($q) use ($request) {
                $q->whereRaw("TRIM(UPPER(p.estado_pedido)) = ?", [strtoupper($request->estado)]);
            }, function ($q) {
                $q->whereRaw("TRIM(UPPER(p.estado_pedido)) = ?", ['VALIDADO']);
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
                'p.latitud_envio',
                'p.longitud_envio',
                'p.nombre_cliente',
                'p.telefono_cliente',
                'p.direccion_cliente',
                'p.direccion_envio',
                'p.fecha_pedido',
                'p.fecha_entrega',
                'p.total',
                'p.estado_pedido',
                'p.observacion',
                'pdp.productos'
            )
            ->orderByDesc('p.created_at')
            ->get()
            ->map(function ($pedido) {
                $pedido->productos = collect(explode(',', $pedido->productos ?? ''))
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



    // Obtener detalle de un pedido para editar
    public function get(Request $request)
    {
        $pedido = DB::table('pedidos')
            ->where('id_pedido', $request->id_pedido)
            ->first();

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        return response()->json([
            'data' => [
                'id_pedido' => $pedido->id_pedido,
                'codigo_pedido' => $pedido->codigo_pedido,
                'cliente' => $pedido->nombre_cliente,
                'direccion_envio' => $pedido->direccion_envio,
                'fecha_entrega' => $pedido->fecha_entrega,
                'total' => $pedido->total
            ]
        ]);
    }

    // Actualizar pedido: reprogramar o marcar como entregado
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $userId = Auth::guard('web')->user()->id;

            $pedido = DB::table('pedidos')
                ->where('id_pedido', $request->id_pedido)
                ->lockForUpdate()
                ->first();

            if (!$pedido) {
                return response()->json(['success'=>false,'message'=>'Pedido no encontrado'],404);
            }

            // Actualizar campos permitidos para motorizado
            $updateData = [];
            if ($request->fecha_entrega) $updateData['fecha_entrega'] = $request->fecha_entrega;
            if ($request->direccion_envio) $updateData['direccion_envio'] = $request->direccion_envio;

            if(!empty($updateData)){
                $updateData['updated_at'] = now();
                DB::table('pedidos')->where('id_pedido', $request->id_pedido)->update($updateData);
            }

            // Registrar seguimiento
            DB::table('pedido_seguimiento')->insert([
                'id_pedido' => $request->id_pedido,
                'id_estado_seguimiento' => $request->id_estado_seguimiento ?? 7, // 7 = reprogramado por motorizado
                'id_motorizado' => $userId,
                'id_usuario_registro' => $userId,
                'comentario' => $request->comentario ?? 'Actualización por motorizado',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido actualizado correctamente'
            ]);

        } catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar pedido',
                'error' => $e->getMessage()
            ],500);
        }
    }


}