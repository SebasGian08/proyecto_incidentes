let tabla;

$(document).ready(function () {

    listarActivos();

    // ABRIR MODAL
    $('#btnRegistrarActivo').click(function () {
        $('#modalActivo').modal('show');
    });

    // GUARDAR
    $('#guardarActivo').click(function () {
        registrarActivo();
    });

});


/* =========================
   LISTAR
========================= */
$('#filtroEstado, #filtroTipo, #filtroUbicacion, #fechaInicio, #fechaFin').change(function () {
    tabla.ajax.reload();
});

function listarActivos() {

    tabla = $('#tableActivos').DataTable({
        processing: true,
        destroy: true,
        ajax: {
            url: '/auth/activos/listar',
            data: function (d) {
                d.estado = $('#filtroEstado').val();
                d.tipo = $('#filtroTipo').val();
                d.ubicacion = $('#filtroUbicacion').val();
                d.fechaInicio = $('#fechaInicio').val();
                d.fechaFin = $('#fechaFin').val();
            },
            dataSrc: 'data'
        },
        columns: [
            { data: 'id', title: '#' },
            { data: 'codigo_patrimonial', title: 'Código' },
            { data: 'nombre', title: 'Nombre' },
            { data: 'numero_serie', title: 'Serie' },
            { data: 'tipo', title: 'Tipo' },
            { data: 'ubicacion', title: 'Ubicación' },
            { data: 'usuario', title: 'Responsable' },
            { data: 'estado', title: 'Estado' },
            {
                data: null,
                title: 'Acciones',
                render: function (data) {
                    return `
                        <button class="btn btn-sm btn-primary editar btn-update" data-id="${data.id}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger eliminar btn-delete" data-id="${data.id}">
                            <i class="fa fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

}


/* =========================
   REGISTRAR
========================= */
function registrarActivo() {

    let data = {
        codigo_patrimonial: $('#codigo_patrimonial').val(),
        nombre: $('#nombre').val(),
        numero_serie: $('#numero_serie').val(),
        tipo_id: $('#tipo_id').val(),
        usuario_responsable_id: $('#usuario_responsable_id').val(),
        criticidad_id: $('#criticidad_id').val(),
        confidencialidad_id: $('#confidencialidad_id').val(),
        ubicacion_id: $('#ubicacion_id').val(),
        estado_id: $('#estado_id').val(),
        _token: $('input[name="_token"]').val()
    };

    if (!data.codigo_patrimonial || !data.nombre || !data.numero_serie) {
        Swal.fire('Atención', 'Completa los campos obligatorios', 'warning');
        return;
    }

    $.ajax({
        url: '/auth/activos/store',
        method: 'POST',
        data: data,

        beforeSend: function () {
            Swal.fire({
                title: 'Guardando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },

        success: function (res) {

            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    text: res.message
                });

                $('#modalActivo').modal('hide');
                tabla.ajax.reload();
                limpiarFormulario();

            } else {
                Swal.fire('Error', res.message, 'error');
            }

        },

        error: function (xhr) {

            let errores = xhr.responseJSON.errors;

            let mensaje = Object.values(errores)
                .map(e => e[0])
                .join('<br>');

            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                html: mensaje
            });
        }

    });

}


/* =========================
   EDITAR (ABRIR MODAL)
========================= */
$(document).on('click', '.btn-update', function () {

    let id = $(this).data('id');

    $.get(`/auth/activos/get/${id}`, function (res) {

        $('#edit_id').val(res.id);
        $('#edit_codigo_patrimonial').val(res.codigo_patrimonial);
        $('#edit_nombre').val(res.nombre);
        $('#edit_numero_serie').val(res.numero_serie);
        $('#edit_tipo_id').val(res.tipo_id);
        $('#edit_usuario_responsable_id').val(res.usuario_responsable_id);
        $('#edit_criticidad_id').val(res.criticidad_id);
        $('#edit_confidencialidad_id').val(res.confidencialidad_id);
        $('#edit_ubicacion_id').val(res.ubicacion_id);
        $('#edit_estado_id').val(res.estado_id);

        $('#modalEditarActivo').modal('show');

    });

});

/* =========================
   ACTUALIZAR
========================= */
$('#actualizarActivo').click(function () {

    $.ajax({
        url: '/auth/activos/update',
        method: 'POST',
        data: {
            id: $('#edit_id').val(),
            codigo_patrimonial: $('#edit_codigo_patrimonial').val(),
            nombre: $('#edit_nombre').val(),
            numero_serie: $('#edit_numero_serie').val(),
            tipo_id: $('#edit_tipo_id').val(),
            usuario_responsable_id: $('#edit_usuario_responsable_id').val(),
            criticidad_id: $('#edit_criticidad_id').val(),
            confidencialidad_id: $('#edit_confidencialidad_id').val(),
            ubicacion_id: $('#edit_ubicacion_id').val(),
            estado_id: $('#edit_estado_id').val(),
            _token: $('input[name="_token"]').val()
        },

        beforeSend: function () {
            Swal.fire({
                title: 'Actualizando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        },

        success: function (res) {

            if (res.success) {
                Swal.fire('Correcto', res.message, 'success');
                $('#modalEditarActivo').modal('hide');
                tabla.ajax.reload();
            } else {
                Swal.fire('Error', res.message, 'error');
            }

        }
    });

});

/* =========================
   ELIMINAR
========================= */
$(document).on('click', '.btn-delete', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: '¿Eliminar activo?',
        text: 'Esta acción no se puede revertir',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '/auth/activos/delete',
                method: 'POST',
                data: {
                    id: id,
                    _token: $('input[name="_token"]').val()
                },

                beforeSend: function () {
                    Swal.fire({
                        title: 'Eliminando...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },

                success: function (res) {

                    if (res.success) {
                        Swal.fire('Eliminado', res.message, 'success');
                        tabla.ajax.reload();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }

                },

                error: function () {
                    Swal.fire('Error', 'Error del servidor', 'error');
                }
            });

        }

    });

});


/* =========================
   LIMPIAR
========================= */
function limpiarFormulario() {
    $('#codigo_patrimonial').val('');
    $('#nombre').val('');
    $('#numero_serie').val('');
    $('#usuario_responsable_id').val('');
    $('#estado_id').val('');
}