@extends('auth.index')

@section('titulo')
<title>Gestión de Activos</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_registro_login/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_incidentes_registro/style.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #81C34D, #81C34D); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-users mr-2" style="margin-right: 8px;"></i> Gestión de Activos
        </h1>
        <ol class="breadcrumb" style="top: 15px !important;">
            <li class="breadcrumb-item">
                <button type="button" id="btnRegistrarActivo" class="btn-primary2">
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
                        @foreach(\DB::table('maestro_estado_activo')->get() as $est)
                        <option value="{{ $est->id }}">{{ $est->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Tipo</label>
                    <select id="filtroTipo" class="form-control">
                        <option value="">Todos</option>
                        @foreach(\DB::table('maestro_tipos_activos')->get() as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Ubicación</label>
                    <select id="filtroUbicacion" class="form-control">
                        <option value="">Todos</option>
                        @foreach(\DB::table('maestro_ubicacion_fisica')->get() as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
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
        <div class="form-section">
            <table id="tableActivos" class="table table-bordered table-striped display nowrap margin-top-10">
            </table>
        </div>

    </section>
</div>

<!-- MODAL REGISTRAR -->
<div class="modal fade" id="modalActivo">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Registrar Activo</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>×</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-2">
                    <label>Código Patrimonial</label>
                    <input type="text" id="codigo_patrimonial" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" id="nombre" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Número de Serie</label>
                    <input type="text" id="numero_serie" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Tipo</label>
                    <select id="tipo_id" class="form-control">
                        @foreach(\DB::table('maestro_tipos_activos')->get() as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Usuario Responsable</label>
                    <select id="usuario_responsable_id" class="form-control">
                        @foreach(\DB::table('users')->get() as $u)
                        <option value="{{ $u->id }}">{{ $u->nombres }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Criticidad</label>
                    <select id="criticidad_id" class="form-control">
                        @foreach(\DB::table('maestro_criticidad')->get() as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Confidencialidad</label>
                    <select id="confidencialidad_id" class="form-control">
                        @foreach(\DB::table('maestro_confidencialidad')->get() as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Ubicación</label>
                    <select id="ubicacion_id" class="form-control">
                        @foreach(\DB::table('maestro_ubicacion_fisica')->get() as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Estado</label>
                    <select id="estado_id" class="form-control">
                        @foreach(\DB::table('maestro_estado_activo')->get() as $e)
                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="guardarActivo">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarActivo">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Editar Activo</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>×</span>
                </button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id">

                <div class="mb-2">
                    <label>Código Patrimonial</label>
                    <input type="text" id="edit_codigo_patrimonial" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" id="edit_nombre" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Número de Serie</label>
                    <input type="text" id="edit_numero_serie" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Tipo</label>
                    <select id="edit_tipo_id" class="form-control">
                        @foreach(\DB::table('maestro_tipos_activos')->get() as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Usuario Responsable</label>
                    <select id="edit_usuario_responsable_id" class="form-control">
                        @foreach(\DB::table('users')->get() as $u)
                        <option value="{{ $u->id }}">{{ $u->nombres }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Criticidad</label>
                    <select id="edit_criticidad_id" class="form-control">
                        @foreach(\DB::table('maestro_criticidad')->get() as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Confidencialidad</label>
                    <select id="edit_confidencialidad_id" class="form-control">
                        @foreach(\DB::table('maestro_confidencialidad')->get() as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Ubicación</label>
                    <select id="edit_ubicacion_id" class="form-control">
                        @foreach(\DB::table('maestro_ubicacion_fisica')->get() as $u)
                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Estado</label>
                    <select id="edit_estado_id" class="form-control">
                        @foreach(\DB::table('maestro_estado_activo')->get() as $e)
                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="actualizarActivo">
                    Actualizar
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('app/js/activos/index.js') }}"></script>
@endsection