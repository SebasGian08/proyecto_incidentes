var OnSuccessRegistroEdit, OnFailureRegistroEdit;
$(function(){

    const $modal = $("#modalMantenimientEdit"), $form = $("form#registroEdit");
    
    OnSuccessRegistroEdit = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroEdit = () => onFailureForm();
});

/* Buscar DNI */
$("#buscardniespecialidad").click(function () {
    const dni = $("#dniedit");

    // Verificar si el campo dni está vacío
    if ($(dni).val().trim() === "") {
        swal("", "Ingrese el documento para buscar la información.", "warning");
        return; // Salir de la función si el dni está vacío
    }

    // Si el dni tiene exactamente 8 dígitos, continuar con la búsqueda
    $.ajax({
        url: "https://istalcursos.edu.pe/apirest/alumnos",
        type: "POST",
        data: { documento: $(dni).val() },
        dataType: "json",
        beforeSend: function () {
            $("#especialidadEdit").attr("placeholder", "Buscando ...");
        },
        success: function (res) {
            $("#especialidadEdit").attr("placeholder", "Especialidad");
            if (res.success === true) {
                const data = res.data[0];
                $("#especialidadEdit").val(data.especialidad);
                /* $("#especialidad").val(data.especialidad); */
                $("#validationDni")
                    .html("DNI correcto.")
                    .removeClass("text-muted")
                    .removeClass("text-danger")
                    .addClass("text-success");
                $(dni)
                    .removeClass("border-danger border-dark")
                    .addClass("border-success");
                $("#btn-registrar").prop("disabled", false);
            } else {
                swal("", "Usted no es alumno de esta institución.", "warning");
                $("#especialidadEdit").val("");
                $(dni)
                    .removeClass("border-success border-dark")
                    .addClass("border-danger");
                $("#validationDni")
                    .html("El DNI no existe en nuestros registros.")
                    .removeClass("text-muted")
                    .removeClass("text-success")
                    .addClass("text-danger");
            }
        },
        error: function () {
            swal(
                "",
                "Error al buscar información. Inténtelo nuevamente más tarde.",
                "error"
            );
        },
    });
});