var $tableAniversario;

function clickExcel() {
    $(".dt-buttons .buttons-excel").click();
}
$(function () {
    const $table = $("#tableAniversario");

    // Función para cargar los datos con o sin filtros
    const loadData = (dateFrom = '', dateTo = '') => {
        if ($.fn.dataTable.isDataTable($table)) {
            $table.DataTable().destroy(); // Destruir la tabla si ya existe
        }
    
        $tableAniversario = $table.DataTable({
            stripeClasses: ["odd-row", "even-row"],
            lengthChange: true,
            lengthMenu: [
                [50, 100, 200, 500, -1],
                [50, 100, 200, 500, "Todo"],
            ],
            info: false,
             buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Lista Aniversarios',
                    className: 'btn btn-success' // Puedes agregar una clase de Bootstrap o personalizar
                }
            ],
            ajax: {
                url: "/auth/aniversario/list_all",
                type: 'GET',
                data: { date_from: dateFrom, date_to: dateTo },
                dataSrc: '', // Asegúrate de que tus datos están en el nivel correcto
            },
            columns: [
                {
                    title: "N°",
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { title: "Nombres y Apellidos", data: "nombre", orderable: true },
                { title: "Celula", data: "celula_nombre" },
                { title: "Teléfono", data: "tel", orderable: true },
                { title: "Foto", data: "foto" },
                {
                    title: "Estado",
                    data: null,
                    render: function (data) {
                        return data.foto ? 
                            `<span class="estado-pagado"><i class="fa fa-check"></i> Pagado</span>` : 
                            `<span class="estado-faltante"><i class="fa fa-times"></i> Faltante</span>`;
                    },
                },
                { title: "Fecha de Registro", data: "created_at" },
                {
                    data: null,
                    render: function (data) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="javascript:void(0)" class="btn-view btn btn-primary" idDato="' +
                            data.id +
                            '"><i class="fa fa-eye"></i> Ver</a>' +
                            "</div>"
                        );
                    },
                },
            ],
        });
    };

    // Cargar datos inicialmente sin filtros
    loadData();

    
    // Evento de filtrado
    $("#filterButton").on('click', function () {
        const dateFrom = $("#dateFrom").val();
        const dateTo = $("#dateTo").val();
        loadData(dateFrom, dateTo); // Cargar datos filtrados
    });

    $table.on("click", ".btn-view", function () {
        const id = $(this).attr("idDato");
        invocarModalView(id);
    });

    function invocarModalView(id) {
        invocarModal(`/auth/aniversario/partialView/${id ? id : 0}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") $tableAniversario.ajax.reload(null, false);
        });
    }
    
    
});
