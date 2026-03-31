@extends('auth.index')

@section('titulo')
<title>Inicio</title>
@endsection


@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('auth/css/inicio/core.css') }}">

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('app/assets_registro_login/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_incidentes_registro/style.css') }}">
@endsection



@section('contenido')

<div class="content-wrapper">

    <section class="content-header d-flex justify-content-between align-items-center"
        style="padding:15px 25px;border-bottom:2px solid #e0e0e0;background:linear-gradient(to right,#5864ff,#646eff);border-radius:8px;">
        <h1 style="font-family:'Poppins';font-weight:600;color:#fff;margin:0;font-size:1.8rem;">
            <i class="fa fa-home" style="margin-right:8px;"></i> Inicio
        </h1>

    </section>
    <br>

    <div class="layout-incidentes">
        <!-- FORMULARIO -->
        <div class="container-form">

            <div class="form-information">

                <div class="form-information-childs">

                    <a class="navbar-brand" href="#">
                        <img src="{{ asset('app/img/logo2.png') }}" alt="Logo" class="logo" />
                    </a>

                    <h2>Registrar Incidente</h2>
                    <p class="sub-text">Completa los datos del incidente</p>

                </div>

                <form class="form form-incidente" action="{{ route('incidentes.store') }}" method="POST"
                    id="formIncidente" enctype="multipart/form-data">
                    @csrf

                    <!-- Título -->
                    <label>
                        <i class='bx bx-text'></i>
                        <input type="text" name="titulo" placeholder="Título del incidente" required
                            value="{{ old('titulo') }}">
                    </label>
                    @error('titulo')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Descripción -->
                    <label>
                        <i class='bx bx-message-square-detail'></i>
                        <textarea name="descripcion"
                            placeholder="Descripción detallada">{{ old('descripcion') }}</textarea>
                    </label>
                    @error('descripcion')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Severidad -->
                    <label style="position: relative; display: flex; align-items: center;">
                        <i class='bx bx-error-circle' style="position:absolute; left:10px;"></i>
                        <select name="severidad" required style="padding-left:35px; appearance:auto;">
                            <option value="" disabled selected>Seleccione la severidad</option>
                            @foreach(\DB::table('maestro_severidad')->get() as $sev)
                            <option value="{{ $sev->id }}" {{ old('severidad') == $sev->id ? 'selected' : '' }}>
                                {{ $sev->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </label>
                    @error('severidad')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror


                    <!-- Activo -->
                    <label style="position: relative; display: flex; align-items: center;">
                        <i class='bx bx-desktop' style="position:absolute; left:10px;"></i>
                        <select name="activo_id" required style="padding-left:35px; appearance:auto;">
                            <option value="" disabled selected>Seleccione el activo</option>
                            @foreach(\DB::table('activos_ti')->get() as $activo)
                            <option value="{{ $activo->id }}" {{ old('activo_id') == $activo->id ? 'selected' : '' }}>
                                {{ $activo->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </label>
                    @error('activo_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror


                    <!-- Evidencia -->
                    <label>
                        <i class='bx bx-upload'></i>
                        <input type="file" name="evidencia" accept="image/*,.pdf">
                    </label>
                    @error('evidencia')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Estado fijo -->
                    <label>
                        <i class='bx bx-cog'></i>
                        <select disabled style="padding-left:35px; appearance:auto;">
                            <option value="1" selected>Abierto</option>
                        </select>
                        <!-- Campo oculto para enviar valor -->
                        <input type="hidden" name="estado" value="1">
                    </label>
                    @error('estado')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <input type="submit" value="Registrar Incidente" class="mt-2">

                    <!-- Mensaje general -->
                    @if(session('message'))
                    <p class="text-green-500 text-sm mt-2">{{ session('message') }}</p>
                    @endif
                </form>
            </div>
        </div>

        <!-- TABLA INCIDENTES -->
        <div class="tabla-incidentes">
            <h4 style="margin-bottom:15px;">
                <i class='bx bx-list-ul'></i> Mis Incidentes
            </h4>
            <div class="filtros-box">

                <select id="filtroEstado" class="filtro-input">
                    <option value="">Estado</option>
                    @foreach(\DB::table('maestro_estado_ticket')->get() as $est)
                    <option value="{{ $est->nombre }}">{{ $est->nombre }}</option>
                    @endforeach
                </select>

                <select id="filtroActivo" class="filtro-input">
                    <option value="">Activo</option>
                    @foreach(\DB::table('activos_ti')->get() as $act)
                    <option value="{{ $act->id }}">{{ $act->nombre }}</option>
                    @endforeach
                </select>

                <input type="date" id="fechaInicio" class="filtro-input">
                <input type="date" id="fechaFin" class="filtro-input">

                <button id="btnFiltrar" class="btn-filtrar">Filtrar</button>

            </div>
            <div class="table-responsive">
                <table class="table-sm" id="tablaIncidentes" style="width:100%;">
                    <thead style="background:#5864ff;color:white;">
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Estado</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL HISTORIAL -->
<div id="modalHistorial" class="modal-custom">
    <div class="modal-content-custom">
        <span class="close-modal">&times;</span>
        <h3>Seguimiento del Incidente</h3>

        <div id="timelineHistorial" class="timeline"></div>

        <div style="margin-top:15px; text-align: center;">
            <textarea id="nuevoComentario" class="form-control"
                placeholder="Escribe un comentario..."></textarea>

            <button id="btnAgregarComentario" class="btn-filtrar mt-2">
                Agregar Comentario
            </button>
        </div>
    </div>
</div>
@endsection



@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
let tabla;

$(document).ready(function() {

    tabla = $('#tablaIncidentes').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('incidentes.list_all') }}",
            type: "GET",
            data: function(d) {
                d.estado = $('#filtroEstado').val();
                d.activo_id = $('#filtroActivo').val();
                d.fecha_inicio = $('#fechaInicio').val();
                d.fecha_fin = $('#fechaFin').val();
            }
        },
        columns: [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'titulo'
            },
            {
                data: 'estado',
                render: function(data) {
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
                render: function(data) {
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

    $('#btnFiltrar').click(function() {
        tabla.ajax.reload();
    });

});

document.getElementById('formIncidente').addEventListener('submit', function(e) {
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

$(document).on('click', '.btn-detalle', function() {
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

$('#btnAgregarComentario').click(function() {

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
$('.close-modal').click(function() {
    $('#modalHistorial').fadeOut();
});

$(window).click(function(e) {
    if ($(e.target).is('#modalHistorial')) {
        $('#modalHistorial').fadeOut();
    }
});
</script>

@endsection