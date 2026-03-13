<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use BolsaTrabajo\Cliente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ClientesController extends Controller
{
    public function index()
    {
        $User = Auth::guard('web')->user();
        $userId = $User->id;

        return view('auth.clientes.index');
    }

    public function list()
    {
        $clientes = Cliente::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'data' => $clientes
        ]);
    }

    public function partialView($id)
    {
        $entity = null;

        if ($id != 0) {
            $entity = Cliente::where('id_cliente', $id)
                ->where('user_id', Auth::id())
                ->whereNull('deleted_at')
                ->first();
        }

        return view('auth.clientes._Mantenimiento', [
            'Entity' => $entity
        ]);
    }

    public function store(Request $request)
    {
        $status = false;

        $validator = Validator::make($request->all(), [
            'documento' => 'required|max:20',
            'nombres'   => 'required|max:150',
            'direccion' => 'nullable|max:255',
            'telefono'  => 'nullable|max:20',
            'email'     => 'nullable|email|max:150',
            'estado'    => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Success' => $status,
                'Errors'  => $validator->errors()
            ]);
        }

        if ($request->id_cliente && $request->id_cliente != 0) {
            $entity = Cliente::where('id_cliente', $request->id_cliente)
                ->where('user_id', Auth::id())
                ->whereNull('deleted_at')
                ->first();
        } else {
            $entity = new Cliente();
            $entity->user_id = Auth::id();
        }

        if (!$entity) {
            return response()->json([
                'Success' => false,
                'Errors'  => ['error' => ['Cliente no encontrado']]
            ]);
        }

        $entity->documento = $request->documento;
        $entity->nombres   = $request->nombres;
        $entity->direccion = $request->direccion;
        $entity->telefono  = $request->telefono;
        $entity->email     = $request->email;
        $entity->estado    = $request->estado;

        if ($entity->save()) {
            $status = true;
        }

        return response()->json([
            'Success' => $status,
            'Errors'  => []
        ]);
    }

    public function delete(Request $request)
    {
        $status = false;

        $entity = Cliente::where('id_cliente', $request->id)
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->first();

        if ($entity && $entity->delete()) {
            $status = true;
        }

        return response()->json([
            'Success' => $status
        ]);
    }

    public function search(Request $request)
    {
        $documento = $request->get('documento');

        $cliente = Cliente::where('user_id', Auth::id())
                    ->where('documento', $documento)
                    ->whereNull('deleted_at')
                    ->first();

        return response()->json([
            'cliente' => $cliente
        ]);
    }


}