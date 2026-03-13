@php
$esEditar = isset($compra);
@endphp

@extends('auth.index')

@section('titulo')
<title>Registrar Compra</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
                <i class="fa {{ $esEditar ? 'fa-edit' : 'fa-shopping-cart' }}"></i>
                {{ $esEditar ? 'Editar Compra' : 'Registrar Compra' }}
        </h1>
    </section>

    <div class="content">

        @if(session('success'))
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ $esEditar ? route('auth.compras.update', $compra->id_compra) : route('auth.compras.store') }}"
            method="POST" id="form-compra">

            @csrf

            @if($esEditar)
            @method('PUT')
            @endif
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
                            <option value="{{ $proveedor->id_proveedor }}"
                                {{ $esEditar && $compra->id_proveedor == $proveedor->id_proveedor ? 'selected' : '' }}>
                                {{ $proveedor->razon_social }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-lg-3">
                        <label>Fecha de compra *</label>
                        <input type="date" name="fecha_compra" class="form-control"
                            value="{{ $esEditar ? $compra->fecha_compra : date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group col-lg-3">
                        <label>Tipo de documento *</label>
                        <select name="tipo_documento" class="form-control" required>

                            <option value="">Seleccione...</option>
                            <option value="FACTURA"
                                {{ $esEditar && $compra->tipo_documento == 'FACTURA' ? 'selected' : '' }}>
                                Factura
                            </option>

                            <option value="BOLETA"
                                {{ $esEditar && $compra->tipo_documento == 'BOLETA' ? 'selected' : '' }}>
                                Boleta
                            </option>

                            <option value="GUIA" {{ $esEditar && $compra->tipo_documento == 'GUIA' ? 'selected' : '' }}>
                                Guía
                            </option>

                            <option value="NV" {{ $esEditar && $compra->tipo_documento == 'NV' ? 'selected' : '' }}>
                                Nota de Venta
                            </option>

                        </select>
                    </div>

                    <div class="form-group col-lg-2">
                        <label>N° Documento *</label>
                        <input type="text" name="numero_documento" class="form-control"
                            value="{{ $esEditar ? $compra->numero_documento : '' }}" required>
                    </div>

                    <div class="form-group col-lg-3">
                        <label>Método de pago *</label>
                        <select name="id_metodo_pago" class="form-control" required>
                            @foreach($metodosPago as $metodo)
                            <option value="{{ $metodo->id_metodo_pago }}"
                                {{ $esEditar && $compra->id_metodo_pago == $metodo->id_metodo_pago ? 'selected' : '' }}>
                                {{ $metodo->descripcion }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-lg-9">
                        <label>Observación</label>
                        <input type="text" name="observacion" class="form-control"
                            value="{{ $esEditar ? $compra->observacion : '' }}">
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
                            <div class="producto-card" data-id="{{ $producto->id_producto }}"
                                data-nombre="{{ $producto->descripcion }}">

                                <img src="{{ asset($producto->imagen) }}">

                                <div class="info">
                                    <h6>{{ $producto->descripcion }}</h6>
                                    <p class="stock">Stock actual: {{ $producto->stock }}</p>
                                </div>

                                <div class="acciones">
                                    <input type="number" class="cantidad" min="1" value="1">
                                    <input type="number" class="costo" min="0" step="0.01" placeholder="Costo">
                                    <button type="button" class="btn btn-add">Agregar</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-15">
                            <button type="button" class="btn btn-light-custom btn-sm" id="prevPage">
                                Anterior
                            </button>

                            <span id="pageInfo" class="mx-2"></span>

                            <button type="button" class="btn btn-light-custom btn-sm" id="nextPage">
                                Siguiente
                            </button>
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
                            <input type="hidden" name="subtotal" id="input-subtotal">
                            <input type="hidden" name="igv" id="input-igv">
                            <input type="hidden" name="total" id="input-total">

                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <button type="submit" class="btn btn-light-custom btn-lg" style="width: 100%;">
                    <i class="fa fa-save"></i>
                    {{ $esEditar ? 'Actualizar Compra' : 'Registrar Compra' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('auth/js/compras/index.js') }}"></script>
<script>
let productosEditar = @json($detalle ?? []);
</script>
<script src="{{ asset('auth/js/compras/editar.js') }}"></script>
@endsection