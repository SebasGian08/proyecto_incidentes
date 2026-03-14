@extends('auth.index')

@section('titulo')
<title>Inicio</title>
@endsection


@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('auth/css/inicio/core.css') }}">

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<link rel="stylesheet" href="{{ asset('app/assets_registro_login/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_incidentes_registro/style.css') }}">
@endsection



@section('contenido')

<div class="content-wrapper">

    <section class="content-header d-flex justify-content-between align-items-center"
        style="padding:15px 25px;border-bottom:2px solid #e0e0e0;background:linear-gradient(to right,#5864ff,#646eff);border-radius:8px;">
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

                    <a class="navbar-brand" href="{{ route('index') }}">
                        <img src="{{ asset('app/img/logo2.png') }}" alt="Logo" class="logo" />
                    </a>

                    <h2>Registrar Incidente</h2>
                    <p class="sub-text">Completa los datos del incidente</p>

                </div>

                <form class="form form-incidente" action="{{ route('incidentes.store') }}" method="POST">

                    @csrf

                    <label>
                        <i class='bx bx-text'></i>
                        <input type="text" placeholder="Título del incidente" name="titulo" required>
                    </label>

                    <label>
                        <i class='bx bx-message-square-detail'></i>
                        <textarea name="descripcion" placeholder="Descripción detallada"></textarea>
                    </label>

                    <label style="position: relative; display: flex; align-items: center;">
                        <i class='bx bx-error-circle' style="position:absolute; left:10px;"></i>
                        <select name="severidad" required style="padding-left:35px; appearance:auto;">
                            <option value="" disabled selected>Seleccione la severidad</option>
                            @foreach(\DB::table('maestro_severidad')->get() as $sev)
                            <option value="{{ $sev->nombre }}">{{ $sev->nombre }}</option>
                            @endforeach

                        </select>
                    </label>


                    <label>
                        <i class='bx bx-cog'></i>

                        <select name="estado" required disabled>

                            @foreach(\DB::table('maestro_estado_ticket')->get() as $est)

                            <option value="{{ $est->nombre }}" {{ $est->nombre == 'Abierto' ? 'selected' : '' }}>
                                {{ $est->nombre }}
                            </option>

                            @endforeach
                        </select>
                    </label>
                    <input type="submit" value="Registrar Incidente">
                </form>
            </div>
        </div>



        <!-- TABLA INCIDENTES -->
        <div class="tabla-incidentes">

            <h4 style="margin-bottom:15px;">
                <i class='bx bx-list-ul'></i> Mis Incidentes
            </h4>

            <div class="table-responsive">

                <table class="table-sm" id="tablaIncidentes" style="width:100%;">

                    <thead style="background:#5864ff;color:white;">
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Estado</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Error en impresora</td>
                            <td>Abierto</td>
                            <td>
                                <button class="btn-detalle">Ver</button>
                            </td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>PC no enciende</td>
                            <td>En proceso</td>
                            <td>
                                <button class="btn-detalle">Ver</button>
                            </td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>Problema con Outlook</td>
                            <td>Cerrado</td>
                            <td>
                                <button class="btn-detalle">Ver</button>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


    </div>


</div>

@endsection



@section('scripts')




<script>
$(document).ready(function() {

    $('#tablaIncidentes').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        paging: true,
        pageLength: 10

    });
});
</script>

@endsection