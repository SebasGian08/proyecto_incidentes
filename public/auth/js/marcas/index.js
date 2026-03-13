var $tableMarcas;

$(function () {

    const $table = $("#tableMarcas");

    $tableMarcas = $table.DataTable({
        ajax: {
            url: "/auth/marcas/list_all",
        },
        columns: [
            {
                title: "N°",
                data: null,
                render: (d, t, r, m) => m.row + 1
            },
            {
                title: "Descripción",
                data: "descripcion"
            },
            {
                title: "Estado",
                data: "estado",
                className: "text-center",
                render: d =>
                    d == 1
                        ? "<span class='estado-activo'>Activo</span>"
                        : "<span class='estado-inactivo'>Inactivo</span>"
            },
            {
                title: "Acciones",
                data: null,
                orderable: false,
                searchable: false,
                className: "text-center",
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-secondary btn-xs btn-update">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-xs btn-delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

    // ➕ NUEVO
    $("#modalRegistrarMarca").on("click", function () {
        invocarModalView();
    });

    // ✏️ EDITAR
    $table.on("click", ".btn-update", function () {
        const id = $tableMarcas.row($(this).parents("tr")).data().id_producto_marca;
        invocarModalView(id);
    });

    // 🗑️ ELIMINAR
    $table.on("click", ".btn-delete", function () {
        const id = $tableMarcas.row($(this).parents("tr")).data().id_producto_marca;

        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);

        confirmAjax(
            "/auth/marcas/delete",
            formData,
            "POST",
            null,
            null,
            () => $tableMarcas.ajax.reload(null, false)
        );
    });

    function invocarModalView(id = 0) {
        invocarModal(`/auth/marcas/partialView/${id}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") {
                $tableMarcas.ajax.reload(null, false);
            }
        });
    }

});
