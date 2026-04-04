@extends('auth.index')

@section('titulo')
<title>Gestión de Estado Activo</title>
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

        <h1 style="color: #fff;">
            <i class="fa fa-toggle-on mr-2"></i> Gestión de Estado Activo
        </h1>

        <button id="btnRegistrarEstadoActivo" class="btn-primary2">
            <i class="fa fa-plus"></i> Nuevo
        </button>
    </section>

    <!-- TABLA -->
    <section class="content mt-3">
        <div class="form-section">
            <table id="tableEstadoActivo" class="table table-bordered table-striped display nowrap"></table>
        </div>
    </section>

</div>

<!-- MODAL REGISTRAR -->
<div class="modal fade" id="modalEstadoActivo">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Registrar Estado Activo</h5>
                <button class="close" data-bs-dismiss="modal"><span>×</span></button>
            </div>

            <div class="modal-body">
                <label>Nombre</label>
                <input type="text" id="nombre" class="form-control">
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="guardarEstadoActivo">Guardar</button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarEstadoActivo">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Editar Estado Activo</h5>
                <button class="close" data-bs-dismiss="modal"><span>×</span></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id">
                <label>Nombre</label>
                <input type="text" id="edit_nombre" class="form-control">

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="actualizarEstadoActivo">Actualizar</button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('app/js/estado_activo/index.js') }}"></script>
@endsection