@extends('auth.index')

@section('titulo')
<title>Reportes de Inventario</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
.kpi-card h6 {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 4px;
}

.kpi-card h3 {
    font-weight: 600;
    margin: 0;
}

.table-sm th,
.table-sm td {
    padding: .45rem;
    font-size: .85rem;
}

canvas {
    max-width: 100%;
}

#chartKardex {
    max-width: 100%;
    height: 360px !important;
    /* tamaño mediano */
}

#chartStock {
    max-width: 100%;
    height: 360px !important;
    /* tamaño mediano */
}

#chartMargen {
    max-width: 100%;
    height: 360px !important;
    /* tamaño mediano */
}

.kpi-card {
    border: none;
    border-radius: 16px;
    padding: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
    transition: all .25s ease;
}

.kpi-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 35px rgba(0, 0, 0, .15);
}

.kpi-header {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}

.kpi-icon {
    font-size: 28px;
    color: #fff;
}

.kpi-title {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #6c757d;
    margin-bottom: 4px;
}

.kpi-value {
    font-weight: 800;
    margin: 0;
}

/* Colores */
.kpi-primary .kpi-header {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
}

.kpi-danger .kpi-header {
    background: linear-gradient(135deg, #dc3545, #a71d2a);
}

.kpi-success .kpi-header {
    background: linear-gradient(135deg, #198754, #0f5132);
}

.kpi-info .kpi-header {
    background: linear-gradient(135deg, #0dcaf0, #087990);
}
</style>
@endsection

@section('contenido')
<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header d-flex justify-content-between align-items-center"
        style="padding:15px 25px; background:linear-gradient(to right,#5864ff,#646eff); border-radius:8px;">
        <h1 style="color:#fff; margin:0;">
            <i class="fa fa-line-chart mr-2"></i> Reportes de Inventario
        </h1>
    </section>

    <!-- KPI -->
    <div class="row mt-4 px-3 g-3">

        <div class="col-md-3">
            <div class="card kpi-card kpi-primary">
                <div class="card-body">
                    <div class="kpi-header">
                        <i class="bi bi-star-fill kpi-icon"></i>
                    </div>
                    <p class="kpi-title">Producto más vendido</p>
                    <h5 class="kpi-value" id="kpiTop">-</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kpi-card kpi-danger">
                <div class="card-body">
                    <div class="kpi-header">
                        <i class="bi bi-exclamation-triangle-fill kpi-icon"></i>
                    </div>
                    <p class="kpi-title">Productos críticos</p>
                    <h3 class="kpi-value" id="kpiStock">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kpi-card kpi-success">
                <div class="card-body">
                    <div class="kpi-header">
                        <i class="bi bi-cash-stack kpi-icon"></i>
                    </div>
                    <p class="kpi-title">Mayor margen</p>
                    <h5 class="kpi-value" id="kpiMargen">-</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kpi-card kpi-info">
                <div class="card-body">
                    <div class="kpi-header">
                        <i class="bi bi-arrow-left-right kpi-icon"></i>
                    </div>
                    <p class="kpi-title">Movimientos Kardex</p>
                    <h3 class="kpi-value" id="kpiMov">-</h3>
                </div>
            </div>
        </div>

    </div>


    <!-- TABS -->
    <div class="container-fluid mt-4">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#rotacion">Rotación</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#stock">Stock crítico</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kardex">Kardex</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#margen">Margen</a></li>
        </ul>

        <div class="tab-content pt-3">

            <!-- ROTACION -->
            <div class="tab-pane fade show active" id="rotacion">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <table id="tablaRotacion" class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Vendido</th>
                                            <th>Ingreso</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-5">
                                <canvas id="chartRotacion" height="180"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STOCK -->
            <div class="tab-pane fade" id="stock">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <table id="tablaStock" class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Stock</th>
                                            <th>Mínimo</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-5">
                                <canvas id="chartStock"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KARDEX -->
            <div class="tab-pane fade" id="kardex">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="table-responsive">
                                    <table id="tablaKardex" class="table table-sm" stlye="width:100% !important;">

                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Movimiento</th>
                                                <th>Cantidad</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                            </div>
                            <div class="col-md-5">
                                <canvas id="chartKardex"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MARGEN -->
            <div class="tab-pane fade" id="margen">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <table id="tablaMargen" class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Ingreso</th>
                                            <th>Costo</th>
                                            <th>Margen</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-5">
                                <canvas id="chartMargen"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ================== REPORTES EXCEL ================== -->
    <div class="container-fluid mt-5 mb-4 px-3">

        <div class="card shadow-sm">

            <!-- HEADER -->
            <div class="card-header d-flex justify-content-between align-items-center" style="background:#f8f9fa;">
                <h5 class="mb-0">
                    <i class="fa fa-file text-success mr-2"></i>
                    Exportar reportes
                </h5>

                <!-- FECHAS -->
                <div class="d-flex align-items-center">
                    <input type="date" id="fechaInicio" class="form-control form-control-sm mr-2" style="width:150px">
                    <span class="mx-1">–</span>
                    <input type="date" id="fechaFin" class="form-control form-control-sm mr-3" style="width:150px">

                    <span class="text-muted small">
                        Rango de fechas
                    </span>
                </div>
            </div>

            <!-- BODY -->
            <div class="card-body py-3">

                <div class="row">

                    <!-- INVENTARIO -->
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center h-100">
                            <i class="fa fa-cubes fa-2x text-success mb-2"></i>
                            <h6 class="mb-1">Inventario</h6>
                            <small class="text-muted d-block mb-3">
                                Stock y mínimos
                            </small>
                            <button class="btn btn-outline-success btn-sm btn-block"
                                onclick="descargarExcel('inventario')">
                                Descargar
                            </button>
                        </div>
                    </div>

                    <!-- KARDEX -->
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center h-100">
                            <i class="fa fa-random fa-2x text-success mb-2"></i>
                            <h6 class="mb-1">Kardex</h6>
                            <small class="text-muted d-block mb-3">
                                Movimientos
                            </small>
                            <button class="btn btn-outline-success btn-sm btn-block" onclick="descargarExcel('kardex')">
                                Descargar
                            </button>
                        </div>
                    </div>

                    <!-- MARGEN -->
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center h-100">
                            <i class="fa fa-line-chart fa-2x text-success mb-2"></i>
                            <h6 class="mb-1">Rentabilidad</h6>
                            <small class="text-muted d-block mb-3">
                                Ingresos y margen
                            </small>
                            <button class="btn btn-outline-success btn-sm btn-block" onclick="descargarExcel('margen')">
                                Descargar
                            </button>
                        </div>
                    </div>

                    <!-- SIN ROTACIÓN -->
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center h-100">
                            <i class="fa fa-ban fa-2x text-success mb-2"></i>
                            <h6 class="mb-1">Sin rotación</h6>
                            <small class="text-muted d-block mb-3">
                                Sin movimiento
                            </small>
                            <button class="btn btn-outline-success btn-sm btn-block"
                                onclick="descargarExcel('sin-rotacion')">
                                Descargar
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ================== FIN REPORTES EXCEL ================== -->



</div>
@endsection

@section('scripts')
<script src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function descargarExcel(tipo) {

    let inicio = $('#fechaInicio').val();
    let fin = $('#fechaFin').val();

    if (!inicio || !fin) {
        alert('Seleccione un rango de fechas');
        return;
    }

    window.open(
        `{{ url('auth/reportes/excel') }}/${tipo}?inicio=${inicio}&fin=${fin}`,
        '_blank'
    );
}
$(function() {

    // ROTACION
    $.get("{{ url('auth/reportes/rotacion') }}", function(r) {
        $('#kpiTop').text(r.data[0]?.descripcion || '-');

        new Chart(chartRotacion, {
            type: 'bar',
            data: {
                labels: r.data.slice(0, 8).map(x => x.descripcion),
                datasets: [{
                    label: 'Ventas',
                    data: r.data.slice(0, 8).map(x => x.total_vendido)
                }]
            }
        });

        $('#tablaRotacion').DataTable({
            data: r.data,
            columns: [{
                    data: 'descripcion'
                },
                {
                    data: 'total_vendido'
                },
                {
                    data: 'ingreso_total'
                },
                {
                    data: 'stock'
                }
            ],
            responsive: true,
            autoWidth: false,
            scrollX: false,
            ordering: true,
            searching: false,
            info: false
        });
    });

    // STOCK
    $.get("{{ url('auth/reportes/stock-critico') }}", function(r) {
        $('#kpiStock').text(r.data.length);

        new Chart(chartStock, {
            type: 'bar',
            data: {
                labels: r.data.map(x => x.descripcion),
                datasets: [{
                    label: 'Stock',
                    data: r.data.map(x => x.stock)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // 🔥 clave
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: true,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        $('#tablaStock').DataTable({
            data: r.data,
            columns: [{
                    data: 'descripcion'
                },
                {
                    data: 'stock'
                },
                {
                    data: 'stock_minimo'
                }
            ],
            responsive: true,
            autoWidth: false,
            scrollX: false,
            ordering: true,
            searching: false,
            info: false
        });
    });

    // KARDEX
    $.get("{{ url('auth/reportes/kardex') }}", function(r) {
        $('#kpiMov').text(r.data.length);

        new Chart(chartKardex, {
            type: 'pie',
            data: {
                labels: r.data.map(x => x.tipo_movimiento),
                datasets: [{
                    data: r.data.map(x => x.cantidad_total)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // 🔥 CLAVE
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 14,
                            padding: 15
                        }
                    }
                }
            }
        });


        $('#tablaKardex').DataTable({
            data: r.data,
            columns: [{
                    data: 'descripcion'
                },
                {
                    data: 'tipo_movimiento'
                },
                {
                    data: 'cantidad_total'
                },
                {
                    data: 'valor_total'
                }
            ],
            responsive: true,
            autoWidth: false,
            scrollX: false,
            ordering: true,
            searching: false,
            info: false
        });
    });

    // MARGEN
    $.get("{{ url('auth/reportes/margen') }}", function(r) {
        $('#kpiMargen').text(r.data[0]?.descripcion || '-');

        new Chart(chartMargen, {
            type: 'bar',
            data: {
                labels: r.data.slice(0, 8).map(x => x.descripcion),
                datasets: [{
                    label: 'Margen',
                    data: r.data.slice(0, 8).map(x => x.margen)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // 🔥 clave
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return 'Margen: ' + ctx.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: true,
                            maxRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        $('#tablaMargen').DataTable({
            data: r.data,
            columns: [{
                    data: 'descripcion'
                },
                {
                    data: 'ingreso'
                },
                {
                    data: 'costo'
                },
                {
                    data: 'margen'
                }
            ],
            responsive: true,
            autoWidth: false,
            scrollX: false,
            ordering: true,
            searching: false,
            info: false
        });
    });

});
</script>
@endsection