// JavaScript para el formulario
$(function () {
    const $table = $("#tableAsistentes");

    var $dataTableParticipante = $table.DataTable({
        stripeClasses: ["odd-row", "even-row"],
        lengthChange: true,
        lengthMenu: [
            [4, 10, 20, 50, -1],
            [4, 10, 20, 50, "Todo"],
        ],
        info: false,
        ajax: {
            url: "/auth/celula/mostrarAsistentes",
            data: function (params) {
                let celula_id = $(".celula_id").val(); // Obtener el valor del programa seleccionado
                console.log("Valor de celula_id:", celula_id); // Mostrar el valor en consola para depurar
    
                return {
                    celula_id: celula_id,
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
            { title: "DNI", data: "dni", class: "text-left" },
            {
                title: "Nombres y Apellidos",
                data: null,
                render: function (data, type, row) {
                    return row.nombre + " " + row.apellido; // Asegúrate de que 'nombre' y 'apellido' sean los nombres correctos
                },
                class: "text-left",
            },
            { title: "Fecha de Nacimiento", data: "fecha_nac", class: "text-left" },
            { title: "Dirección", data: "direccion", class: "text-left" },
            { title: "Teléfono", data: "tel", class: "text-left" },
            { title: "Distrito", data: "distrito", class: "text-left" },
            { title: "Género", data: "genero", class: "text-left" },
            {
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
            }
            
            
        ],
    });
    
    /* Para abrir modal y editar */
    $table.on("click", ".btn-seguimiento", function () {
        const id = $dataTableParticipante.row($(this).parents("tr")).data().id;
        invocarModalView(id);
    });

    function invocarModalView(id) {
        invocarModal(
            `/auth/celula/modalSeguimientoAsistentes/${id ? id : 0}`,
            function ($modal) {
                if ($modal.attr("data-reload") === "true")
                    $dataTableParticipante.ajax.reload(null, false);
            }
        );
    }

    
});

