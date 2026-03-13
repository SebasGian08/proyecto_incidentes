<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use BolsaTrabajo\Producto;
use BolsaTrabajo\ProductoMarca;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductosController extends Controller
{
    public function index()
    {
        $productos = Producto::with('marca')
            ->whereNull('deleted_at')
            ->get();

        return view('auth.productos.index', compact('productos'));
    }

    public function list_all()
    {
        $productos = Producto::whereNull('deleted_at')->get();

        $data = $productos->map(function($producto){
            return [
                'id_producto' => $producto->id_producto,
                'descripcion' => $producto->descripcion,
                'precio_venta' => $producto->precio_venta,
                'stock' => $producto->stock,
                'estado' => $producto->estado,
                'acciones' => '
                    <button class="btn btn-sm btn-primary btn-editar" data-id="'.$producto->id.'">Editar</button>
                    <button class="btn btn-sm btn-danger btn-eliminar" data-id="'.$producto->id.'">Eliminar</button>'
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $status = false;

        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'id_producto' => 'nullable|integer',
            'id_producto_marca' => 'required|integer|exists:productos_marca,id_producto_marca',
            'codigo_producto' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'estado' => 'required|in:1,2'
        ]);

        // Buscar o crear el producto
        $producto = ($request->id_producto && $request->id_producto != 0)
            ? Producto::findOrFail($request->id_producto)
            : new Producto();


        // Asignar campos
        $producto->id_producto_marca = $request->id_producto_marca;
        $producto->codigo_producto = $request->codigo_producto;
        $producto->descripcion = $request->descripcion;
        $producto->precio_compra = $request->precio_compra;
        $producto->precio_venta = $request->precio_venta;
        $producto->stock = $request->stock;
        $producto->estado = $request->estado;

        // Manejar la imagen si se sube una nueva
        if ($request->hasFile('imagen')) {
            if ($producto->imagen && file_exists(public_path($producto->imagen))) {
                unlink(public_path($producto->imagen));
            }

            $file = $request->file('imagen');
            $fileName = uniqid('PROD_') . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/productos/';
            $file->move(public_path($filePath), $fileName);

            $producto->imagen = $filePath . $fileName;
        }

        // Guardar el producto
        if ($producto->save()) {
            $status = true;
        }

        // Respuesta final
        return response()->json([
            'Success' => $status,
            'Errors' => $validator->errors()
        ]);
    }   



    public function delete(Request $request)
    {
        $producto = Producto::find($request->id);
        if($producto){
            $producto->delete();
            return response()->json(['success'=>true]);
        }
        return response()->json(['success'=>false]);
    }


    public function partialView($id = null)
    {
        $Producto = $id ? Producto::find($id) : null;
        $Marcas = ProductoMarca::whereNull('deleted_at')->get();

        return view('auth.productos._Mantenimiento', compact('Producto', 'Marcas'));
    }
}