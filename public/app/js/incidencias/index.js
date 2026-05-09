let tabla;
let currentId = null;

$(document).ready(function () {

    tabla = $('#tablaGestion').DataTable({
        ajax: URL_INCIDENTES_MIS,
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
            data: 'severidad',
            title: 'Criticidad'
        },
        {
            data: 'estado',
            title: 'Estado',
            render: function (data) {
                let color = 'secondary';
                if (data === 'Abierto') color = 'danger';
                if (data === 'En Progreso') color = 'warning';
                if (data === 'Cerrado') color = 'success';
                return `<span class="badge bg-${color}">${data}</span>`;
            }
        },
        {
            data: 'activo',
            title: 'Activo'
        },
        {
            data: 'created_at',
            title: 'Fecha creación',
            render: function (data) {
                if (!data) return '';

                let fecha = new Date(data);

                return fecha.toLocaleString('es-PE', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        },
        {
            data: 'fecha_cierre',
            title: 'Fecha cierre',
            render: function (data) {
                if (!data) return '';

                let fecha = new Date(data);

                return fecha.toLocaleString('es-PE', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        },

        {
            data: null,
            title: 'Acciones',
            render: function (d) {
                if (d.estado === 'Cerrado') {
                    return `<button class="btn btn-primary1 btn-sm ver" data-id="${d.id}">Ver</button>`;
                }
                return `<button class="btn btn-primary2 btn-sm gestionar" data-id="${d.id}">Gestionar</button>`;
            }
        }
        ]
    });

});

let incidenteSeleccionado = null;

/* =========================
   ABRIR HISTORIAL (VER)
========================= */
$(document).on('click', '.ver', function () {

    let id = $(this).data('id');
    incidenteSeleccionado = id;

    cargarHistorial(id);

});


/* =========================
   FUNCIÓN CARGAR HISTORIAL
========================= */
function cargarHistorial(id) {

    fetch(`/auth/incidentes/list_historial/${id}`)
        .then(res => res.json())
        .then(data => {

            let html = `
            <div style="max-height:350px;overflow-y:auto;text-align:left;">
            `;

            data.reverse().forEach((item, index) => {

                html += `
                <div style="padding:10px 0;border-bottom:1px solid #e5e7eb;">

                    <div style="display:flex;justify-content:space-between;">
                        <strong style="color:#2c3e50;">
                            ${item.accion}
                        </strong>
                        <small style="color:#6c757d;">
                            ${item.fecha_accion}
                        </small>
                    </div>

                    <div style="font-size:13px;color:#6c757d;margin-top:2px;">
                        ${item.usuario}
                    </div>

                    <div style="margin-top:5px;color:#333;">
                        ${item.comentario || 'Sin comentario'}
                    </div>

                    ${index === 0 ? `
                        <div style="margin-top:5px;">
                            <span style="
                                font-size:11px;
                                background:#dfffe3;
                                color:#495057;
                                padding:2px 6px;
                                border-radius:4px;
                            ">
                                Última actualización
                            </span>
                        </div>
                    ` : ''}

                </div>
                `;
            });

            html += `</div>`;

            html += `
                <div style="margin-top:15px;">
                    <textarea id="nuevoComentario" class="form-control"
                        placeholder="Escribe un comentario..."
                        style="width:100%;border:1px solid #ddd;border-radius:6px;padding:8px;"></textarea>

                    <button id="btnAgregarComentario"
                        style="
                            margin-top:10px;
                            background:#81C34D;
                            color:#fff;
                            border:none;
                            padding:8px 15px;
                            border-radius:6px;
                            cursor:pointer;
                        ">
                        Agregar Comentario
                    </button>
                    <button id="btnCerrarModal"
                        style="
                            background:#6c757d;
                            color:#fff;
                            border:none;
                            padding:8px 12px;
                            border-radius:6px;
                            cursor:pointer;
                        ">
                        Cerrar
                    </button>
                </div>
            `;

            Swal.fire({
                title: 'Seguimiento del Incidente',
                html: html,
                width: 600,
                showConfirmButton: false
            });

        });

}

$(document).on('click', '#btnCerrarModal', function () {
    Swal.close();
});

/* =========================
   AGREGAR COMENTARIO
========================= */
$(document).on('click', '#btnAgregarComentario', function () {

    let comentario = $('#nuevoComentario').val();

    if (!comentario.trim()) {
        Swal.fire('Atención', 'Escribe un comentario', 'warning');
        return;
    }

    fetch('/auth/incidentes/agregar_comentario', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        body: JSON.stringify({
            incidente_id: incidenteSeleccionado,
            comentario: comentario
        })
    })
        .then(res => res.json())
        .then(data => {

            if (data.success) {

                $('#nuevoComentario').val('');

                cargarHistorial(incidenteSeleccionado);

            } else {
                Swal.fire('Error', 'No se pudo guardar', 'error');
            }

        });

});

/* =========================
   ABRIR MODAL DIRECTO
========================= */
$(document).on('click', '.gestionar', function () {

    currentId = $(this).data('id');

    $.get(`/auth/incidentes/get/${currentId}`, function (res) {
        $('#g_titulo').text(res.titulo);
        $('#g_descripcion').text(res.descripcion);
        $('#g_estado').val(res.estado_id);
        $('#g_estado').val(3);
        $('#g_comentario').val('');
        $('#g_evidencia').val('');

        if (res.evidencia) {

            let ext = res.evidencia.split('.').pop().toLowerCase();

            if (ext === 'jpg' || ext === 'jpeg' || ext === 'png') {
                $('#g_evidencia_ver').html(`
                <img src="/${res.evidencia}" style="max-width:100%;border-radius:5px;">
            `);
            } else {
                $('#g_evidencia_ver').html(`
                <a href="/${res.evidencia}" target="_blank" class="btn btn-sm btn-outline-primary">
                    Ver archivo
                </a>
            `);
            }

        } else {
            $('#g_evidencia_ver').html(`<span style="color:#999;">Sin evidencia</span>`);
        }
        $('#modalGestionar').modal('show');
    });
});


/* =========================
   GUARDAR (CIERRE)
========================= */
$('#guardarGestion').click(function () {

    let formData = new FormData();

    formData.append('_token', $('input[name="_token"]').val());
    formData.append('id', currentId);
    formData.append('estado_id', 3); // 🔥 siempre cerrado
    formData.append('comentario', $('#g_comentario').val());

    let file = $('#g_evidencia')[0].files[0];
    if (file) {
        formData.append('evidencia', file);
    }

    fetch(URL_UPDATE_INCIDENTE, {
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