@extends('auth.index')

@section('titulo')
<title>Kardex - Movimientos</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/listado.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.stock-bajo { color: red; font-weight: 600; }
.entrada { background-color: #d4edda; } /* verde suave */
.salida { background-color: #f8d7da; }  /* rojo suave */
</style>
@endsection

@section('contenido')
<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-list-alt" style="margin-right: 8px;"></i> Kardex / Movimientos
        </h1>
    </section>

    <br>
    <div class="content">
        <div class="row mb-3 form-section">
            <div class="col-md-2"><input type="date" id="fecha_inicio" class="form-control" placeholder="Fecha Inicio"></div>
            <div class="col-md-2"><input type="date" id="fecha_fin" class="form-control" placeholder="Fecha Fin"></div>
            <div class="col-md-3">
                <select id="producto_id" class="form-control">
                    <option value="">Todos los productos</option>
                    @foreach($productos as $prod)
                        <option value="{{ $prod->id_producto }}">{{ $prod->descripcion }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select id="tipo_movimiento" class="form-control">
                    <option value="">Todos los tipos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id_tipo_movimiento }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select id="motivo" class="form-control">
                    <option value="">Todos los motivos</option>
                    @foreach($motivos as $motivo)
                        <option value="{{ $motivo }}">{{ $motivo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1">
                <button id="btn-filtrar" class="btn btn-light-custom btn-m"><i class="fa fa-search"></i> Buscar</button>
            </div>
        </div>

        <br>
        <hr>
        <div class="form-section">
            <table id="tableMovimientos" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Motivo</th>
                        <th>Cantidad</th>
                        <th>Stock Anterior</th>
                        <th>Stock Nuevo</th>
                        <th>Costo Unitario</th>
                        <th>Costo Total</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#producto_id').select2({ placeholder: 'Buscar producto...', allowClear: true, width: '100%' });
    $('#tipo_movimiento').select2({ placeholder: 'Tipo movimiento', allowClear: true, width: '100%' });

    var table = $('#tableMovimientos').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        ajax: {
            url: "{{ route('auth.movimientos.list_all') }}",
            dataSrc: 'data',
            data: function(d) {
                d.fecha_inicio = $('#fecha_inicio').val();
                d.fecha_fin = $('#fecha_fin').val();
                d.producto_id = $('#producto_id').val();
                d.id_tipo_movimiento = $('#tipo_movimiento').val();
                d.motivo = $('#motivo').val();
            }
        },
        columns: [
            { data: null, render: (data, type, row, meta) => meta.row + 1 },
            { data: 'fecha_movimiento', name: 'fecha_movimiento' },
            { data: 'producto', name: 'producto' },
            { 
                data: 'tipo_movimiento',
                render: function(d, type, row) {
                    let clase = '';
                    if (row.codigo === 'E') clase = 'entrada';
                    if (row.codigo === 'S') clase = 'salida';
                    return `<span class="${clase}">${d}</span>`;
                }
            },
            { data: 'motivo', name: 'motivo' },
            { data: 'cantidad', name: 'cantidad' },
            { data: 'stock_anterior', name: 'stock_anterior' },
            { data: 'stock_nuevo', render: d => `<span class="${d <= 0 ? 'stock-bajo' : ''}">${d}</span>` },
            { data: 'costo_unitario', render: d => 'S/ ' + parseFloat(d || 0).toFixed(2) },
            { data: 'costo_total', render: d => 'S/ ' + parseFloat(d || 0).toFixed(2) }
        ],
        order: [[1, 'desc']]
    });

    $('#btn-filtrar').click(function() { table.ajax.reload(); });
});
</script>
@endsection
