@extends('auth.index')

@section('titulo')
<title>Gestión de Pedidos</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
<style>
#tableGestionPedidos thead th {
    background-color: #272727;
    color: #fff;
    font-weight: 600;
}

.btn-gestionar {
    background: #5864ff;
    color: #fff;
    border: none;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: .85rem;
}

#modalGestionPedido .modal-header {
    background: linear-gradient(to right, #ffffffff, #ffffffff);
    color: #fff;
    border-bottom: none;
    padding: 1rem 1.5rem;
}

#modalGestionPedido .modal-content {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e9ecef;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}


#modalDetallePedido .modal-header {
    background: linear-gradient(to right, #ffffffff, #ffffffff);
    color: #fff;
    border-bottom: none;
    padding: 1rem 1.5rem;
}

#modalDetallePedido .modal-content {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e9ecef;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
}
</style>
@endsection

@section('contenido')
<div class="content-wrapper">
    <section class="content-header" style="padding:15px; background:linear-gradient(90deg,#5864ff,#646eff); color:#fff">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-shopping-cart" style="margin-right: 8px;"></i> Aprobación de Pedidos
        </h1>
    </section>
    <br>
    <div class="row mb-3 form-section">
        <div class="col-md-3">
            <input type="date" id="fecha_inicio" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="date" id="fecha_fin" class="form-control">
        </div>
        <div class="col-md-3">
            <select id="filtro_estado" class="form-control">
                <option value="PENDIENTE">Pendiente</option>
                <option value="VALIDADO">Validado</option>
                <option value="ENTREGADO">Entregado</option>
                <option value="">Todos los estados</option>
            </select>
        </div>
        <div class="col-md-3">
            <button id="btn-filtrar" class="btn btn-light-custom">
                <i class="fa fa-search"></i> Filtrar
            </button>
        </div>
    </div>

    <hr>
    <div class="form-section">
        <table id="tableGestionPedidos" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pedido</th>
                    <th>Usuario</th>
                    <th>Fecha Registro</th>
                    <th>Fecha Entrega</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modalGestionPedido">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formGestionSeguimiento">
                @csrf
                <input type="hidden" id="g_id_pedido">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Gestionar Pedido</h5>
                    <button class="close text-black" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="g_id_pedido">

                    <label>Motorizado</label>
                    <select id="g_motorizado" class="form-control">
                        <option value="">-- Seleccione un motorizado --</option>
                        @foreach($motorizados as $m)
                        <option value="{{ $m->id }}">{{ $m->nombres }}</option>
                        @endforeach
                    </select>

                    <div class="form-group">
                        <label>Comentario</label>
                        <textarea id="g_comentario" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="aprobarPedido()">Aprobar</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Modal Detalle --}}
<div class="modal fade" id="modalDetallePedido">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Detalle del Pedido</h5>
                <button class="close text-black" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Pedido:</strong> <span id="d_codigo_pedido"></span></p>
                <p><strong>Fecha Registro:</strong> <span id="d_fecha_pedido"></span></p>
                <p><strong>Fecha Entrega solicitado:</strong> <span id="d_fecha_entrega"></span></p>
                <p><strong>Total:</strong> <span id="d_total"></span></p>
                <h6>Productos</h6>
                <div id="d_detalles"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
//Listar Pedidos para Gestión
let tabla;

$(function() {
    tabla = $('#tableGestionPedidos').DataTable({
        ajax: {
            url: "{{ route('auth.pedidos.gestion_list') }}",
            data: function(d) {
                d.fecha_inicio = $('#fecha_inicio').val();
                d.fecha_fin = $('#fecha_fin').val();
                d.estado = $('#filtro_estado').val();
            },
            dataSrc: 'data'
        },
        columns: [{
                data: null,
                render: (d, t, r, m) => m.row + 1
            },
            {
                data: 'codigo_pedido'
            },
            {
                data: 'nombre_usuario'
            },
            {
                data: 'fecha_pedido'
            },
            {
                data: 'fecha_entrega'
            },
            {
                data: 'total',
                render: d => `S/ ${parseFloat(d).toFixed(2)}`
            },
            {
                data: null,
                render: d => {

                    const estado = (d.estado_pedido || '').trim().toUpperCase();
                    let html = '';

                    // SOLO PENDIENTE
                    if (estado === 'PENDIENTE') {
                        html += `
                            <button class="btn-gestionar"
                                onclick="abrirGestion(${d.id_pedido})">
                                Gestionar
                            </button>

                            <button class="btn btn-dark btn-sm"
                                style="border-radius:25px;"
                                onclick="verDetalle(${d.id_pedido})">
                                Ver
                            </button>

                            <button class="btn btn-danger btn-sm"
                                style="border-radius:25px;"
                                onclick="anularPedido(${d.id_pedido})">
                                <i class="fa fa-ban"></i>
                            </button>
                        `;
                    }

                    // OTROS ESTADOS (VALIDADO / ENTREGADO)
                    else if (estado !== 'ANULADO') {
                        html += `
                            <button class="btn btn-dark btn-sm"
                                style="border-radius:25px;"
                                onclick="verDetalle(${d.id_pedido})">
                                Ver
                            </button>
                        `;
                    } else {
                        html = `<span class="badge badge-danger">ANULADO</span>`;
                    }
                    return html;
                }
            }


        ]
    });
});
$('#btn-filtrar').on('click', function() {
    tabla.ajax.reload();
});


//Abrir Modal de Gestión
function abrirGestion(id) {
    $('#modalGestionPedido').modal('show');
    $.get("{{ route('auth.pedidos.gestion_get') }}", {
        id_pedido: id
    }, resp => {
        const p = resp.data;
        $('#g_id_pedido').val(p.id_pedido);
        $('#g_cliente').text(p.cliente);
        $('#g_ubigeo').text(`${p.departamento} / ${p.provincia} / ${p.distrito}`);
        $('#g_total').text(`S/ ${parseFloat(p.total).toFixed(2)}`);
        $('#g_motorizado').val(p.seguimiento?.id_motorizado || '');
        $('#g_comentario').val(p.seguimiento?.comentario || '');
        $('input[name="estado"]').prop('checked', false);
        if (p.seguimiento) {
            $(`#chk_${p.seguimiento.id_estado_seguimiento}`).prop('checked', true);
        }

        let html = '<table class="table table-sm"><tr><th>Producto</th><th>Cant</th></tr>';
        p.detalles.forEach(i => html += `<tr><td>${i.descripcion}</td><td>${i.cantidad}</td></tr>`);
        html += '</table>';
        $('#g_detalle').html(html);
    });
}

// Aprobar Pedido desde el modal
function aprobarPedido() {
    const idPedido = $('#g_id_pedido').val();
    const motorizado = $('#g_motorizado').val();
    const comentario = $('#g_comentario').val();

    if (!idPedido) {
        Swal.fire('Error', 'No se obtuvo el ID del pedido.', 'error');
        return;
    }

    Swal.fire({
        title: '¿Aprobar pedido?',
        icon: 'question',
        showCancelButton: true
    }).then(result => {
        if (!result.isConfirmed) return;

        $.ajax({
            url: "{{ route('auth.pedidos.gestion_update') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                id_pedido: idPedido,
                id_motorizado: motorizado,
                comentario: comentario,
                id_estado_seguimiento: 3 // validado
            },
            success: function() {
                $('#tableGestionPedidos').DataTable().ajax.reload();
                $('#modalGestionPedido').modal('hide');
                Swal.fire('Aprobado', 'El pedido ha sido validado correctamente.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Ocurrió un problema al aprobar el pedido: ' + xhr.responseText,
                    'error');
            }
        });
    });
}



//Ver Detalle del Pedido
function verDetalle(id) {
    $('#modalDetallePedido').modal('show');
    $.get("{{ route('auth.pedidos.gestion_get') }}", {
        id_pedido: id
    }, resp => {
        const p = resp.data;
        $('#d_usuario_nombre').text(p.nombre_usuario);
        $('#d_usuario_email').text(p.email_usuario);
        $('#d_direccion_cliente').text(p.direccion_cliente);
        $('#d_codigo_pedido').text(p.codigo_pedido);
        $('#d_fecha_pedido').text(p.fecha_pedido);
        $('#d_fecha_entrega').text(p.fecha_entrega);
        $('#d_total').text(`S/ ${parseFloat(p.total).toFixed(2)}`);

        // Tabla con código del producto añadido
        let html = '<table class="table table-bordered"><tr><th>Código</th><th>Producto</th><th>Cant</th></tr>';
        p.detalles.forEach(i => {
            html += `<tr>
                        <td>${i.codigo_producto}</td>
                        <td>${i.descripcion}</td>
                        <td>${i.cantidad}</td>
                     </tr>`;
        });
        html += '</table>';

        $('#d_detalles').html(html);
    });
}

function anularPedido(idPedido) {

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
            if (!comentario || !comentario.trim()) {
                Swal.showValidationMessage('El motivo es obligatorio');
            }
            return comentario.trim();
        }
    }).then(result => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: "{{ route('auth.pedidos.gestion_update') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                id_pedido: idPedido,
                id_estado_seguimiento: 7,
                comentario: result.value
            },
            success: function(resp) {
                Swal.fire('Anulado', resp.message, 'success');
                $('#tableGestionPedidos').DataTable().ajax.reload();
            },
            error: function(xhr) {
                Swal.fire(
                    'Error',
                    xhr.responseJSON?.message || 'No se pudo anular el pedido',
                    'error'
                );
            }
        });

    });
}
</script>
@endsection