@extends('auth.index')

@section('titulo')
<title>Devoluciones de Productos</title>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/listado.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="color: #fff;"><i class="fa fa-undo"></i> Devoluciones</h1>
    </section>

    <div class="content mt-3">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <!-- LISTADO DE PRODUCTOS -->
        <div class="form-section">
            <div class="card-body">
                <table id="tableProductosDevolucion" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Precio Compra</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $prod)
                        <tr>
                            <td>{{ $prod->id_producto }}</td>
                            <td>{{ $prod->descripcion }}</td>
                            <td>{{ $prod->stock }}</td>
                            <td>{{ number_format($prod->precio_compra ?? 0, 2) }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm btn-devolucion" data-id="{{ $prod->id_producto }}"
                                    data-stock="{{ $prod->stock }}" data-precio="{{ $prod->precio_compra ?? 0 }}"
                                    data-nombre="{{ $prod->descripcion }}">
                                    <i class="fa fa-undo"></i> Devolución
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- MODAL DE DEVOLUCIÓN -->
<div class="modal fade" id="modalDevolucion" tabindex="-1" aria-labelledby="modalDevolucionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formDevolucion" method="POST" action="{{ route('auth.devoluciones.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title">
                        Registrar Devolución
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_producto" id="modalProductoId">

                    <div class="mb-3">
                        <label>Producto</label>
                        <input type="text" id="modalProductoNombre" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Motivo de movimiento</label>
                        <select name="motivo" class="form-control select2" required>
                            <option value="">Seleccione...</option>
                            <!-- Devoluciones -->
                            <option value="DEVOLUCION_CLIENTE">Devolución de cliente</option>
                            <option value="DEVOLUCION_PROVEEDOR">Devolución a proveedor</option>
                            <!-- Salidas especiales -->
                            <option value="ROTO">Producto roto</option>
                            <option value="ERROR_ENTREGA">Producto entregado incorrectamente</option>
                            <option value="MERMA">Merma / Pérdida de producto</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Cantidad</label>
                        <input type="number" name="cantidad" id="modalCantidad" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label>Costo Unitario</label>
                        <input type="number" name="costo_unitario" id="modalCosto" class="form-control" readonly
                            required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Registrar Devolución</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%'
    });

    $('#tableProductosDevolucion').DataTable({
        pageLength: 10,
        responsive: true,
        autoWidth: false,
        dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-end"f>>rtip',
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ productos",
            paginate: {
                next: "Siguiente",
                previous: "Anterior"
            },
            zeroRecords: "No se encontraron resultados"
        }
    });

    // Abrir modal al hacer clic en el botón
    $('.btn-devolucion').on('click', function() {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let stock = $(this).data('stock');
        let precio = $(this).data('precio');

        $('#modalProductoId').val(id);
        $('#modalProductoNombre').val(nombre);
        $('#modalCantidad').val(1).attr('max', stock);
        $('#modalCosto').val(precio);

        $('#modalDevolucion').modal('show');
    });
});
</script>
@endsection