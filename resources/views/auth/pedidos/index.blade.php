@extends('auth.index')

@section('titulo')
<title>Registrar Pedido</title>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('auth/plugins/datatable/datatables.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('app/assets_pedidos/style.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endsection

@section('contenido')
<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between align-items-center header-animado"
        style="padding: 15px 25px; border-bottom: 2px solid #e0e0e0; background: linear-gradient(to right, #5864ff, #646eff); border-radius: 8px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #fff; margin: 0; font-size: 1.8rem;">
            <i class="fa fa-shopping-cart" style="margin-right: 8px;"></i> Registrar Pedido
        </h1>
    </section>
    <div class="content">
        @if(session('success'))
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('auth.pedidos.store') }}" method="POST" id="form-pedido">
            @csrf

            {{-- DATOS DEL CLIENTE --}}
            <div class="form-section">
                <h5><i class="fa fa-user"></i> Datos del Cliente</h5>
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label>Usuario</label>
                        <select name="id_usuario" id="usuario_select" class="form-control" required>
                            <option value="{{ $userId }}" selected>{{ Auth::guard('web')->user()->nombres }}</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <label>N° Documento</label>
                        <div class="input-group">
                            <input id="num_doc" class="form-control" placeholder="Ingrese documento" maxlength="11">

                            <div class="input-group-append">
                                <button type="button" id="btnBuscarCliente" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>

                                <a href="{{ route('auth.clientes') }}" target="_blank" class="btn btn-success"
                                    title="Registrar nuevo cliente">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-5">
                        <label>Razón Social / Nombre</label>
                        <input id="razonSocial" name="razon_social" class="form-control">
                    </div>

                    <div class="form-group col-lg-9">
                        <label>Dirección del Cliente</label>
                        <input id="direccion" name="direccion" class="form-control">
                    </div>

                    <div class="form-group col-lg-3">
                        <label>Teléfono de contacto</label>
                        <input type="text" name="telefono" id="telefono" class="form-control"
                            placeholder="Ingrese teléfono" required>
                    </div>

                    
                </div>
            </div>

            <div class="form-section">
                <h5><i class="fa fa-truck"></i> Datos para el Envío</h5>
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label>Fecha de pedido</label>
                        <input type="date" name="fecha_pedido" class="form-control" value="{{ date('Y-m-d') }}"
                            readonly>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Fecha de entrega</label>
                        <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" required>
                    </div>


                    <div class="form-group col-lg-4">
                        <label>Método de pago</label>
                        <select name="metodo_pago" class="form-control" required>
                            <option value="" disabled selected>Seleccione método...</option>
                            @foreach($metodosPago as $metodo)
                            <option value="{{ $metodo->id_metodo_pago }}">{{ $metodo->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Punto de llegada *</label>
                        <select name="punto_llegada" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="almacen">Almacén Central</option>
                            <option value="tienda">Tienda Principal</option>
                            <option value="cliente">Dirección del Cliente</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Ubigeo *</label>
                        <select name="ubigeo_envio" id="ubigeo_envio" class="form-control" required>
                            <option value="">Seleccione ubigeo...</option>
                            @foreach($ubigeos as $ubigeo)
                            <option value="{{ $ubigeo->id_ubigeo }}">
                                {{ $ubigeo->departamento }} - {{ $ubigeo->provincia }} - {{ $ubigeo->distrito }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Rerefencia</label>
                        <input type="text" name="referencia" id="telefono" class="form-control"
                            placeholder="Ingrese Referencia" required>
                    </div>
                    <div class="form-group col-lg-12">
                        <label>Buscar dirección *</label>

                        <div class="input-group">
                            <input type="text" id="direccion_envio" name="direccion_envio" class="form-control"
                                placeholder="Ej: Av. Arequipa 123, Lima (ENTER)" autocomplete="off">

                            <div class="input-group-append">
                                <button type="button" id="btnMiUbicacion" class="btn btn-primary"
                                    title="Usar mi ubicación">
                                    <i class="fa fa-crosshairs"></i>
                                </button>
                            </div>
                        </div>

                        <small id="estado_busqueda" class="text-muted"></small>
                    </div>



                    <div class="form-group col-lg-12">
                        <label>Ubicación exacta en el mapa *</label>
                        <div id="mapa_envio" style="height:300px;border-radius:8px;"></div>
                        <small class="text-muted">Arrastra el marcador para precisar la ubicación</small>
                    </div>

                    <input type="hidden" name="latitud_envio" id="latitud_envio">
                    <input type="hidden" name="longitud_envio" id="longitud_envio">


                </div>
            </div>

            <div class="form-section">
                <h5><i class="fa fa-box"></i> Productos</h5>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="form-group col-lg-12">
                            <input type="text" id="buscarProducto" class="form-control"
                                placeholder="Buscar producto...">
                        </div>
                        <div class="productos-pos" id="productosPos">
                            @foreach($productos as $producto)
                            <div class="producto-card" data-id="{{ $producto->id_producto }}"
                                data-nombre="{{ $producto->descripcion }}" data-precio="{{ $producto->precio_venta }}">

                                <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->descripcion }}">

                                <div class="info">
                                    <h6>
                                        {{ $producto->descripcion }}
                                        <button class="btn btn-link btn-sm p-0 ml-2" type="button"
                                            data-toggle="collapse" data-target="#stock-{{ $producto->id_producto }}"
                                            aria-expanded="false" aria-controls="stock-{{ $producto->id_producto }}">
                                            Stock <i class="fa fa-chevron-down flecha"></i>
                                        </button>
                                    </h6>
                                    <p class="precio">S/ {{ number_format($producto->precio_venta, 2) }}</p>

                                    <div class="collapse mt-1" id="stock-{{ $producto->id_producto }}">
                                        <p class="stock stock-real">Stock real: {{ $producto->stock }}</p>
                                        <p class="stock stock-reservado">Stock reservado:
                                            {{ $producto->stock_reservado }}</p>
                                        <p
                                            class="stock stock-disponible {{ $producto->stock_disponible > 5 ? 'text-success' : ($producto->stock_disponible > 0 ? 'text-warning' : 'text-danger') }}">
                                            Disponible: {{ $producto->stock_disponible }}
                                        </p>
                                    </div>
                                </div>





                                <div class="acciones">
                                    <input type="number" class="cantidad" min="1" value="1">
                                    <button type="button" class="btn btn-add">Agregar</button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-15">
                            <button type="button" class="btn btn-light-custom btn-sm" id="prevPage">Anterior</button>
                            <span id="pageInfo" class="mx-2"></span>
                            <button type="button" class="btn btn-light-custom btn-sm" id="nextPage">Siguiente</button>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <table class="table table-bordered" id="ticket-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="totals-ticket">
                            <p>Subtotal: S/ <span id="subtotal-ticket">0.00</span></p>
                            <p>IGV (18%): S/ <span id="igv-ticket">0.00</span></p>
                            <p><strong>Total: S/ <span id="total-ticket">0.00</span></strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <button type="submit" class="btn btn-light-custom btn-lg" style="width: 100%;">
                    <i class="fa fa-save"></i> Registrar Pedido
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('fecha_entrega');
    const hoy = new Date().toISOString().split('T')[0];
    input.min = hoy;
});
$(document).ready(function() {
    $('#ubigeo_envio').select2({
        placeholder: 'Buscar ubigeo...',
        allowClear: true,
        width: '100%'
    });

    function actualizarStock() {
        $.ajax({
            url: '/auth/pedidos/stock-actualizado',
            method: 'GET',
            success: function(productos) {
                productos.forEach(p => {
                    const card = $(`.producto-card[data-id='${p.id_producto}']`);
                    if (card.length) {
                        card.find('.stock-real').text(`Stock real: ${p.stock}`);
                        card.find('.stock-reservado').text(
                            `Stock reservado: ${p.stock_reservado}`);
                        card.find('.stock-disponible').text(
                            `Disponible: ${p.stock_disponible}`);

                        // Opcional: cambiar color según disponibilidad
                        const dispElem = card.find('.stock-disponible');
                        dispElem.removeClass('text-success text-warning text-danger');
                        if (p.stock_disponible == 0) dispElem.addClass('text-danger');
                        else if (p.stock_disponible <= 5) dispElem.addClass('text-warning');
                        else dispElem.addClass('text-success');
                    }
                });
            }
        });
    }

    // Llamar cada 5 segundos
    setInterval(actualizarStock, 5000);


    function buscarCliente() {
        let documento = $('#num_doc').val().trim();
        if (documento === '') return;

        $.ajax({
            url: '/auth/clientes/search',
            method: 'GET',
            data: {
                documento: documento
            },
            success: function(res) {
                if (res && res.cliente) {
                    $('#razonSocial').val(res.cliente.nombres);
                    $('#direccion').val(res.cliente.direccion);
                    $('#telefono').val(res.cliente.telefono);
                    $('#email').val(res.cliente.email);
                } else {
                    alert('Cliente no encontrado');
                    $('#razonSocial, #direccion, #telefono, #email').val('');
                }
            },
            error: function() {
                alert('Error al buscar cliente');
            }
        });
    }

    // ✅ Botón de buscar
    $('#btnBuscarCliente').on('click', buscarCliente);

    // ✅ Enter en input de documento
    $('#num_doc').on('keypress', function(e) {
        if (e.which === 13) { // Enter
            e.preventDefault();
            buscarCliente();
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    let bloqueado = false;
    const cache = {};

    const map = L.map('mapa_envio').setView([-12.0464, -77.0428], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const marker = L.marker([-12.0464, -77.0428], {
        draggable: true
    }).addTo(map);

    function setCoords(lat, lng, actualizarTexto = true) {
        lat = parseFloat(lat);
        lng = parseFloat(lng);

        marker.setLatLng([lat, lng]);
        map.setView([lat, lng], 17);

        $('#latitud_envio').val(lat);
        $('#longitud_envio').val(lng);

        if (actualizarTexto) reverseGeocode(lat, lng);
    }

    function reverseGeocode(lat, lng) {
        if (bloqueado) return;
        bloqueado = true;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(r => r.json())
            .then(data => {
                if (data.display_name) {
                    $('#direccion_envio').val(data.display_name);
                }
            })
            .finally(() => bloqueado = false);
    }

    // Buscar por texto (ENTER)
    $('#direccion_envio').on('keydown', function(e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();

        const q = this.value.trim();
        if (q.length < 5) return;

        if (cache[q]) {
            setCoords(cache[q].lat, cache[q].lon, false);
            return;
        }

        fetch(
                `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(q)}`
            )
            .then(r => r.json())
            .then(data => {
                if (data.length) {
                    cache[q] = data[0];
                    setCoords(data[0].lat, data[0].lon, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ubicación no detectada',
                        text: 'No se pudo obtener tu ubicación actual. Verifica los permisos.'
                    });
                }
            });
    });

    // Click en mapa
    map.on('click', e => {
        setCoords(e.latlng.lat, e.latlng.lng);
    });

    // Arrastrar marcador
    marker.on('dragend', e => {
        const pos = e.target.getLatLng();
        setCoords(pos.lat, pos.lng);
    });

    $('#btnMiUbicacion').on('click', function() {

        if (!navigator.geolocation) {
            Swal.fire({
                icon: 'warning',
                title: 'Geolocalización no disponible',
                text: 'Tu navegador no soporta obtener la ubicación actual.'
            });
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        navigator.geolocation.getCurrentPosition(
            function(position) {

                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                setCoords(lat, lng);

                btn.prop('disabled', false).html('<i class="fa fa-crosshairs"></i>');
            },
            function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Ubicación no detectada',
                    text: 'No se pudo obtener tu ubicación. Revisa los permisos del navegador.'
                });

                btn.prop('disabled', false).html('<i class="fa fa-crosshairs"></i>');
            }, {
                enableHighAccuracy: true,
                timeout: 10000
            }
        );
    });

});
</script>




<script type="text/javascript" src="{{ asset('auth/js/pedidos/index.js') }}"></script>

@endsection