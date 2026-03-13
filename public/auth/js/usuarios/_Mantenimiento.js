var OnSuccessRegistroUsuarios, OnFailureRegistroUsuarios;
$(function(){

    const $modal = $("#modalMantenimientoUsuarios"), $form = $("form#registroUsuarios");

    OnSuccessRegistroUsuarios = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroUsuarios = () => onFailureForm();
});
