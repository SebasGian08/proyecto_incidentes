var OnSuccessActualizoPerfil, OnFailureActualizoPerfil;
$(function(){

    const $form = $('#actualizoPerfil');

    $form.on('submit', function (e) {
        e.preventDefault();
        for(var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }
        const formData = new FormData($(this)[0]);
        actionAjax("/empresa/perfil", formData, "POST", function(data){
            onSuccessForm(data, $form, null, true, function(){
                if(data.Success){
                }
            });
        });
    });

    OnFailureActualizoPerfil = () => onFailureForm();
});

console.log("indexx")

var TipoPersona = $("#tipo_de_persona").val();

if(TipoPersona == 1){

    $("#name_paciente").attr("required", false);
    $("#enfermedad_paciente").attr("required", false);
    $("#carga_evidencias").attr("required", false);


}else if(TipoPersona == 2){
    $(".title_data_nombre").html("Datos Generales")
    $("#nombre_comercial").attr("placeholder", "Nombre de la Persona Natural")
    $("#razon_social").attr("hidden", true)
    $("#razon_social").attr("required", false)
    $("#ruc").attr("placeholder", "DNI")
    $("#actividad_economica").attr("hidden", true)
    $("#actividad_economica").attr("required", false)

    $("#telefono").attr("hidden", true)
    $("#telefono").attr("required", false)

    $("#pagina_web").attr("hidden", true)

    $("#email").attr("hidden", true)
    $("#email").attr("required", false)

    $("#cargo_contacto").attr("hidden", true)
    $("#cargo_contacto").attr("required", false)

    $("#section_data_paciente").attr("hidden", false)

}else if(TipoPersona == 3){
    
    $("#name_paciente").attr("required", false);
    $("#enfermedad_paciente").attr("required", false);
    $("#carga_evidencias").attr("required", false);

    $("#telefono").attr("hidden", true)
    $("#telefono").attr("required", false)

    $("#pagina_web").attr("hidden", true)

    $("#email").attr("hidden", true)
    $("#email").attr("required", false)

    $("#cargo_contacto").attr("hidden", true)
    $("#cargo_contacto").attr("required", false)

}

