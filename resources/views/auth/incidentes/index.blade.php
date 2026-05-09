@extends('auth.index')

@section('titulo')
<title>Gestión de Incidencias</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_registro_login/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_incidentes_registro/style.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #81C34D, #81C34D); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-users mr-2" style="margin-right: 8px;"></i> Gestión de Incidencias
        </h1>
        <ol class="breadcrumb" style="top: 15px !important;">
            <li class="breadcrumb-item">
                <button type="button" id="btnRegistrarIncidente" class="btn-primary2">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
            </li>
        </ol>
    </section>
    <!-- FILTROS -->
    <section class="content mt-3">
        <div class="form-section">
            <div class="row mb-3">

                <div class="col-md-3">
                    <label>Estado</label>
                    <select id="filtroEstado" class="form-control">
                        <option value="">Todos</option>
                        @foreach(\DB::table('maestro_estado_ticket')->get() as $est)
                        <option value="{{ $est->nombre }}">{{ $est->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Criticidad</label>
                    <select id="filtroSeveridad" class="form-control">
                        <option value="">Todos</option>
                        @foreach(\DB::table('maestro_severidad')->get() as $cr)
                        <option value="{{ $cr->id }}">{{ $cr->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Activo</label>
                    <select id="filtroActivo" class="form-control">
                        <option value="">Todos</option>
                        @foreach(\DB::table('activos_ti')->get() as $act)
                        <option value="{{ $act->id }}">{{ $act->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Fecha</label>
                    <input type="date" id="fechaInicio" class="form-control mb-1">
                    <input type="date" id="fechaFin" class="form-control">
                </div>

            </div>
        </div>
        <!-- TABLA -->
        <section class="content">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-section">
                        <table id="tableIncidentes"
                            class="table table-bordered table-striped display nowrap margin-top-10 dataTable no-footer">
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>

<!-- MODAL REGISTRAR -->
<div class="modal fade" id="modalIncidente">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Registrar Incidente</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-2">
                    <label>Título</label>
                    <input type="text" id="titulo" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Descripción</label>
                    <textarea id="descripcion" class="form-control"></textarea>
                </div>

                <div class="mb-2">
                    <label>Severidad</label>
                    <select id="severidad" class="form-control">
                        @foreach(\DB::table('maestro_severidad')->get() as $cr)
                        <option value="{{ $cr->id }}">{{ $cr->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Estado</label>
                    <select id="estado" class="form-control">
                        @foreach(\DB::table('maestro_estado_ticket')->get() as $est)
                        <option value="{{ $est->id }}">{{ $est->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Activo</label>
                    <select id="activo_id" class="form-control">
                        <option value="" disabled selected>Seleccione el activo</option>
                        @foreach(\DB::table('activos_ti')->get() as $activo)
                        <option value="{{ $activo->id }}" {{ old('activo_id') == $activo->id ? 'selected' : '' }}>
                            {{ $activo->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @error('activo_id')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

                <div class="mb-2">
                    <label>Técnico</label>
                    <select id="tecnico_id" class="form-control">
                        <option value="">Sin asignar</option>
                        @foreach(\DB::table('users')->get() as $u)
                        <option value="{{ $u->id }}">{{ $u->nombres }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="guardarIncidente">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalGestionar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Gestionar Incidente</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">

                <p><b>Título:</b> <span id="g_titulo"></span></p>
                <p><b>Descripción:</b> <span id="g_descripcion"></span></p>
                <p><b>Activo:</b> <span id="g_activo"></span></p>
                <p><b>Severidad:</b> <span id="g_severidad"></span></p>

                <hr>

                <div class="mb-2">
                    <label>Técnico</label>
                    <select id="g_tecnico" class="form-control">
                        <option value="">Sin asignar</option>
                        @foreach(\DB::table('users')->where('profile_id', 3)->get() as $u)
                        <option value="{{ $u->id }}">{{ $u->nombres }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Estado</label>
                    <select id="g_estado" class="form-control">
                        @foreach(\DB::table('maestro_estado_ticket')->get() as $est)
                        <option value="{{ $est->id }}">{{ $est->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <hr>

                <h6>Historial</h6>
                <div id="historial"></div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="guardarGestion">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const API = {
    historial: "{{ route('incidentes.historial', ':id') }}"
};
</script>
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('app/js/gestionincidencias/index.js') }}"></script>
@endsection