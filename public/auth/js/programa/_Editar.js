var OnSuccessRegistroPrograma, OnFailureRegistroPrograma;
$(function(){

    const $modal = $("#modalMantenimientoPrograma"), $form = $("form#registroPrograma");
    
    OnSuccessRegistroPrograma = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroPrograma = () => onFailureForm();
});