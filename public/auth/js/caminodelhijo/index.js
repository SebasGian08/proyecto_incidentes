$(function () {
    const $table = $("#tableCursos");

    var $dataTable = $table.DataTable({
        columnDefs: [
            {
                defaultContent: "-",
                targets: "_all",
            },
        ],
        stripeClasses: ["odd-row", "even-row"],
        lengthChange: true,
        lengthMenu: [
            [10, 10, 20, 50, -1],
            [10, 10, 20, 50, "Todo"],
        ],
        info: false,
        ajax: {
            url: "/auth/caminodelhijo/lista-usuarios",
            dataSrc: "OlListaUsuarios", // Cambia esto para apuntar a OlListaUsuarios
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error en la carga de datos:", textStatus, errorThrown);
                alert("Error al cargar los datos. Revisa la consola para más detalles.");
            }
        },
        columns: [
            { title: "Nombre", data: "Nombres" },
            { title: "Apellidos", data: "Apellidos" },
            { title: "Curso", data: "CursoNombre" },
            { title: "Red", data: "Red" },
        ],
    });
});
