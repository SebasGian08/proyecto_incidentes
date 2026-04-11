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
<style>
.modal-content-custom {
    width: 80%;
    max-width: 1000px; /* tamaño LG */
    height: auto;
    max-height: 90vh;
    overflow-y: auto;
    border-radius: 12px;
    background: #fff;
    padding: 20px;
}
</style>
@endsection



@section('contenido')

<div class="content-wrapper">

    <section class="content-header d-flex justify-content-between align-items-center"
        style="padding:15px 25px;border-bottom:2px solid #e0e0e0;background:linear-gradient(to right,#81C34D,#81C34D);border-radius:8px;">
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
                            @foreach(\DB::table('maestro_severidad')->whereNull('deleted_at')->get() as $sev)
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
                            @foreach(\DB::table('activos_ti')->whereNull('deleted_at')->get() as $activo)
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
                    <div class="flex items-center bg-green-100 border border-green-400 text-green-800 px-4 py-2 rounded mt-2"
                        role="alert">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('message') }}</span>
                    </div>
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

                <!-- Filtro Estado -->
                <label for="filtroEstado" style="display:flex; flex-direction: column; margin-bottom: 10px;">
                    Estado del Ticket
                    <select id="filtroEstado" class="filtro-input">
                        <option value="">Todos</option>
                        @foreach(\DB::table('maestro_estado_ticket')->whereNull('deleted_at')->get() as $est)
                        <option value="{{ $est->nombre }}">{{ $est->nombre }}</option>
                        @endforeach
                    </select>
                </label>

                <!-- Filtro Activo -->
                <label for="filtroActivo" style="display:flex; flex-direction: column; margin-bottom: 10px;">
                    Activo TI
                    <select id="filtroActivo" class="filtro-input">
                        <option value="">Todos</option>
                        @foreach(\DB::table('activos_ti')->whereNull('deleted_at')->get() as $act)
                        <option value="{{ $act->id }}">{{ $act->nombre }}</option>
                        @endforeach
                    </select>
                </label>

                <!-- Fecha Inicio -->
                <label for="fechaInicio" style="display:flex; flex-direction: column; margin-bottom: 10px;">
                    Fecha Inicio
                    <input type="date" id="fechaInicio" class="filtro-input">
                </label>

                <!-- Fecha Fin -->
                <label for="fechaFin" style="display:flex; flex-direction: column; margin-bottom: 10px;">
                    Fecha Fin
                    <input type="date" id="fechaFin" class="filtro-input">
                </label>

                <button id="btnFiltrar" class="btn-filtrar" style="height: 38px; margin-top: 20px;;">Filtrar</button>

            </div>
            <div class="table-responsive">
                <table class="table-sm" id="tablaIncidentes" style="width:100%;">
                    <thead style="background:#81C34D;color:white;">
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
        <hr>
        <div style="margin-top:15px; text-align:center;">
            <textarea id="nuevoComentario" class="form-control"
                placeholder="Escribe un comentario..."></textarea>

            <button id="btnAgregarComentario" class="btn-filtrar mt-2">
                Agregar Comentario
            </button>

        </div>

    </div>
</div>

<!-- MODAL HISTORIAL -->
<div id="modalCalificar" class="modal-custom">
    <div class="modal-content-custom">
        <span class="close-modal">&times;</span>
        <h3>Calificación de Resolución de Incidencias

        <div style="margin-top: 10px;">
            <label for="ratingInput">Rating: <span id="ratingValue">2.5</span></label>
            <input type="range" id="ratingInput" min="0.5" max="5" step="0.5" value="2.5" style="width:100%;">
        </div>

        <div style="margin-top:10px;">
            <label for="comentarioCalificacion">Comentario / Recomendación:</label>
            <textarea id="comentarioCalificacion" class="form-control" placeholder="Escribe tu recomendación..."></textarea>
        </div>

        <button id="btnEnviarCalificacion" class="btn-filtrar mt-2">Enviar</button>
    </div>
</div>


@endsection



@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('app/js/inicio/index.js') }}"></script>

@endsection