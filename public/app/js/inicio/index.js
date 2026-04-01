let tabla;

$(document).ready(function () {

    tabla = $('#tablaIncidentes').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '/auth/incidentes/list_all',
            type: "GET",
            data: function (d) {
                d.estado = $('#filtroEstado').val();
                d.activo_id = $('#filtroActivo').val();
                d.fecha_inicio = $('#fechaInicio').val();
                d.fecha_fin = $('#fechaFin').val();
            }
        },
        columns: [{
            data: null,
            render: function (data, type, row, meta) {
                return meta.row + 1;
            }
        },
        {
            data: 'titulo'
        },
        {
            data: 'estado',
            render: function (data) {
                if (data === 'Abierto') {
                    return `<span style="color:red;font-weight:bold;">${data}</span>`;
                }
                if (data === 'Cerrado') {
                    return `<span style="color:green;font-weight:bold;">${data}</span>`;
                }
                return data;
            }
        },
        {
            data: 'id',
            render: function (data) {
                return `<button class="btn-detalle" data-id="${data}">Ver</button>`;
            }
        }
        ],
        pageLength: 10,
        language: {
            search: "Buscar:",
            emptyTable: "No hay incidentes",
            paginate: {
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });

    $('#btnFiltrar').click(function () {
        tabla.ajax.reload();
    });

});

document.getElementById('formIncidente').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    form.querySelectorAll('p.text-error').forEach(p => p.remove());
    const generalMsg = document.getElementById('generalMsg');
    if (generalMsg) generalMsg.remove();

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
        },
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.Success) {
                const msg = document.createElement('p');
                msg.id = 'generalMsg';
                msg.className = 'text-green-500 text-sm mt-2';
                msg.innerText = data.Message;
                form.appendChild(msg);
                form.reset();
            } else if (data.Errors) {
                for (const [field, messages] of Object.entries(data.Errors)) {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        messages.forEach(msgText => {
                            const p = document.createElement('p');
                            p.className = 'text-red-500 text-sm text-error';
                            p.innerText = msgText;
                            input.insertAdjacentElement('afterend', p);
                        });
                    }
                }
            }
        })
        .catch(err => console.error(err));
});

// ABRIR MODAL AL DAR CLICK EN VER
let incidenteSeleccionado = null;

$(document).on('click', '.btn-detalle', function () {
    let id = $(this).data('id');
    incidenteSeleccionado = id;

    fetch(`/auth/incidentes/list_historial/${id}`)
        .then(res => res.json())
        .then(data => {

            let html = '';

            data.forEach(item => {
                html += `
                <div class="timeline-item">
                    <div class="timeline-content">
                        <strong>${item.accion}</strong>
                        <p>${item.comentario}</p>
                        <small>${item.usuario} - ${item.fecha_accion}</small>
                    </div>
                </div>
                `;
            });

            $('#timelineHistorial').html(html);
            $('#modalHistorial').fadeIn();
        });
});

$('#btnAgregarComentario').click(function () {

    let comentario = $('#nuevoComentario').val();

    if (!comentario.trim()) {
        alert('Escribe un comentario');
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

                // limpiar textarea
                $('#nuevoComentario').val('');

                // recargar historial automáticamente
                $(`button[data-id="${incidenteSeleccionado}"]`).click();

            } else {
                alert('Error al guardar');
            }

        });
});

// CERRAR MODAL
$('.close-modal').click(function () {
    $('#modalHistorial').fadeOut();
});

$(window).click(function (e) {
    if ($(e.target).is('#modalHistorial')) {
        $('#modalHistorial').fadeOut();
    }
});