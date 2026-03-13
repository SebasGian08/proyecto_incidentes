var $tableClientes;

$(function () {

    const $table = $("#tableClientes");

    $tableClientes = $table.DataTable({
        ajax: { url: "/auth/clientes/list_all" },
        columns: [
            { title: "N°", data: null, render: (d, t, r, m) => m.row + 1 },
            { title: "Documento", data: "documento" },
            { title: "Nombres", data: "nombres" },
            {
                title: "Estado", data: "estado", className: "text-center",
                render: d => d == 1 ? "<span class='estado-activo'>Activo</span>" : "<span class='estado-inactivo'>Inactivo</span>"
            },
            {
                title: "Acciones", data: null, orderable: false, searchable: false, className: "text-center",
                render: () => `
<button class="btn btn-secondary btn-xs btn-update"><i class="fa fa-pencil"></i></button>
<button class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>`
            }
        ]
    });

    $("#btnRegistrarCliente").on("click", () => invocarModalView());

    $table.on("click", ".btn-update", function () {
        const id = $tableClientes.row($(this).parents("tr")).data().id_cliente;
        invocarModalView(id);
    });

    $table.on("click", ".btn-delete", function () {
        const id = $tableClientes.row($(this).parents("tr")).data().id_cliente;
        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);
        confirmAjax("/auth/clientes/delete", formData, "POST", null, null, () => $tableClientes.ajax.reload(null, false));
    });

    function invocarModalView(id = 0) {
        invocarModal(`/auth/clientes/partialView/${id}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") {
                $tableClientes.ajax.reload(null, false);
            }
        });
    }

});
