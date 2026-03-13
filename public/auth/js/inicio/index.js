$(document).ready(function() {
    $('#filtro-form').submit(function() {
        $('#loading').show(); // Muestra el indicador de carga
        $('#filtro-submit').attr('disabled',
            true); 
        return true; 
    });
});

$(function () {
    const $table = $("#tableInicio");

    var $dataTableInicio = $table.DataTable({
        stripeClasses: ["odd-row", "even-row"],
        lengthChange: true,
        lengthMenu: [
            [10, 10, 20, 50, -1],
            [10, 10, 20, 50, "Todo"],
        ],
        info: false,
        ajax: {
            url: "/auth/inicio/listSeguimiento", // Ruta al endpoint del controlador
            dataSrc: 'data', // Especifica que los datos están en la propiedad "data"
        },
        columns: [
            {
                title: "N°",
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            { title: "Fecha de Contacto", data: "fecha_contacto", class: "text-center" },
            { title: "Asistente", data: "asistente_nombre", class: "text-center", render: function(data, type, row) {
                return row.asistente_nombre + ' ' + row.asistente_apellido;
            } },
            { title: "Celula", data: "celula_nombre", class: "text-center" },
            { title: "Motivo de oración ", data: "oracion", class: "text-center" },
        ],
    });
});

