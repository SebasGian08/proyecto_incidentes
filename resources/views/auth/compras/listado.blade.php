@extends('auth.index')

@section('titulo')
<title>Listado de Compras</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/listado.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-shopping-cart" style="margin-right: 8px;"></i> Listado de Compras
        </h1>
    </section>

    <br>

    <div class="content">
        <!-- Filtros -->
        <div class="row mb-3 form-section">
            <div class="col-md-3">
                <input type="date" id="fecha_inicio" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="date" id="fecha_fin" class="form-control">
            </div>
            <div class="col-md-3">
                <button id="btn-filtrar" class="btn btn-light-custom">
                    <i class="fa fa-search"></i> Filtrar
                </button>
            </div>
        </div>

        <hr>

        <!-- Tabla -->
        <div class="form-section">
            <table id="tableCompras" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Documento</th>
                        <th>Número</th>
                        <th>Proveedor</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- MODAL VER COMPRA -->

<div class="modal fade" id="modalVerCompra" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-shopping-cart"></i> Detalle de Compra
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div id="infoCompra"></div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo</th>
                            <th>Total</th>
                            <th>Movimiento</th>
                        </tr>
                    </thead>

                    <tbody id="detalleCompra"></tbody>

                </table>

                <h4 class="text-right">
                    Total: S/ <span id="totalCompra"></span>
                </h4>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalKardex">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Movimiento del Producto
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Cantidad</th>
                            <th>Stock Anterior</th>
                            <th>Stock Nuevo</th>
                        </tr>
                    </thead>

                    <tbody id="tablaKardex"></tbody>

                </table>

            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>

<script>
$(document).ready(function() {

    let table = $('#tableCompras').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        paging: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('auth.compras.list_all') }}",
            dataSrc: 'data',
            data: function(d) {
                d.fecha_inicio = $('#fecha_inicio').val();
                d.fecha_fin = $('#fecha_fin').val();
            }
        },
        columns: [{
                data: null,
                orderable: false,
                searchable: false,
                render: (data, type, row, meta) => meta.row + 1
            },
            {
                data: 'fecha_compra',
                render: data => data ?? ''
            },
            {
                data: 'tipo_documento',
                render: data => data.toUpperCase()
            },
            {
                data: 'numero_documento'
            },
            {
                data: 'razon_social'
            },
            {
                data: 'total',
                render: data => `<strong>S/ ${parseFloat(data || 0).toFixed(2)}</strong>`
            },
            {
                data: 'estado',
                render: function(estado) {
                    let texto = 'Anulado';
                    let badge = 'danger';

                    if (estado == 1) {
                        texto = 'Activo';
                        badge = 'primary';
                    }

                    return `<span class="badge badge-${badge}">${texto}</span>`;
                }
            },
            {
                data: 'id_compra',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(id) {
                    return `
                        <button 
                        class="btn btn-sm btn-outline-primary btn-ver-compra"
                        data-id="${id}">
                        Ver Compra
                        </button>
                        <a href="/auth/compras/edit/${id}" 
                        class="btn btn-sm btn-outline-warning">
                        <i class="fa fa-edit"></i>
                        </a>
                        
                    `;
                }
            }




        ],
        order: [
            [1, 'desc']
        ]
    });

    $('#btn-filtrar').click(function() {
        table.ajax.reload();
    });

});

$(document).on("click", ".btn-ver-compra", function() {

    let id = $(this).data("id")

    $.get("/auth/compras/ver/" + id, function(resp) {

        let compra = resp.compra
        let detalle = resp.detalle

        $("#infoCompra").html(`
                <p><b>Proveedor:</b> ${compra.razon_social}</p>
                <p><b>Documento:</b> ${compra.tipo_documento} - ${compra.numero_documento}</p>
                <p><b>Fecha:</b> ${compra.fecha_compra}</p>
                `)

        let html = ""

        detalle.forEach(d => {

            html += `
                <tr>
                <td>${d.descripcion}</td>
                <td>${d.cantidad}</td>
                <td>S/ ${parseFloat(d.costo_unitario).toFixed(2)}</td>
                <td>S/ ${parseFloat(d.subtotal).toFixed(2)}</td>
                <td>
                <button 
                class="btn btn-sm btn-light-custom btn-ver-kardex"
                data-id="${d.id_producto}">
                <i class="fa fa-exchange-alt"></i> Ver Movimiento
                </button>
                </td>
                </tr>
                `

        })

        $("#detalleCompra").html(html)

        $("#totalCompra").text(parseFloat(compra.total).toFixed(2))

        $("#modalVerCompra").modal("show")

    })

})

$(document).on("click", ".btn-ver-kardex", function() {

    let id = $(this).data("id")

    $.get("/auth/compras/kardex/" + id, function(resp) {

        let html = ""

        resp.movimientos.forEach(m => {

            html += `
                <tr>
                <td>${m.fecha_movimiento}</td>
                <td>${m.motivo}</td>
                <td>${m.cantidad}</td>
                <td>${m.stock_anterior}</td>
                <td>${m.stock_nuevo}</td>
                </tr>
                `

        })

        $("#tablaKardex").html(html)

        $("#modalKardex").modal("show")

    })

})
</script>
@endsection