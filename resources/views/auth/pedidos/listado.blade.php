@extends('auth.index')

@section('titulo')
<title>Listado de Pedidos</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/listado.css') }}">
<style>
.seguimiento-item {
    position: relative;
    padding-left: 28px;
    margin-bottom: 15px;
}

.seguimiento-item::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 6px;
    width: 10px;
    height: 10px;
    background: #5864ff;
    border-radius: 50%;
}

.seguimiento-item::after {
    content: '';
    position: absolute;
    left: 12px;
    top: 18px;
    width: 2px;
    height: calc(100% - 18px);
    background: #e5e5e5;
}

.seguimiento-item.activo::before {
    background: #28a745;
}

.seguimiento-card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #eee;
    padding: 12px 15px;
}

.seguimiento-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.seguimiento-estado {
    font-weight: 600;
    color: #5864ff;
    text-transform: capitalize;
}

.seguimiento-fecha {
    font-size: 0.75rem;
    color: #999;
}

.seguimiento-comentario {
    margin-top: 6px;
    font-size: 0.9rem;
    color: #444;
}

.seguimiento-evidencias a {
    margin-right: 6px;
}
</style>
@endsection

@section('contenido')

<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-shopping-cart" style="margin-right: 8px;"></i> Listado de Pedidos
        </h1>
    </section>
    <br><br>
    <div class="content">
        <div class="row mb-3 form-section">
            <div class="col-md-3"><input type="date" id="fecha_inicio" class="form-control" placeholder="Fecha Inicio">
            </div>
            <div class="col-md-3"><input type="date" id="fecha_fin" class="form-control" placeholder="Fecha Fin"></div>
            <div class="col-md-3">
                <select id="filtro_estado" class="form-control">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="validado">Validado</option>
                    <option value="entregado">Entregado</option>
                </select>

            </div>
            <div class="col-md-3">
                <button id="btn-filtrar" class="btn btn-light-custom btn-m"><i class="fa fa-search"></i>
                    Filtrar</button>
            </div>
        </div>
        <br><br>
        <hr>
        <div class="form-section">
            <table id="tablePedidos" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Fecha registro</th>
                        <th>Fecha entrega</th>
                        <th>Cliente</th>
                        <th>Producto(s)</th>
                        <th>Total</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Seguimiento</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSeguimientoPedido" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header text-white">
                <h5 class="modal-title">
                    <i class="fa fa-history"></i> Seguimiento del Pedido
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="s_id_pedido">

                <div id="contenedorSeguimiento">
                    <p class="text-center text-muted">Cargando seguimiento...</p>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalEvidencia" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-paperclip"></i> Evidencia
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body text-center" id="contenedorEvidencia">
                <p class="text-muted">Cargando evidencia...</p>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalProductos" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-box"></i> Productos del pedido</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="contenedorProductos">
                <p class="text-center text-muted">Cargando productos...</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const hoy = new Date().toISOString().split('T')[0];

    $('#fecha_inicio').val(hoy);
    $('#fecha_fin').val(hoy);

    var table = $('#tablePedidos').DataTable({
        processing: true,
        serverSide: false,
        scrollX: false,
        scrollCollapse: true,
        responsive: true,
        autoWidth: false,
        paging: true,
        pageLength: 10,
        lengthChange: true,
        pagingType: 'simple_numbers',
        ajax: {
            url: "{{ route('auth.pedidos.list_all') }}",
            dataSrc: 'data',
            data: function(d) {
                d.fecha_inicio = $('#fecha_inicio').val();
                d.fecha_fin = $('#fecha_fin').val();
                d.estado = $('#filtro_estado').val();
            }
        },
        columns: [{ // #
                data: null,
                orderable: false,
                searchable: false,
                width: "50px",
                render: (d, t, r, m) => m.row + 1
            },
            { // Código pedido
                data: 'codigo_pedido',
                name: 'codigo_pedido',
                width: "120px"
            },
            { // Fecha registro
                data: 'fecha_pedido',
                name: 'fecha_pedido',
                width: "120px",
                render: d => d ? d.split(' ')[0] : ''
            },
            { // Fecha entrega
                data: 'fecha_entrega',
                name: 'fecha_entrega',
                width: "120px"
            },
            { // Cliente
                data: 'nombre_cliente',
                width: "240px",
                render: function(data, type, row) {
                    let telefono = row.telefono_cliente || '';
                    return `
                <div class="cliente-column">
                    <div class="cliente-nombre">${data}</div>
                    <a target="_blank"
                       href="https://api.whatsapp.com/send?phone=${telefono}"
                       class="cliente-whatsapp">
                        <i class="fa fa-whatsapp"></i> ${telefono}
                    </a>
                </div>`;
                }
            },
            {
                data: 'productos',
                orderable: false,
                searchable: false,
                width: "100px",
                render: function(data, type, row) {
                    if (!data || data.length === 0)
                        return '<span class="text-muted">Sin productos</span>';

                    // Botón de lupa para abrir modal
                    return `<button class="btn btn-sm btn-primary btn-ver-productos" 
                        data-productos='${JSON.stringify(data)}'>
                    <i class="fa fa-search"></i>
                </button>`;
                }
            },
            { // Total
                data: 'total',
                width: "110px",
                render: d => `<span class="total-badge">S/ ${parseFloat(d || 0).toFixed(2)}</span>`
            },
            { // Ubigeo
                data: null,
                width: "220px",
                render: r =>
                    `${r.departamento || ''} - ${r.provincia || ''} - ${r.distrito || ''}`
            },
            { // Estado
                data: 'estado_seguimiento',
                width: "130px",
                render: d => d ?
                    `<div class="estado-pedido">${d.replace('_', ' ')}</div>` :
                    '<span class="text-muted">Sin estado</span>'
            },
            { // Seguimiento
                data: null,
                orderable: false,
                searchable: false,
                width: "120px",
                render: function(data, type, row) {

                    let botones = '';

                    // Ver seguimiento (solo si NO está anulado)
                    if (row.estado !== 'ANULADO') {
                        botones += `
                            <button class="btn btn-sm btn-gestionar btn-ver-seguimiento"
                                    data-id="${row.id_pedido}">
                                <i class="fa fa-eye"></i> Ver
                            </button>
                        `;
                    }

                    // Anular pedido (solo si está pendiente)
                    if (row.estado === 'PENDIENTE') {
                        botones += `
                            <button class="btn btn-sm btn-danger btn-anular-pedido"
                                    data-id="${row.id_pedido}">
                                <i class="fa fa-ban"></i>
                            </button>
                        `;
                    }



                    return botones;
                }
            }


        ],
        order: [
            [1, 'desc']
        ],
        drawCallback: function() {
            this.api().columns.adjust();
            if (this.api().responsive) this.api().responsive.recalc();
        }
    });


    // Botón de filtro
    $('#btn-filtrar').click(function() {
        table.ajax.reload();
    });

    $(document).on('click', '.btn-ver-seguimiento', function() {
        let idPedido = $(this).data('id');

        $('#s_id_pedido').val(idPedido);
        $('#contenedorSeguimiento').html(
            '<p class="text-center text-muted">Cargando seguimiento...</p>'
        );

        $('#modalSeguimientoPedido').modal('show');

        cargarSeguimiento(idPedido);
    });

    function cargarSeguimiento(idPedido) {
        $.ajax({
            url: "{{ route('auth.pedidos.seguimiento') }}",
            type: "GET",
            data: {
                id_pedido: idPedido
            },
            success: function(resp) {

                if (!resp.length) {
                    $('#contenedorSeguimiento').html(
                        '<p class="text-center text-muted">Sin seguimiento registrado</p>'
                    );
                    return;
                }

                let html = '';

                resp.forEach((item, index) => {
                    html += `
                <div class="seguimiento-item ${index === resp.length - 1 ? 'activo' : ''}">
                    <div class="seguimiento-card">
                        <div class="seguimiento-header">
                            <span class="seguimiento-estado">
                                ${item.estado}
                            </span>
                            <span class="seguimiento-fecha">
                                ${item.created_at}
                            </span>
                        </div>

                        <div class="seguimiento-comentario">
                            ${item.comentario 
                                ? item.comentario 
                                : '<span class="text-muted">Sin comentario</span>'}
                        </div>

                        <div class="seguimiento-evidencias">
                            ${renderEvidencias(item)}
                        </div>
                    </div>
                </div>
                `;
                });

                $('#contenedorSeguimiento').html(html);
            }
        });
    }


    function renderEvidencias(item) {
        let html = '<div class="mt-2">';

        if (item.evidencia_chat)
            html += `<button class="badge badge-info mr-1 btn-evidencia"
                    data-url="${item.evidencia_chat}">
                    Chat
                 </button>`;

        if (item.evidencia_llamada_chat)
            html += `<button class="badge badge-warning mr-1 btn-evidencia"
                    data-url="${item.evidencia_llamada_chat}">
                    Llamada
                 </button>`;

        if (item.evidencia_entrega)
            html += `<button class="badge badge-success mr-1 btn-evidencia"
                    data-url="${item.evidencia_entrega}">
                    Entrega
                 </button>`;

        if (item.evidencia_guia)
            html += `<button class="badge badge-dark mr-1 btn-evidencia"
                    data-url="${item.evidencia_guia}">
                    Guía
                 </button>`;

        html += '</div>';
        return html;
    }
    $(document).on('click', '.btn-evidencia', function() {
        let url = $(this).data('url');
        let ext = url.split('.').pop().toLowerCase();

        let html = '';

        if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
            html = `<img src="${url}" class="img-fluid rounded">`;
        } else if (ext === 'pdf') {
            html = `
            <iframe src="${url}" 
                width="100%" 
                height="500px" 
                frameborder="0">
            </iframe>`;
        } else {
            html = `
            <a href="${url}" target="_blank" class="btn btn-primary">
                Descargar archivo
            </a>`;
        }

        $('#contenedorEvidencia').html(html);
        $('#modalEvidencia').modal('show');
    });

});
$(document).on('click', '.btn-anular-pedido', function() {

    let idPedido = $(this).data('id');

    Swal.fire({
        title: '¿Anular pedido?',
        input: 'textarea',
        inputLabel: 'Motivo de anulación',
        inputPlaceholder: 'Escribe el motivo...',
        inputAttributes: {
            required: true
        },
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar',
        preConfirm: (comentario) => {
            if (!comentario) {
                Swal.showValidationMessage('El motivo es obligatorio');
            }
            return comentario;
        }
    }).then(result => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: "{{ route('auth.pedidos.gestion_update') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id_pedido: idPedido,
                id_estado_seguimiento: 7,
                comentario: result.value
            },
            success: function(res) {
                Swal.fire('Anulado', res.message, 'success');
                $('#tablePedidos').DataTable().ajax.reload();
            },
            error: function(xhr) {
                Swal.fire(
                    'Error',
                    xhr.responseJSON?.message || 'Error al anular pedido',
                    'error'
                );
            }
        });

    });
});
$(document).on('click', '.btn-ver-productos', function() {
    let productos = $(this).data('productos');

    if (!productos || productos.length === 0) {
        $('#contenedorProductos').html('<p class="text-center text-muted">Sin productos</p>');
    } else {
        let html = '<ul class="list-group">';
        productos.forEach(p => {
            html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                ${p.descripcion} 
                <span class="badge badge-primary badge-pill">${p.cantidad}</span>
             </li>`;
        });

        html += '</ul>';
        $('#contenedorProductos').html(html);
    }

    $('#modalProductos').modal('show');
});
</script>
@endsection