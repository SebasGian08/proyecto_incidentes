@extends('auth.index')

@section('titulo')
    <title>BolsaTrabajo | Principal</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/css/inicio/core.css') }}">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css"> --}}
@endsection

@section('contenido')
    <div class="content-wrapper">

        <section class="content-header d-flex justify-content-between align-items-center">
            <h2>
                <i class="fa fa-info-circle mr-5"></i> Acceso no Autorizado
            </h2>

            <!-- Botón de refresco alineado a la derecha -->
            <div>
                <a style="color:red;" href="javascript:void(0)" class="btn-m btn-secondary-m"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i> Cerrar Sesión
                </a>
            </div>
        </section>

 
        <style>
            .img {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                /* Opcional, ajusta según tus necesidades */
            }

            .img img {
                width: 50%;
            }
        </style>
        <div class="img">
            <img src="{{ asset('auth/image/icon/noautorizado.png') }}" alt="Imagen no autorizada">
        </div>

    </div>
@endsection

@section('scripts')
    <!-- Incluir archivos de Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/boost.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/variwide.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endsection
