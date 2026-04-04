@extends('auth.index')

@section('titulo')
<title>Gestión de Tipo de Activos</title>
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
            <i class="fa fa-tags mr-2"></i> Gestión de Tipo de Activos
        </h1>

        <ol class="breadcrumb" style="top: 15px !important;">
            <li class="breadcrumb-item">
                <button type="button" id="btnRegistrarTipo" class="btn-primary2">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
            </li>
        </ol>
    </section>

    <!-- TABLA -->
    <section class="content mt-3">
        <div class="form-section">
            <table id="tableTipoActivos" class="table table-bordered table-striped display nowrap margin-top-10">
            </table>
        </div>
    </section>

</div>

<!-- MODAL REGISTRAR -->
<div class="modal fade" id="modalTipoActivo">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Registrar Tipo de Activo</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>×</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" id="nombre" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Código</label>
                    <input type="text" id="codigo" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Descripción</label>
                    <textarea id="descripcion" class="form-control"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="guardarTipo">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarTipo">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Editar Tipo de Activo</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>×</span>
                </button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id">

                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" id="edit_nombre" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Código</label>
                    <input type="text" id="edit_codigo" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Descripción</label>
                    <textarea id="edit_descripcion" class="form-control"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary2" id="actualizarTipo">
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
<script src="{{ asset('app/js/tipo_activos/index.js') }}"></script>
@endsection