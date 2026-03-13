<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Pedido {{ $pedido->codigo_pedido }}</title>

    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000;
        margin: 30px;
    }

    /* ===== CABECERA ===== */
    .header {
        width: 100%;
        margin-bottom: 20px;
    }

    .empresa {
        width: 65%;
        float: left;
    }

    .empresa h2 {
        margin: 0;
        font-size: 16px;
        text-transform: uppercase;
    }

    .empresa p {
        margin: 2px 0;
        font-size: 11px;
    }

    .documento {
        width: 35%;
        float: right;
        text-align: center;
        border: 1px solid #000;
        padding: 8px;
    }

    .documento h3 {
        margin: 0;
        font-size: 14px;
    }

    .clear {
        clear: both;
    }

    /* ===== BLOQUES ===== */
    .bloque {
        width: 100%;
        margin-top: 15px;
    }

    .bloque table {
        width: 100%;
        border-collapse: collapse;
    }

    .bloque td {
        padding: 6px;
        font-size: 11px;
    }

    .label {
        font-weight: bold;
        width: 20%;
    }

    /* ===== TABLA PRODUCTOS ===== */
    table.productos {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table.productos th {
        border: 1px solid #000;
        padding: 6px;
        background: #f0f0f0;
        font-size: 11px;
    }

    table.productos td {
        border: 1px solid #000;
        padding: 6px;
        font-size: 11px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    /* ===== TOTALES ===== */
    .totales {
        width: 40%;
        float: right;
        margin-top: 10px;
    }

    .totales table {
        width: 100%;
        border-collapse: collapse;
    }

    .totales td {
        padding: 6px;
        border: 1px solid #000;
        font-size: 11px;
    }

    .totales .label {
        font-weight: bold;
    }

    /* ===== OBSERVACIONES ===== */
    .observaciones {
        margin-top: 20px;
        font-size: 11px;
    }

    /* ===== FIRMA ===== */
    .firma {
        margin-top: 50px;
        width: 40%;
        text-align: center;
    }

    .firma hr {
        margin-bottom: 5px;
    }

    .footer-entrega {
        position: fixed;
        bottom: 30px;
        left: 30px;
        right: 30px;
    }
    </style>
</head>

<body>

    <!-- CABECERA -->
    <div class="header">
        <div class="empresa">
            <h2>ESSENTIUM GROUP E.I.R.L.</h2>
            <p>RUC: 20614652234</p>
            <p>Dirección: CAL. JOSE MARIA ARGUEDAS NRO. 237 URB. LUCYANA</p>
            <p>Teléfono: 980 812 235</p>
        </div>

        <div class="documento">
            <h3>GUÍA DE PEDIDO</h3>
            <p>N° {{ $pedido->codigo_pedido }}</p>
            <p>Fecha: {{ date('d/m/Y', strtotime($pedido->fecha_entrega)) }}</p>
        </div>
    </div>

    <div class="clear"></div>

    <!-- DATOS CLIENTE -->
    <div class="bloque">
        <table>
            <tr>
                <td class="label">Cliente:</td>
                <td>{{ $pedido->nombre_cliente }}</td>
                <td class="label">Teléfono:</td>
                <td>{{ $pedido->telefono_cliente }}</td>
            </tr>
            <tr>
                <td class="label">Dirección:</td>
                <td colspan="3">
                    {{ $pedido->direccion_envio }},
                    {{ $pedido->distrito }},
                    {{ $pedido->provincia }},
                    {{ $pedido->departamento }}
                </td>
            </tr>
        </table>
    </div>

    <!-- PRODUCTOS -->
    <table class="productos">
        <thead>
            <tr>
                <th style="width:10%">Código</th>
                <th>Descripción</th>
                <th style="width:10%">Cant.</th>
                <th style="width:15%">Precio Unit.</th>
                <th style="width:15%">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $d)
            <tr>
                <td class="text-center">{{ $d->codigo_producto }}</td>
                <td>{{ $d->descripcion }}</td>
                <td class="text-center">{{ $d->cantidad }}</td>
                <td class="text-right">S/ {{ number_format($d->precio_unitario, 2) }}</td>
                <td class="text-right">
                    S/ {{ number_format($d->cantidad * $d->precio_unitario, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALES -->
    <div class="totales">
        <table>
            <tr>
                <td class="label">Total</td>
                <td class="text-right">
                    S/ {{ number_format($pedido->total, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <div class="clear"></div>

    <!-- OBSERVACIONES -->
    <div class="observaciones">
        <strong>Observaciones:</strong>
        <p>Documento generado automáticamente. No válido como comprobante de pago.</p>
    </div>
    <!-- CONFORMIDAD DE ENTREGA -->
    <!-- CONFORMIDAD DE ENTREGA (FOOTER) -->
    <div class="footer-entrega">

        <table width="100%" style="border-collapse:collapse;">
            <tr>
                <!-- CLIENTE -->
                <td width="50%" style="text-align:center; padding:10px;">
                    <hr style="width:80%;">
                    <p><strong>RECIBÍ CONFORME</strong></p>
                    <p>Cliente / Receptor</p>
                    <p>DNI:</p>
                </td>

                <!-- DESPACHADOR -->
                <td width="50%" style="text-align:center; padding:10px;">
                    <hr style="width:80%;">
                    <p><strong>ENTREGUÉ CONFORME</strong></p>
                    <p>Despachador</p>
                    <p>DNI:</p>
                </td>
            </tr>
        </table>

    </div>


    <!-- FIRMA -->
    <!--  <div class="firma">
        <hr>
        <p>Firma y Sello</p>
    </div>
 -->
</body>

</html>