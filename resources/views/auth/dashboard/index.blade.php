@extends('auth.index')

@section('titulo')
<title>Dashboard Incidencias</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_registro_login/style.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_incidentes_registro/style.css') }}">
@endsection


@section('contenido')
<div class="content-wrapper">

    <section class="content-header d-flex justify-content-between align-items-center"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #81C34D, #81C34D); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            Dashboard
        </h1>
        <a href="javascript:void(0)" class="btn btn-primary2" onclick="window.location.reload();">
            <i class="fa fa-refresh"></i> Refrescar
        </a>
    </section>

    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="form-section">
                    <form method="GET" action="{{ route('auth.dashboard') }}">
                        <div class="row align-items-end">

                            <div class="col-md-3">
                                <label>Desde</label>
                                <input type="date" name="fecha_desde" class="form-control" value="{{ $fechaDesde }}">
                            </div>

                            <div class="col-md-3">
                                <label>Hasta</label>
                                <input type="date" name="fecha_hasta" class="form-control" value="{{ $fechaHasta }}">
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-primary1 w-100">
                                    <i class="fa fa-filter"></i> Filtrar
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- GRAFICO -->
        <div class="row">
            <div class="col-md-6">
                <div style="background:#fff;padding:20px;border-radius:10px;">
                    <div id="graficoIncidencias"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div style="background:#fff;padding:20px;border-radius:10px;">
                    <div id="graficoResumen"></div>
                </div>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div style="background:#fff;padding:20px;border-radius:10px;">
                    <div id="graficoMTTR"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div style="background:#fff;padding:20px;border-radius:10px;margin-top:20px;">
                    <div id="graficoSLA"></div>
                </div>
            </div>
        </div>

        <br>
        <div class="row">
            <div class="col-md-6">
                <div style="background:#fff;padding:20px;border-radius:10px;margin-top:20px;">
                    <div id="graficoSatisfaccion"></div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/10.3.3/highcharts.js"></script>

<script>
//grafico incidencias por estado
let incidenciasPorEstado = @json($incidenciasPorEstado);
//grafico resumen
let incidenciasResumen = @json($incidenciasResumen);

Highcharts.chart('graficoIncidencias', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Número de incidencias registradas'
    },
    tooltip: {
        pointFormat: '<b>{point.name}: {point.y}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y}'
            }
        }
    },
    series: [{
        name: 'Incidencias',
        colorByPoint: true,
        data: incidenciasPorEstado
    }]
});

Highcharts.chart('graficoResumen', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Incidencias Resueltas vs Pendientes'
    },
    tooltip: {
        pointFormat: '<b>{point.name}: {point.y}</b>'
    },
    plotOptions: {
        pie: {
            innerSize: '60%',
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y}'
            }
        }
    },
    series: [{
        name: 'Incidencias',
        colorByPoint: true,
        data: incidenciasResumen
    }]
});

Highcharts.chart('graficoMTTR', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'MTTR - Tiempo Medio de Resolución (minutos)'
    },
    xAxis: {
        categories: @json($mttr['categories'])
    },
    yAxis: {
        title: {
            text: 'Minutos'
        }
    },
    series: [{
        name: 'Minutos',
        data: @json($mttr['series'])
    }]
});

Highcharts.chart('graficoSLA', {
    chart: {
        type: 'column'
    },
    title: {
        text: '% Incidencias Resueltas dentro del SLA'
    },
    xAxis: {
        categories: @json($incidenciasSLA['categories'])
    },
    yAxis: {
        title: {
            text: 'Porcentaje (%)'
        },
        max: 100
    },
    tooltip: {
        pointFormat: '{point.y}%'
    },
    series: [{
        name: 'Cumplimiento SLA',
        data: @json($incidenciasSLA['series']),
        color: '#81C34D'
    }]
});

Highcharts.chart('graficoSatisfaccion', {
    chart: {
        type: 'column'
    },
    title: {
        text: '% Satisfacción del Cliente'
    },
    xAxis: {
        categories: @json($satisfaccion['categories'])
    },
    yAxis: {
        title: {
            text: 'Porcentaje (%)'
        },
        max: 100
    },
    tooltip: {
        pointFormat: '{point.y}%'
    },
    series: [{
        name: 'Satisfacción',
        data: @json($satisfaccion['series']),
        color: '#007BFF'
    }]
});
</script>

@endsection