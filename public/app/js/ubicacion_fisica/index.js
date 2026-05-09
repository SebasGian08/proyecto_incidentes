let tabla;

$(document).ready(function () {

    listarUbicacion();

    $('#btnRegistrarUbicacion').click(function () {
        $('#modalUbicacion').modal('show');
    });

    $('#guardarUbicacion').click(function () {
        registrarUbicacion();
    });

});


function listarUbicacion() {

    tabla = $('#tableUbicacion').DataTable({
        processing: true,
        destroy: true,
        ajax: {
            url: '/auth/ubicacion-fisica/listar',
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


function registrarUbicacion() {

    let data = {
        nombre: $('#nombre').val(),
        _token: $('input[name="_token"]').val()
    };

    if (!data.nombre) {
        Swal.fire('Atención', 'Completa los campos', 'warning');
        return;
    }

    $.ajax({
        url: '/auth/ubicacion-fisica/store',
        method: 'POST',
        data: data,
        beforeSend: () => Swal.fire({ title: 'Guardando...', didOpen: () => Swal.showLoading() }),
        success: function (res) {
            if (res.success) {
                Swal.fire('Correcto', res.message, 'success');
                $('#modalUbicacion').modal('hide');
                tabla.ajax.reload();
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }
    });
}


$(document).on('click', '.btn-update', function () {

    let id = $(this).data('id');

    $.get(`/auth/ubicacion-fisica/get/${id}`, function (res) {

        $('#edit_id').val(res.id);
        $('#edit_nombre').val(res.nombre);

        $('#modalEditarUbicacion').modal('show');

    });

});


$('#actualizarUbicacion').click(function () {

    $.ajax({
        url: '/auth/ubicacion-fisica/update',
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
                $('#modalEditarUbicacion').modal('hide');
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

            $.post('/auth/ubicacion-fisica/delete', {
                id: id,
                _token: $('input[name="_token"]').val()
            }, function () {
                tabla.ajax.reload();
            });

        }

    });

});