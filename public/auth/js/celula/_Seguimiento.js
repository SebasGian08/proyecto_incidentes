function clickExcel(){
    $('.dt-buttons .buttons-excel').click()
}

$(function () {
    const $table = $("#tableSeguimiento");

    var $dataTableSeguimiento = $table.DataTable({
        stripeClasses: ["odd-row", "even-row"],
        lengthChange: true,
        lengthMenu: [
            [4, 10, 20, 50, -1],
            [4, 10, 20, 50, "Todo"],
        ],
        info: false,
        ajax: {
            url: "/auth/celula/listSeguimiento",
            data: function (params) {
                let asistente_id = $(".asistente_id").val(); // Obtener el valor del asistente_id
                console.log("Valor de asistente_id:", asistente_id); // Mostrar el valor en consola para depurar
        
                // Agregar el asistente_id a los datos enviados al servidor
                return {
                    ...params,
                    asistente_id: asistente_id // Incluir asistente_id en la solicitud
                };
            },
        },
        columns: [
            {
                title: "N°",
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            { title: "Fecha Contacto", data: "fecha_contacto", class: "text-left" }, // Nueva columna para fecha_contacto
            { title: "Tipo Contacto", data: "tipo_contacto", class: "text-left" },
            { title: "Detalle", data: "detalle", class: "text-left" },
            { title: "Oracion", data: "oracion", class: "text-left" },
            /* {
                data: null,
                render: function (data) {
                    return (
                        '<div class="btn-group" style="margin-left: 5px;">' +
                        '<a href="javascript:void(0)" class="btn-seguimiento btn btn-info" idDato="' +
                        data.id +
                        '"><i class="fa fa-tachometer"></i> Registrar Seguimiento</a>' +
                        '</div>'
                    );
                },
            } */
        ],
    });
    
    

    
});

