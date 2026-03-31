@extends('auth.index')

@section('titulo')
<title>Mis Incidentes</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-users mr-2" style="margin-right: 8px;"></i> Incidencias
        </h1>
    </section>

    <section class="content mt-3">
        <div class="card p-3">
            <table id="tablaGestion" class="table table-bordered table-striped">
            </table>
        </div>
    </section>

</div>

<!-- MODAL GESTION -->
<div class="modal fade" id="modalGestionar">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Gestionar Incidente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p><b>Título:</b> <span id="g_titulo"></span></p>
                <p><b>Descripción:</b> <span id="g_descripcion"></span></p>

                <div class="mb-2">
                    <label>Estado</label>
                    <select id="g_estado" class="form-control" disabled>
                        @foreach(\DB::table('maestro_estado_ticket')->get() as $est)
                        <option value="{{ $est->id }}" {{ $est->id == 3 ? 'selected' : '' }}>
                            {{ $est->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label>Comentario de solución</label>
                    <textarea id="g_comentario" class="form-control"></textarea>
                </div>

                <div class="mb-2">
                    <label>Evidencia (opcional)</label>
                    <input type="file" id="g_evidencia" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id="guardarGestion">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let tabla;
let currentId = null;

$(document).ready(function() {

    tabla = $('#tablaGestion').DataTable({
        ajax: "{{ route('incidentes.mis') }}",
        columns: [{
                data: 'id',
                title: 'ID'
            },
            {
                data: 'titulo',
                title: 'Título'
            },
            {
                data: 'severidad',
                title: 'Criticidad'
            },
            {
                data: 'estado',
                title: 'Estado',
                render: function(data) {
                    let color = 'secondary';
                    if (data === 'Abierto') color = 'danger';
                    if (data === 'En proceso') color = 'warning';
                    if (data === 'Cerrado') color = 'success';
                    return `<span class="badge bg-${color}">${data}</span>`;
                }
            },
            {
                data: 'activo',
                title: 'Activo'
            },
            {
                data: null,
                title: 'Acciones',
                render: function(d) {

                    if (d.estado === 'Cerrado') {
                        return `
                                <button class="btn btn-secondary btn-sm ver" data-id="${d.id}">
                                    Ver
                                </button>
                            `;
                    }

                    return `
                            <button class="btn btn-primary btn-sm gestionar" data-id="${d.id}">
                                Gestionar
                            </button>
                        `;
                }
            }
        ]
    });

});

$(document).on('click', '.ver', function() {
    let id = $(this).data('id');

    fetch(`/auth/incidentes/list_historial/${id}`)
        .then(res => res.json())
        .then(data => {

            let html = '';

            data.forEach(item => {
                html += `
                <div>
                    <b>${item.usuario}</b> - ${item.accion}<br>
                    <small>${item.comentario}</small>
                    <hr>
                </div>
                `;
            });

            Swal.fire({
                title: 'Historial del Incidente',
                html: html || 'Sin historial',
                width: 600
            });
        });
});
/* ABRIR MODAL */
$(document).on('click', '.gestionar', function() {
    currentId = $(this).data('id');

    $.get(`/auth/incidentes/get/${currentId}`, function(res) {
        $('#g_titulo').text(res.titulo);
        $('#g_descripcion').text(res.descripcion);
        $('#g_estado').val(res.estado_id);
    });

    $('#modalGestionar').modal('show');
});

/* GUARDAR */
$('#guardarGestion').click(function() {

    let formData = new FormData();

    formData.append('_token', $('input[name="_token"]').val());
    formData.append('id', currentId);
    formData.append('estado_id', 3);
    formData.append('comentario', $('#g_comentario').val());

    let file = $('#g_evidencia')[0].files[0];
    if (file) {
        formData.append('evidencia', file);
    }

    fetch("{{ route('incidentes.update') }}", {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {

            if (res.success) {

                $('#modalGestionar').modal('hide');
                tabla.ajax.reload();

                Swal.fire({
                    icon: 'success',
                    title: 'Incidente Cerrado',
                    text: 'Se registró correctamente'
                });

            } else {
                Swal.fire('Error', 'No se pudo guardar', 'error');
            }

        });

});

$(document).on('click', '.gestionar', function() {
    currentId = $(this).data('id');

    Swal.fire({
        title: '¿Resolver incidente?',
        text: '¿Deseas registrar evidencia y comentario?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            $.get(`/auth/incidentes/get/${currentId}`, function(res) {
                $('#g_titulo').text(res.titulo);
                $('#g_descripcion').text(res.descripcion);
                $('#g_estado').val(3); // cerrado
            });

            $('#modalGestionar').modal('show');
        }

    });
});
</script>
@endsection