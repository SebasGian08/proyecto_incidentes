@extends('auth.index')

@section('titulo')
<title>Ajuste de Cuadre</title>
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
        <h1 style="color: #fff;">
            <i class="fa fa-calculator"></i> Ajuste de Stock
        </h1>
    </section>

    <div class="content mt-3">
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
        <div class="form-section">
            <div class="card-body">
                <table id="tableProductosAjuste" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Stock Actual</th>
                            <th width="120">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $index => $prod)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $prod->descripcion }}</td>
                            <td>
                                {{ $prod->stock }}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm btn-ajuste" data-id="{{ $prod->id_producto }}"
                                    data-descripcion="{{ $prod->descripcion }}" data-stock="{{ $prod->stock }}">
                                    <i class="fa fa-edit"></i> Ajuste
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
@endsection
<div class="modal fade" id="modalAjuste" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('auth.ajustes.store') }}">
                @csrf
                <input type="hidden" name="id_producto" id="modal_id_producto">

                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title">
                            Ajustar Stock
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Producto</label>
                            <input type="text" id="modal_producto" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Stock actual</label>
                            <input type="text" id="modal_stock_actual" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Nuevo stock</label>
                            <input type="number" name="nuevo_stock" class="form-control" min="0" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%'
    });
    
    $('#tableProductosAjuste').DataTable({
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
});

document.addEventListener("click", function(e) {

    if (e.target.closest(".btn-ajuste")) {

        let btn = e.target.closest(".btn-ajuste");

        document.getElementById("modal_id_producto").value = btn.dataset.id;
        document.getElementById("modal_producto").value = btn.dataset.descripcion;
        document.getElementById("modal_stock_actual").value = btn.dataset.stock;

        let modal = new bootstrap.Modal(document.getElementById('modalAjuste'));
        modal.show();
    }

});
</script>

@endsection