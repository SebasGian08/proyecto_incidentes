let tabla;

$(document).ready(function () {

    listarTipos();

    // ABRIR MODAL
    $('#btnRegistrarTipo').click(function () {
        $('#modalTipoActivo').modal('show');
    });

    // GUARDAR
    $('#guardarTipo').click(function () {
        registrarTipo();
    });

});


/* =========================
   LISTAR
========================= */
function listarTipos() {

    tabla = $('#tableTipoActivos').DataTable({
        processing: true,
        destroy: true,
        ajax: {
            url: '/auth/tipo-activos/listar',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id', title: '#' },
            { data: 'nombre', title: 'Nombre' },
            { data: 'codigo', title: 'Código' },
            { data: 'descripcion', title: 'Descripción' },
            {
                data: null,
                title: 'Acciones',
                render: function (data) {
                    return `
                        <button class="btn btn-sm btn-primary btn-update" data-id="${data.id}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${data.id}">
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
function registrarTipo() {

    let data = {
        nombre: $('#nombre').val(),
        codigo: $('#codigo').val(),
        descripcion: $('#descripcion').val(),
        _token: $('input[name="_token"]').val()
    };

    if (!data.nombre || !data.codigo) {
        Swal.fire('Atención', 'Completa los campos obligatorios', 'warning');
        return;
    }

    $.ajax({
        url: '/auth/tipo-activos/store',
        method: 'POST',
        data: data,

        beforeSend: function () {
            Swal.fire({
                title: 'Guardando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        },

        success: function (res) {

            if (res.success) {
                Swal.fire('Correcto', res.message, 'success');
                $('#modalTipoActivo').modal('hide');
                tabla.ajax.reload();
                limpiar();
            } else {
                Swal.fire('Error', res.message, 'error');
            }

        }
    });

}


/* =========================
   EDITAR
========================= */
$(document).on('click', '.btn-update', function () {

    let id = $(this).data('id');

    $.get(`/auth/tipo-activos/get/${id}`, function (res) {

        $('#edit_id').val(res.id);
        $('#edit_nombre').val(res.nombre);
        $('#edit_codigo').val(res.codigo);
        $('#edit_descripcion').val(res.descripcion);

        $('#modalEditarTipo').modal('show');

    });

});


/* =========================
   ACTUALIZAR
========================= */
$('#actualizarTipo').click(function () {

    $.ajax({
        url: '/auth/tipo-activos/update',
        method: 'POST',
        data: {
            id: $('#edit_id').val(),
            nombre: $('#edit_nombre').val(),
            codigo: $('#edit_codigo').val(),
            descripcion: $('#edit_descripcion').val(),
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
                $('#modalEditarTipo').modal('hide');
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
        title: '¿Eliminar tipo de activo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '/auth/tipo-activos/delete',
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

                }
            });

        }

    });

});


function limpiar() {
    $('#nombre').val('');
    $('#codigo').val('');
    $('#descripcion').val('');
}