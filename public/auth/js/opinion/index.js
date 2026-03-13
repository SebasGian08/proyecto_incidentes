$(function () {
    const $table = $("#tableOpiniones");

    const loadData = (dateFrom = '', dateTo = '') => {
        $tableAniversario = $table.DataTable({
            stripeClasses: ["odd-row", "even-row"],
            lengthChange: true,
            lengthMenu: [
                [50, 100, 200, 500, -1],
                [50, 100, 200, 500, "Todo"],
            ],
            info: false,
            buttons: [],
            ajax: {
                url: "/auth/opinion/list_all",
                type: 'GET',
                data: { date_from: dateFrom, date_to: dateTo },
                dataSrc: "",
            },
            columns: [
                {
                    title: "N°",
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { title: "Opinion", data: "opinion" },
                { title: "Puntuación", data: "rating" },
                { title: "Fecha", data: "created_at" },
            ],
        });
    };

    // Carga inicial de datos
    loadData();

    // Evento de filtrado
    $("#filterButton").on('click', function () {
        const dateFrom = $("#dateFrom").val();
        const dateTo = $("#dateTo").val();
        
        $table.DataTable().destroy(); // Destruir la tabla actual
        loadData(dateFrom, dateTo); // Cargar datos filtrados
    });
});
