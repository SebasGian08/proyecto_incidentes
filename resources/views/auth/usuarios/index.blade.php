@extends('auth.index')

@section('titulo')
<title>Registro de Usuarios</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
@endsection

@section('contenido')
<div class="content-wrapper">

    <section class="content-header d-flex justify-content-between align-items-center"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-users" style="margin-right: 8px;"></i> Listado de Usuarios
        </h1>
        <ol class="breadcrumb" style="top: 15px !important;">
            <li class="breadcrumb-item">
                <button type="button" id="modalRegistrarUsuarios" class="btn-primary"><i class="fa fa-plus"></i>
                    Registrar Usuario</button>
            </li>
        </ol>
    </section>
    <section class="content">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <br>
                <div class="form-section">
                    <table id="tableUsuarios"
                        class="table table-bordered table-striped display nowrap margin-top-10 dataTable no-footer">
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/plugins/datatable/dataTables.config.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/js/usuarios/index.js') }}"></script>
@endsection