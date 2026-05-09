$(function () {

    // TABLA
    let tabla = $('#tableIncidentes').DataTable({
        ajax: {
            url: '/auth/incidentes/list_filtros',
            data: function (d) {
                d.estado = $('#filtroEstado').val();
                d.severidad = $('#filtroSeveridad').val();
                d.activo_id = $('#filtroActivo').val();
                d.fecha_inicio = $('#fechaInicio').val();
                d.fecha_fin = $('#fechaFin').val();
            }
        },
        columns: [{
            data: null,
            title: '#',
            render: function (data, type, row, meta) {
                return meta.row + 1;
            }
        },
        {
            data: 'titulo',
            title: 'Título'
        },
        {
            data: 'estado',
            title: 'Estado',
            render: function (data) {
                let color = 'secondary';
                if (data === 'Abierto') color = 'danger';
                if (data === 'En proceso') color = 'warning';
                if (data === 'Cerrado') color = 'success';
                return `<span class="badge bg-${color}">${data}</span>`;
            }
        },
        {
            data: 'severidad',
            title: 'Criticidad'
        },
        {
            data: 'activo',
            title: 'Activo'
        },
        {
            data: 'created_at',
            title: 'Fecha Creación'
        },
        {
            data: null,
            title: 'Acciones',
            render: function (d) {
                return `
                        <button class="btn btn-sm btn-primary2 gestionar" data-id="${d.id}">
                            <i class="fa fa-cog"></i> Gestionar
                        </button>
                    `;
            }
        }
        ]
    });

    $('#filtroEstado, #filtroSeveridad, #filtroActivo, #fechaInicio, #fechaFin')
        .change(() => tabla.ajax.reload());

    $('#btnRegistrarIncidente').click(() => {
        $('#modalIncidente').modal('show');
    });

    $('#guardarIncidente').click(function () {

        $.post("/auth/incidentes/store", {
            _token: $('input[name="_token"]').val(),
            titulo: $('#titulo').val(),
            descripcion: $('#descripcion').val(),
            severidad: $('#severidad').val(),
            estado: $('#estado').val(),
            activo_id: $('#activo_id').val(),
            tecnico_asignado_id: $('#tecnico_id').val()
        })
            .done(function (res) {

                if (res.Success) {

                    Swal.fire({
                        icon: 'success',
                        title: '¡Registrado!',
                        text: res.Message,
                        confirmButtonText: 'OK'
                    });

                    $('#modalIncidente').modal('hide');
                    tabla.ajax.reload();

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.Message || 'No se pudo registrar'
                    });

                }

            })
            .fail(function (err) {

                if (err.status === 422) {

                    let errores = err.responseJSON.Errors;
                    let listaErrores = '';

                    for (let campo in errores) {
                        listaErrores += `• ${errores[campo][0]}<br>`;
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Validaciones',
                        html: listaErrores
                    });

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error del servidor',
                        text: 'Ocurrió un problema inesperado'
                    });

                }

            });

    });

    let currentId = null;

    $(document).on('click', '.gestionar', function () {

        currentId = $(this).data('id');

        $.get(`/auth/incidentes/get/${currentId}`, function (res) {

            $('#g_titulo').text(res.titulo);
            $('#g_descripcion').text(res.descripcion);
            $('#g_activo').text(res.activo_nombre ?? '-');
            $('#g_severidad').text(res.severidad_nombre ?? '-');

            $('#g_estado').val(res.estado_id);
            $('#g_tecnico').val(res.tecnico_asignado_id);

        });

        $.get(API.historial.replace(':id', currentId), function (data) {

            let html = '';

            data.forEach(x => {
                html += `
                <div>
                    <b>${x.usuario}</b> - ${x.accion}<br>
                    <small>${x.comentario}</small>
                    <hr>
                </div>
            `;
            });

            $('#historial').html(html);
        });

        $('#modalGestionar').modal('show');
    });

    $('#guardarGestion').click(function () {

        $.post("/auth/incidentes/update", {
            _token: $('input[name="_token"]').val(),
            id: currentId,
            tecnico_id: $('#g_tecnico').val(),
            estado_id: $('#g_estado').val()
        })
            .done(function (res) {

                if (res.success) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        text: 'Incidente actualizado correctamente'
                    });

                    $('#modalGestionar').modal('hide');
                    tabla.ajax.reload();

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo actualizar'
                    });
                }

            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error del servidor'
                });
            });

    });
});