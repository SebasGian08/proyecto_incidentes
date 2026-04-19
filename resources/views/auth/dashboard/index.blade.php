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
        <style>
        .card-kpi {
            background: #fff;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            border-left: 5px solid #81C34D;
            transition: 0.3s;
        }

        .card-kpi:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.10);
        }

        .card-kpi-body {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .kpi-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: rgba(129, 195, 77, 0.12);
            color: #81C34D;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .kpi-info h2 {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
            color: #222;
        }

        .kpi-info p {
            margin: 0;
            font-size: 13px;
            color: #777;
        }
        </style>
        <div class="row">
            <div class="col-md-3 mt-2">
                <div class="card-kpi">
                    <div class="card-kpi-body">
                        <div class="kpi-icon">
                            <i class="fa fa-ticket"></i>
                        </div>
                        <div class="kpi-info">
                            <p>Incidencias Registradas</p>
                            <h2>{{ $totalIncidencias }}</h2>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="card-kpi">
                    <div class="card-kpi-body">
                        <div class="kpi-icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="kpi-info">
                            <p>Incidencias Resueltas</p>
                            <h2>{{ $porcentajeResueltas }}%</h2>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="card-kpi">
                    <div class="card-kpi-body">
                        <div class="kpi-icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="kpi-info">
                            <p>MTTR Promedio</p>
                            <h2>{{ $mttrPromedio }}min</h2>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="card-kpi">
                    <div class="card-kpi-body">
                        <div class="kpi-icon">
                            <i class="fa fa-shield"></i>
                        </div>
                        <div class="kpi-info">
                            <p>Cumplimiento SLA</p>
                            <h2>{{ $porcentajeSLA  }}%</h2>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- GRAFICO -->
        <div class="row">
            <div class="col-md-6 mt-2">
                <div style="background:#fff;padding:20px;border-radius:10px;">
                    <div id="graficoIncidencias"></div>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div style="background:#fff;padding:20px;border-radius:10px;">
                    <div id="graficoResumen"></div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6 mt-2">
                <div style="background:#fff;padding:20px;border-radius:10px;">
                    <div id="graficoActivos"></div>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div style="background:#fff;padding:20px;border-radius:10px;margin-top:20px;">
                    <div id="graficoSLA"></div>
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-md-6 mt-2">
                <div style="background:#fff;padding:20px;border-radius:10px;margin-top:20px;">
                    <div id="graficoSatisfaccion"></div>
                </div>
            </div>
        </div> -->

        <div class="row">
            <div class="col-md-6 mt-2">
                <div style="background:#fff;padding:20px;border-radius:10px;margin-top:20px;">
                    <div id="graficoSatisfaccion"></div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')

<script src="https://code.highcharts.com/12.1.2/highcharts.js"></script>
<script src="https://code.highcharts.com/12.1.2/highcharts-more.js"></script>
<script src="https://code.highcharts.com/12.1.2/modules/solid-gauge.js"></script>
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

Highcharts.chart('graficoActivos', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Activos TI por Estado'
    },

    xAxis: {
        type: 'category'
    },

    yAxis: {
        title: {
            text: 'Cantidad de activos'
        }
    },

    legend: {
        enabled: false
    },

    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },

    tooltip: {
        pointFormat: '<b>{point.y}</b> activos'
    },

    series: [{
        name: 'Estados',
        colorByPoint: true,
        data: @json($activosPorEstado)
    }]
});

Highcharts.chart('graficoSLA', {
    chart: {
        type: 'bar'
    },
    title: {
        text: '% Cumplimiento SLA por Severidad'
    },
    xAxis: {
        categories: @json($incidenciasSLA['categories'])
    },
    yAxis: {
        min: 0,
        max: 100,
        title: {
            text: 'Porcentaje (%)'
        }
    },
    legend: {
        reversed: true
    },
    plotOptions: {
        series: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                format: '{point.y}%'
            }
        }
    },
    tooltip: {
        pointFormat: '<b>{series.name}: {point.y}%</b>'
    },
    series: [{
        name: 'Dentro SLA',
        data: @json($incidenciasSLA['dentro']),
        color: '#28a745'
    }, {
        name: 'Fuera SLA',
        data: @json($incidenciasSLA['fuera']),
        color: '#dc3545'
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

Highcharts.chart('graficoSatisfaccion', {

    chart: {
        type: 'gauge',
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false,
        height: '80%'
    },

    title: {
        text: '% Satisfacción del Cliente'
    },

    pane: {
        startAngle: -90,
        endAngle: 90,
        background: null,
        center: ['50%', '75%'],
        size: '110%'
    },

    yAxis: {
        min: 0,
        max: 100,
        tickPixelInterval: 72,
        tickPosition: 'inside',
        labels: {
            distance: 20,
            style: {
                fontSize: '12px'
            }
        },
        lineWidth: 0,
        plotBands: [{
            from: 0,
            to: 60,
            color: '#DF5353'
        }, {
            from: 60,
            to: 85,
            color: '#DDDF0D'
        }, {
            from: 85,
            to: 100,
            color: '#55BF3B'
        }]
    },

    series: [{
        name: 'Satisfacción',
        data: [{{ $porcentajeSatisfaccion }}],
        tooltip: {
            valueSuffix: '%'
        },
        dataLabels: {
            format: '{y} %',
            style: {
                fontSize: '16px'
            }
        }
    }]
});
</script>

@endsection