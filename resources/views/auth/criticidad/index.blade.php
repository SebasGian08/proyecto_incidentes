@extends('auth.index')

@section('titulo')
<title>Gestión de Criticidad</title>
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
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff;">
            <i class="fa fa-bolt mr-2"></i> Gestión de Criticidad
        </h1>

        <button id="btnRegistrarCriticidad" class="btn-primary2">
            <i class="fa fa-plus"></i> Nuevo
        </button>
    </section>

    <!-- TABLA -->
    <section class="content mt-3">
        <div class="form-section">
            <table id="tableCriticidad" class="table table-bordered table-striped display nowrap margin-top-10"></table>
        </div>
    </section>

</div>

<!-- MODAL REGISTRAR -->
<div class="modal fade" id="modalCriticidad">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Registrar Criticidad</h5>
                <button class="close" data-bs-dismiss="modal"><span>×</span></button>
            </div>

            <div class="modal-body">
                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" id="nombre" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="guardarCriticidad">Guardar</button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarCriticidad">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Editar Criticidad</h5>
                <button class="close" data-bs-dismiss="modal"><span>×</span></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id">

                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" id="edit_nombre" class="form-control">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="actualizarCriticidad">Actualizar</button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('app/js/criticidad/index.js') }}"></script>
@endsection