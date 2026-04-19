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
            data: null,
            render: function (data, type, row) {
                let botones = `<button class="btn-detalle" data-id="${row.id}">Ver</button>`;
                if (row.estado === 'Cerrado') {
                    if (row.ya_calificado > 0) {
                        /* botones += ` <button class="btn-secondary" disabled>Calificado</button>`; */
                    } else {
                        botones += ` <button class="btn-primary2 btn-calificar" data-id="${row.id}">Calificar</button>`;
                    }
                }
                return botones;
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
                const msgDiv = document.createElement('div');
                msgDiv.id = 'generalMsg';
                msgDiv.innerHTML = `
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>${data.Message}</span>
            `;
                form.appendChild(msgDiv);

                // Animación de aparición
                setTimeout(() => msgDiv.style.opacity = 1, 50);

                // Auto-desaparecer después de 4 segundos
                setTimeout(() => {
                    msgDiv.style.opacity = 0;
                    setTimeout(() => msgDiv.remove(), 500);
                }, 4000);

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

let incidenteId = null;

// Abrir modal de calificación
$(document).on('click', '.btn-calificar', function() {
    incidenteId = $(this).data('id');
    $('#modalCalificar').fadeIn();
    $('#ratingInput').val(2.5);              // valor por defecto
    $('#ratingValue').text(2.5);
    $('#comentarioCalificacion').val('');
});

// Mostrar valor al mover la barra
$('#ratingInput').on('input', function() {
    $('#ratingValue').text($(this).val());
});

// Cerrar modal
$('.close-modal').click(function() {
    $(this).closest('.modal-custom').fadeOut();
});

// Enviar calificación usando fetch()
$('#btnEnviarCalificacion').click(function() {
    const rating = $('#ratingInput').val();
    const comentario = $('#comentarioCalificacion').val();

    if(!rating) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Selecciona un rating antes de enviar!'
        });
        return;
    }

    fetch('/auth/incidentes/calificar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        body: JSON.stringify({
            id: incidenteId,
            rating: rating,
            comentario: comentario
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.Success) {
            $('#modalCalificar').fadeOut();
            Swal.fire({
                icon: 'success',
                title: '¡Calificación enviada!',
                text: data.Message
            });
            incidenteId = null;
            tabla.ajax.reload(); // recargar tabla si usas DataTables
        } else {
            let errores = Object.values(data.Errors).flat().join("\n");
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errores || 'No se pudo enviar la calificación'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error inesperado'
        });
    });
});