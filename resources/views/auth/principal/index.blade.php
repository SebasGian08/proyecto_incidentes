@extends('auth.index')

@section('titulo')
    <title>BolsaTrabajo | Principal</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/css/inicio/core.css') }}">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css"> --}}
@endsection

<style type="text/css">
    .txt_claro {
        background: #79f57f63;
        /* color: #fff; */
    }

    .label-as-badge {
        border-radius: 1em;
        font-size: 12px;
        cursor: pointer;
    }

    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
    }

    .sorting_1 {
        padding-left: 30px !important;
    }
</style>

@section('contenido')
    <div class="content-wrapper">

        <section class="content-header d-flex justify-content-between align-items-center">
            <h2>
                <i class="fa fa-info-circle mr-5"></i> Instituto Arzobispo Loayza
            </h2>
            
            <!-- Botón de refresco alineado a la derecha -->
            <div>
                <a style="color:red;" href="javascript:void(0)" class="btn-m btn-secondary-m"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i> Cerrar Sesión
                </a>
            </div>
        </section>

        <br>

        <!-- Carrusel de imágenes -->
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" >
            <div class="carousel-inner" style="border-radius:20px !important;">
                <div class="carousel-item active">
                    <img class="d-block" src="https://www.ial.edu.pe/web_loayza/assets/img/imgactualizado/Portada_26.png"
                        alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block"
                        src="https://www.ial.edu.pe/web_loayza/assets/img/imgactualizado/beneficiosactual.webp"
                        alt="First slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <br>

        
        <div class="text-center mt-4">
            <a href="{{ route('auth.eventosasistencia') }}"
                class="btn btn-success btn-lg d-flex align-items-center justify-content-center">
                <i class="fa fa-check-circle mr-2"></i> Registrar Asistencia
            </a>
        </div>
       

        <br>
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
    {{-- Verficar Permiso Rol --}}
    <script type="text/javascript">
        const usuarioLoggin = {
            user_id: {{ \Illuminate\Support\Facades\Auth::guard('web')->user()->id }},
            profile_id: {{ \Illuminate\Support\Facades\Auth::guard('web')->user()->profile_id }}
        }
    </script>
    {{-- <script type="text/javascript" src="{{ asset('auth/js/inicio/index.js') }}"></script> --}}
@endsection
