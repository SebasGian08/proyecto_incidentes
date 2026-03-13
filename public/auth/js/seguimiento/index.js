$(document).ready(function () {
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
}); 
