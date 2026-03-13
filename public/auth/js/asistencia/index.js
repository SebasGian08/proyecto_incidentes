// PARA SELECCIONAR CON UN SELECT
/* $(document).ready(function () {
    $("#celula_id").change(function () {
        var celulaId = $(this).val();

        if (celulaId) {
            $.ajax({
                type: "POST",
                url: "/auth/asistencia/asistentesPorCelula",
                data: {
                    id: celulaId,
                    _token: csrfToken,
                },
                dataType: "json",
                success: function (response) {
                    var $asistentesSelect = $("#asistente_id");
                    $asistentesSelect.empty(); // Limpia el select

                    // Añade la opción por defecto
                    $asistentesSelect.append(
                        '<option value="" disabled selected>Seleccione Asistente..</option>'
                    );

                    $.each(response, function (index, asistente) {
                        // Verifica que los campos existen en el objeto asistente
                        var nombreCompleto =
                            asistente.nombre + " " + asistente.apellido;
                        $asistentesSelect.append(
                            '<option value="' +
                                asistente.id +
                                '">' +
                                nombreCompleto +
                                "</option>"
                        );
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", status, error);
                },
            });
        } else {
            // Si no hay célula seleccionada, limpia el select de asistentes
            $("#asistente_id")
                .empty()
                .append(
                    '<option value="" disabled selected>Seleccione Asistente..</option>'
                );
        }
    });
}); */

//PARA SELECCIONAR CON UN CHECK LIST
$(document).ready(function () {
    $("#celula_id").change(function () {
        const celulaId = $(this).val();

        const $asistentesContainer = $("#asistentes-container");
        $asistentesContainer.empty(); // Limpia el contenedor

        if (celulaId) {
            $.ajax({
                type: "POST",
                url: "/auth/asistencia/asistentesPorCelula",
                data: {
                    id: celulaId,
                    _token: csrfToken,
                },
                dataType: "json",
                success: function (response) {
                    if (response.length > 0) {
                        response.forEach((asistente) => {
                            const nombreCompleto = `${asistente.nombre} ${asistente.apellido}`;
                            const checkboxHtml = `
                            <div class="form-check custom-checkbox">
                            <label class="form-check-label" 
                                   for="asistente_${asistente.id}" 
                                   id="label_asistente_${asistente.id}">
                                ${nombreCompleto}
                            </label>
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   value="${asistente.id}" 
                                   id="asistente_${asistente.id}" 
                                   name="asistente_id[]" 
                                   aria-labelledby="label_asistente_${asistente.id}">
                        </div>
                        
                            `;
                            $asistentesContainer.append(checkboxHtml);
                        });
                    } else {
                        $asistentesContainer.append(
                            "<p>No hay asistentes disponibles para esta célula.</p>"
                        );
                    }
                },
                error: function () {
                    $asistentesContainer.append(
                        "<p>Ocurrió un error al cargar los asistentes. Inténtalo de nuevo.</p>"
                    );
                },
            });
        } else {
            $asistentesContainer.append(
                "<p>No hay asistentes disponibles. Seleccione una célula primero.</p>"
            );
        }
    });
});

$(document).ready(function () {
    $("#celulas_id").change(function () {
        const celulaId = $(this).val();

        const $asistentesContainer = $("#asistentes-container");
        $asistentesContainer.empty(); // Limpia el contenedor

        if (celulaId) {
            $.ajax({
                type: "POST",
                url: "/asistentesPorCelula",
                data: {
                    id: celulaId,
                    _token: csrfToken,
                },
                dataType: "json",
                success: function (response) {
                    if (response.length > 0) {
                        response.forEach((asistente) => {
                            const nombreCompleto = `${asistente.nombre} ${asistente.apellido}`;
                            const checkboxHtml = `
                            <div class="form-check custom-checkbox">
                            <label class="form-check-label" 
                                   for="asistente_${asistente.id}" 
                                   id="label_asistente_${asistente.id}">
                                ${nombreCompleto}
                            </label>
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   value="${asistente.id}" 
                                   id="asistente_${asistente.id}" 
                                   name="asistente_id[]" 
                                   aria-labelledby="label_asistente_${asistente.id}">
                        </div>
                        
                            `;
                            $asistentesContainer.append(checkboxHtml);
                        });
                    } else {
                        $asistentesContainer.append(
                            "<p>No hay asistentes disponibles para esta célula.</p>"
                        );
                    }
                },
                error: function () {
                    $asistentesContainer.append(
                        "<p>Ocurrió un error al cargar los asistentes. Inténtalo de nuevo.</p>"
                    );
                },
            });
        } else {
            $asistentesContainer.append(
                "<p>No hay asistentes disponibles. Seleccione una célula primero.</p>"
            );
        }
    });
});

function clickExcel() {
    $(".dt-buttons .buttons-excel").click();
}

$(function () {
    const $table = $("#tableAsistencia");

    var $dataTableAsistencia = $table.DataTable({
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
            url: "/auth/asistencia/list_all",
            data: function (d) {
                d.fecha_exacta = $("#fecha_exacta").val();
                d.estado_asistencia = $("#estado_asistencia").val();
                d.celula_filter_id = $("#celula_filter_id").val();
            },
            dataSrc: "data", // Aquí se especifica que los datos están dentro de la propiedad `data` en la respuesta JSON
        },
        columns: [
            {
                title: "N°",
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            {
                title: "Programa",
                data: "programa.nombre_programa",
                class: "text-left",
            },
            {
                title: "Fecha de Programa",
                data: "fecha_registro",
                class: "text-left",
            },
            {
                title: "Celula",
                data: "celula.nombre_celula",
                class: "text-left",
            },
            {
                title: "Lider",
                data: "usuario.nombre",
                class: "text-center",
            },
            {
                title: "Asistente",
                data: "asistente",
                class: "text-left",
                render: function (data) {
                    return data
                        ? `${data.nombre_asistente} ${data.apellido_asistente}`
                        : "-";
                },
            },
            {
                title: "Estado",
                data: "estado",
                class: "text-left",
                render: function (data) {
                    switch (data) {
                        case "presente":
                            return '<span class="estado presente">Presente</span>';
                        case "ausente":
                            return '<span class="estado ausente">Ausente</span>';
                        case "justificado":
                            return '<span class="estado justificado">Justificado</span>';
                        default:
                            return '<span class="estado desconocido">Desconocido</span>';
                    }
                },
            },
            {
                title: "Motivo",
                data: "motivo",
                class: "text-left",
            },
            {
                data: null,
                render: function (data) {
                    return (
                        '<div class="btn-group">' +
                        '<a href="javascript:void(0)" class="btn-delete btn btn-danger" idDato="' +
                        data.id +
                        '"><i class="fa fa-trash"></i></a>' +
                        "</div>"
                    );
                },
            },
        ],
    });

    // Actualizar los datos de la tabla al hacer clic en el botón de consulta
    $("#btn-consultar").on("click", function () {
        $dataTableAsistencia.ajax.reload(null, false);
    });

    // Manejo de eliminación
    $table.on("click", ".btn-delete", function () {
        const id = $(this).attr("idDato");
        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);
        confirmAjax(
            `/auth/asistencia/delete`,
            formData,
            "POST",
            null,
            null,
            function () {
                $dataTableAsistencia.ajax.reload(null, false);
            }
        );
    });

});
