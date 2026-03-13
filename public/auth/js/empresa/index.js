var $dataTableEmpresa, $dataTable;
const $table = $("#tableEmpresa");
const $actividad_eco_filter_id = $("#actividad_eco_filter_id");
const $ruc_dni = $("#ruc_dni");
const $fecha_desde = $("#fecha_desde");
const $fecha_hasta = $("#fecha_hasta");
console.log("arhivo cargado");

function clickExcel() {
    $(".dt-buttons .buttons-excel").click();
}

function consultarEmpleador() {
    $("#btn_mostrar").attr("mostrar", "");
    $dataTableEmpresa.ajax.reload();
}

function mostrarTodo() {
    $("#btn_mostrar").attr("mostrar", "mostrar");
    $dataTableEmpresa.ajax.reload();
}

$(function () {
    $dataTableEmpresa = $table.DataTable({
        stripeClasses: ["odd-row", "even-row"],
        lengthChange: true,
        lengthMenu: [
            [10, 20, 50, 100, -1],
            [10, 20, 50, 100, "Todo"],
        ],
        info: false,
        //"buttons": [],
        ajax: {
            url: "/auth/empresa/list_all",
            data: function (s) {
                if ($fecha_desde.val() != "") {
                    s.fecha_desde = $fecha_desde.val();
                }
                if ($fecha_hasta.val() != "") {
                    s.fecha_hasta = $fecha_hasta.val();
                }

                // Filtro por actividad económ
                if ($actividad_eco_filter_id.val() != "") {
                    s.actividad_eco_filter_id = $actividad_eco_filter_id.val();
                }

                // Filtro por RUC/DNI
                if ($ruc_dni.val() != "") {
                    s.ruc_dni = $ruc_dni.val();
                } else {
                }
                if ($("#btn_mostrar").attr("mostrar") != "") {
                    s.mostrar = $("#btn_mostrar").attr("mostrar");
                }
            },
        },
        columns: [
            {
                title: "N°",
                data: null,
                className: "text-center",
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            { title: "RUC", data: "ruc" },
            {
                title: "Razón Social",
                data: null,
                render: function (data) {
                    if (data.razon_social == null || data.razon_social == "") {
                        return data.nombre_comercial;
                    } else {
                        return data.razon_social;
                    }
                },
            },
            {
                title: "Actividad Económica",
                data: "actividad_economicas.descripcion",
                render: function (data) {
                    if (data) {
                        return data;
                    }
                    return "NO HAY CIIU REGISTRADO";
                },
            },
            {
                title: "Nombre de la Empresa",
                data: "nombre_comercial",
                class: "hidden",
            },
            {
                title: "Ciudad",
                data: "provincias.nombre",
                class: "hidden",
                render: function (data) {
                    if (data) {
                        return data;
                    }
                    return "NO HAY CIUDAD REGISTRADO";
                },
            },
            {
                title: "Distrito",
                data: "distritos.nombre",
                class: "hidden",
                render: function (data) {
                    if (data) {
                        return data;
                    }
                    return "NO HAY DISTRITO REGISTRADO";
                },
            },
            { title: "Dirección", data: "direccion", class: "hidden" },
            { title: "Teléfono Empresa", data: "telefono", class: "hidden" },
            { title: "E-mail", data: "email" },
            {
                title: "Nombre Contacto",
                data: null,
                render: function (data) {
                    if (data) {
                        return data.nombre_contacto;
                    }
                },
                orderable: false,
                searchable: false,
                width: "26px",
            },
            {
                title: "Cargo Contacto",
                data: "cargo_contacto",
                class: "hidden",
            },
            { title: "Teléfono Contacto", data: "telefono_contacto" },
            {
                title: "Email del Contacto",
                data: "email_contacto",
                class: "hidden",
            },
            {
                title: "Tipo de Persona",
                data: null,
                render: function (data) {
                    // console.log(data)
                    if (data.tipo_persona == 1) {
                        return "PERSONA JURIDICA";
                    } else if (data.tipo_persona == 2) {
                        return "PERSONA NATURAL";
                    } else if (data.tipo_persona == 3) {
                        return "PERSONA NATURAL CON NEGOCIO";
                    }
                    return "";
                },
                orderable: false,
                searchable: false,
                width: "35px",
            },
            {
                title: "Fecha de Registro",
                data: "created_at",
                render: function (data) {
                    if (data != null) return moment(data).format("DD-MM-YYYY");
                    return "-";
                },
            },
            // {
            //     data: null,
            //     render: function(data){
            //         if(data.aprobado == ESTADOS.APROBADO){
            //             return "<button type='button' class='btn btn-warning btn-xs btn-cancel' data-toggle='tooltip' title='Dar de baja'><i class='fa fa-ban'></i></button>";
            //         }
            //         return "";
            //     },
            //     "orderable": false,
            //     "searchable": false,
            //     "width": "26px"
            // },
            {
                data: null,
                render: function (data) {
                    if (data.aprobado == ESTADOS.CANCELADO) {
                        estado =
                            '<a class="dropdown-item btn-approved" href="#"><i class="fa fa-check"></i> Activar</a>';
                    } else if (data.aprobado == ESTADOS.APROBADO) {
                        estado =
                            '<a class="dropdown-item btn-cancel" href="#"><i class="fa fa-ban"></i> Bloquear</a>';
                    }
                    return `<div class="dropup">
                        <a class="" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                            </svg>
                        </a>
                        <div class="dropdown-menu p-0">
                        ${estado}
                        <a class="dropdown-item btn-update" href="#"><i class='fa fa-eye'></i> Más Información</a>
                        <a class="dropdown-item btn-delete" href="#"><i class='fa fa-trash'></i> Eliminar</a>
                        </div>
                    </div>`;
                },
                orderable: false,
                searchable: false,
                width: "26px",
            },
        ],
        rowCallback: function (row, data, index) {
            if (data.aprobado == ESTADOS.CANCELADO) {
                $("td", row).css({
                    "background-color": "#f87171",
                    color: "#fff",
                });
            }
        },
    });

    /* $actividad_eco_filter_id.on("change", function(){
        $dataTableEmpresa.ajax.reload();
    }) */

    $table.on("click", ".btn-update", function () {
        const id = $dataTableEmpresa.row($(this).parents("tr")).data().id;
        invocarModalView(id);
    });

    $table.on("click", ".btn-cancel", function () {
        const id = $dataTableEmpresa.row($(this).parents("tr")).data().id;
        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);
        formData.append("update_id", ESTADOS.CANCELADO);
        confirmAjax(
            `/auth/empresa/update`,
            formData,
            "POST",
            null,
            null,
            function () {
                $dataTableEmpresa.ajax.reload(null, false);
            }
        );
    });

    $table.on("click", ".btn-approved", function () {
        const id = $dataTableEmpresa.row($(this).parents("tr")).data().id;
        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);
        formData.append("update_id", ESTADOS.APROBADO);
        confirmAjax(
            `/auth/empresa/update`,
            formData,
            "POST",
            null,
            null,
            function () {
                $dataTableEmpresa.ajax.reload(null, false);
            }
        );
    });

    $table.on("click", ".btn-delete", function () {
        const id = $dataTableEmpresa.row($(this).parents("tr")).data().id;
        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);
        confirmAjax(
            `/auth/empresa/delete`,
            formData,
            "POST",
            null,
            null,
            function () {
                $dataTableEmpresa.ajax.reload(null, false);
            }
        );
    });

    $("#modalRegistrarEmpresa").on("click", function () {
        invocarModalView();
    });

    function invocarModalView(id) {
        invocarModal(
            `/auth/empresa/partialView/${id ? id : 0}`,
            function ($modal) {
                if ($modal.attr("data-reload") === "true")
                    $dataTableEmpresa.ajax.reload(null, false);
            }
        );
    }
});
