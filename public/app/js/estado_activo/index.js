let tabla;

$(document).ready(function () {

    listarEstadoActivo();

    $('#btnRegistrarEstadoActivo').click(function () {
        $('#modalEstadoActivo').modal('show');
    });

    $('#guardarEstadoActivo').click(function () {
        registrarEstadoActivo();
    });

});

function listarEstadoActivo() {

    tabla = $('#tableEstadoActivo').DataTable({
        processing: true,
        destroy: true,
        ajax: {
            url: '/auth/estado_activo/listar',
            dataSrc: 'data'
        },
        columns: [
            {
                data: null,
                title: '#',
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'nombre', title: 'Nombre' },
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

function registrarEstadoActivo() {

    let data = { nombre: $('#nombre').val(), _token: $('input[name="_token"]').val() };

    if (!data.nombre) { Swal.fire('Atención', 'Completa los campos', 'warning'); return; }

    $.post('/auth/estado_activo/store', data, function (res) {
        if (res.success) {
            Swal.fire('Correcto', res.message, 'success');
            $('#modalEstadoActivo').modal('hide');
            tabla.ajax.reload();
        }
    });

}

$(document).on('click', '.btn-update', function () {

    let id = $(this).data('id');

    $.get(`/auth/estado_activo/get/${id}`, function (res) {
        $('#edit_id').val(res.id);
        $('#edit_nombre').val(res.nombre);
        $('#modalEditarEstadoActivo').modal('show');
    });

});

$('#actualizarEstadoActivo').click(function () {

    $.post('/auth/estado_activo/update', {
        id: $('#edit_id').val(),
        nombre: $('#edit_nombre').val(),
        _token: $('input[name="_token"]').val()
    }, function (res) {
        if (res.success) {
            Swal.fire('Correcto', res.message, 'success');
            $('#modalEditarEstadoActivo').modal('hide');
            tabla.ajax.reload();
        }
    });

});

$(document).on('click', '.btn-delete', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: '¿Eliminar?',
        icon: 'warning',
        showCancelButton: true
    }).then((r) => {
        if (r.isConfirmed) {
            $.post('/auth/estado_activo/delete', { id: id, _token: $('input[name="_token"]').val() }, function () {
                tabla.ajax.reload();
            });
        }
    });

});