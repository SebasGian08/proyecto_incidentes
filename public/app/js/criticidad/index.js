let tabla;

$(document).ready(function () {

    listarCriticidad();

    $('#btnRegistrarCriticidad').click(function () {
        $('#modalCriticidad').modal('show');
    });

    $('#guardarCriticidad').click(function () {
        registrarCriticidad();
    });

});


function listarCriticidad() {

    tabla = $('#tableCriticidad').DataTable({
        processing: true,
        destroy: true,
        ajax: {
            url: '/auth/criticidad/listar',
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


function registrarCriticidad() {

    let data = {
        nombre: $('#nombre').val(),
        _token: $('input[name="_token"]').val()
    };

    if (!data.nombre) {
        Swal.fire('Atención', 'Completa los campos', 'warning');
        return;
    }

    $.ajax({
        url: '/auth/criticidad/store',
        method: 'POST',
        data: data,
        beforeSend: () => Swal.fire({ title: 'Guardando...', didOpen: () => Swal.showLoading() }),
        success: function (res) {
            if (res.success) {
                Swal.fire('Correcto', res.message, 'success');
                $('#modalCriticidad').modal('hide');
                tabla.ajax.reload();
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }
    });
}


$(document).on('click', '.btn-update', function () {

    let id = $(this).data('id');

    $.get(`/auth/criticidad/get/${id}`, function (res) {

        $('#edit_id').val(res.id);
        $('#edit_nombre').val(res.nombre);

        $('#modalEditarCriticidad').modal('show');

    });

});


$('#actualizarCriticidad').click(function () {

    $.ajax({
        url: '/auth/criticidad/update',
        method: 'POST',
        data: {
            id: $('#edit_id').val(),
            nombre: $('#edit_nombre').val(),
            _token: $('input[name="_token"]').val()
        },
        beforeSend: () => Swal.fire({ title: 'Actualizando...', didOpen: () => Swal.showLoading() }),
        success: function (res) {
            if (res.success) {
                Swal.fire('Correcto', res.message, 'success');
                $('#modalEditarCriticidad').modal('hide');
                tabla.ajax.reload();
            } else {
                Swal.fire('Error', res.message, 'error');
            }
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

            $.post('/auth/criticidad/delete', {
                id: id,
                _token: $('input[name="_token"]').val()
            }, function () {
                tabla.ajax.reload();
            });

        }

    });

});