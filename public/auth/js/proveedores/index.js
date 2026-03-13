var $tableProveedores;

$(function () {

    const $table = $("#tableProveedores");

    $tableProveedores = $table.DataTable({
        ajax: {
            url: "/auth/proveedores/list_all",
        },
        columns: [
            {
                title: "N°",
                data: null,
                render: (d, t, r, m) => m.row + 1
            },
            {
                title: "Razón Social",
                data: "razon_social"
            },
            {
                title: "RUC",
                data: "ruc",
                className: "text-center"
            },
            {
                title: "Teléfono",
                data: "telefono",
                className: "text-center"
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
                render: function () {
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
    $("#btnRegistrarProveedor").on("click", function () {
        invocarModalView();
    });


    // ✏️ EDITAR
    $table.on("click", ".btn-update", function () {
        const id = $tableProveedores
            .row($(this).parents("tr"))
            .data().id_proveedor;

        invocarModalView(id);
    });

    // 🗑️ ELIMINAR
    $table.on("click", ".btn-delete", function () {
        const id = $tableProveedores
            .row($(this).parents("tr"))
            .data().id_proveedor;

        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);

        confirmAjax(
            "/auth/proveedores/delete",
            formData,
            "POST",
            null,
            null,
            () => $tableProveedores.ajax.reload(null, false)
        );
    });

    function invocarModalView(id = 0) {
        invocarModal(`/auth/proveedores/partialView/${id}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") {
                $tableProveedores.ajax.reload(null, false);
            }
        });
    }

});
