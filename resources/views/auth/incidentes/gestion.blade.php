@extends('auth.index')

@section('titulo')
<title>Mis Incidentes</title>
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
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-users mr-2" style="margin-right: 8px;"></i> Incidencias
        </h1>
    </section>

    <section class="content">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-section">
                    <table id="tablaGestion" class="table table-bordered table-striped">
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- MODAL GESTION -->
<div class="modal fade" id="modalGestionar">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Gestionar Incidente</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <p><b>Título:</b> <span id="g_titulo"></span></p>
                <p><b>Descripción:</b> <span id="g_descripcion"></span></p>

                <div class="mb-2">
                    <label>Estado</label>
                    <select id="g_estado" class="form-control" disabled>
                        @foreach(\DB::table('maestro_estado_ticket')->get() as $est)
                        <option value="{{ $est->id }}" {{ $est->id == 3 ? 'selected' : '' }}>
                            {{ $est->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label>Evidencia</label>
                    <div id="g_evidencia_ver"></div>
                </div>
                <div class="mb-2">
                    <label>Comentario de solución</label>
                    <textarea id="g_comentario" class="form-control"></textarea>
                </div>

                <div class="mb-2">
                    <label>Evidencia (opcional)</label>
                    <input type="file" id="g_evidencia" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id="guardarGestion">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const URL_INCIDENTES_MIS = "{{ route('incidentes.mis') }}";
    const URL_UPDATE_INCIDENTE = "{{ route('incidentes.update') }}";
</script>
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('app/js/incidencias/index.js') }}"></script>
@endsection