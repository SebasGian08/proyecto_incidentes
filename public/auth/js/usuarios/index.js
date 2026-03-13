var $tableUsuarios, $dataTable;
$(function () {
    const $table = $("#tableUsuarios");

    $tableUsuarios = $table.DataTable({
        stripeClasses: ["odd-row", "even-row"],
        lengthChange: true,
        lengthMenu: [
            [50, 100, 200, 500, -1],
            [50, 100, 200, 500, "Todo"],
        ],
        info: false,
        buttons: [],
        ajax: {
            url: "/auth/usuarios/list_all",
        },
        columns: [
            {
                title: "N°",
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            { title: "Nombres y Apellidos ", data: "nombres" },
            { title: "Usuario de Ingreso ", data: "email" },
            { title: "Perfil", data: "profile.name", className: "text-center" },
            {
                title: "Estado",
                data: "estado",
                render: function (data) {
                    return data === 1 || data === '1'
                        ? "<span class='estado-activo'>Activo</span>"
                        : "<span class='estado-inactivo'>Inactivo</span>";
                },
            },
            {
                title: "Online",
                data: null,
                className: "text-center",
                render: function(data, type, row) {
                    console.log('row.online:', row.online); // Para depuración
            
                    const onlineStatus = Number(row.online);
            
                    if (onlineStatus === 0) {
                        return "<p class='text-danger'>● OffLine</p>";
                    } else if (onlineStatus === 1) {
                        return "<p class='text-success animated-online'>● En Línea</p>";
                    } else {
                        return "<p class='text-muted'>● Desconocido</p>";
                    }
                }
            },
            {
                title: "Último inicio de sesión",
                data: "inicio_sesion",
                className: "text-center"
            },
            {
                title: "Último cierre de sesión",
                data: "cerrar_sesion",
                className: "text-center"
            },
            
            {
                data: null,
                defaultContent:
                    "<button type='button' class='btn btn-secondary btn-xs btn-update' data-toggle='tooltip' title='Actualizar'><i class='fa fa-pencil'></i></button>",
                orderable: false,
                searchable: false,
                width: "26px",
            },
            {
                data: null,
                defaultContent:
                    "<button type='button' class='btn btn-danger btn-xs btn-delete' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash'></i></button>",
                orderable: false,
                searchable: false,
                width: "26px",
            },
        ],
    });

    $table.on("click", ".btn-update", function () {
        const id = $tableUsuarios.row($(this).parents("tr")).data().id;
        invocarModalView(id);
    });

    $table.on("click", ".btn-delete", function () {
        const id = $tableUsuarios.row($(this).parents("tr")).data().id;
        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);
        confirmAjax(
            `/auth/usuarios/delete`,
            formData,
            "POST",
            null,
            null,
            function () {
                $tableUsuarios.ajax.reload(null, false);
            }
        );
    });

    $("#modalRegistrarUsuarios").on("click", function () {
        invocarModalView();
    });

    function invocarModalView(id) {
        invocarModal(
            `/auth/usuarios/partialView/${id ? id : 0}`,
            function ($modal) {
                if ($modal.attr("data-reload") === "true")
                    $tableUsuarios.ajax.reload(null, false);
            }
        );
    }
});
