@extends('auth.index')

@section('titulo')
<title>Registrar Compra</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('app/assets_compras/style.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding:15px 25px; background:linear-gradient(to right,#2c7be5,#5e9cff); border-radius:8px;">
        <h1 style="color:#fff;">
            <i class="fa fa-truck-loading"></i> Registrar Compra
        </h1>
    </section>

    <div class="content">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('auth.compras.store') }}" method="POST" id="form-compra">
            @csrf

            {{-- DATOS DE LA COMPRA --}}
            <div class="form-section">
                <h5><i class="fa fa-file-invoice"></i> Datos de la Compra</h5>

                <div class="row">
                    <div class="form-group col-lg-4">
                        <label>Proveedor *</label>
                        <select name="id_proveedor" class="form-control select2" required>
                            <option value="">Seleccione proveedor...</option>
                            @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id_proveedor }}">
                                {{ $proveedor->razon_social }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-lg-3">
                        <label>Fecha de compra *</label>
                        <input type="date" name="fecha_compra" class="form-control"
                            value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group col-lg-3">
                        <label>Tipo de documento *</label>
                        <select name="tipo_documento" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="FACTURA">Factura</option>
                            <option value="BOLETA">Boleta</option>
                            <option value="GUIA">Guía</option>
                        </select>
                    </div>

                    <div class="form-group col-lg-2">
                        <label>N° Documento *</label>
                        <input type="text" name="numero_documento" class="form-control" required>
                    </div>

                    <div class="form-group col-lg-3">
                        <label>Método de pago *</label>
                        <select name="id_metodo_pago" class="form-control" required>
                            @foreach($metodosPago as $metodo)
                            <option value="{{ $metodo->id_metodo_pago }}">
                                {{ $metodo->descripcion }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-lg-9">
                        <label>Observación</label>
                        <input type="text" name="observacion" class="form-control">
                    </div>
                </div>
            </div>

            {{-- PRODUCTOS --}}
            <div class="form-section">
                <h5><i class="fa fa-box"></i> Productos Comprados</h5>

                <div class="row">
                    <div class="col-lg-7">
                        <input type="text" id="buscarProducto" class="form-control mb-2"
                            placeholder="Buscar producto...">

                        <div class="productos-pos" id="productosPos">
                            @foreach($productos as $producto)
                            <div class="producto-card"
                                data-id="{{ $producto->id_producto }}"
                                data-nombre="{{ $producto->descripcion }}">

                                <img src="{{ asset($producto->imagen) }}">

                                <div class="info">
                                    <h6>{{ $producto->descripcion }}</h6>
                                    <p class="stock">Stock actual: {{ $producto->stock }}</p>
                                </div>

                                <div class="acciones">
                                    <input type="number" class="cantidad" min="1" value="1">
                                    <input type="number" class="costo" min="0" step="0.01"
                                        placeholder="Costo">
                                    <button type="button" class="btn btn-add">Agregar</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <table class="table table-bordered" id="detalle-compra">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Costo</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <div class="totals-ticket">
                            <p>Subtotal: S/ <span id="subtotal">0.00</span></p>
                            <p>IGV (18%): S/ <span id="igv">0.00</span></p>
                            <p><strong>Total: S/ <span id="total">0.00</span></strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary btn-lg w-100">
                <i class="fa fa-save"></i> Registrar Compra
            </button>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('auth/js/compras/index.js') }}"></script>
@endsection
