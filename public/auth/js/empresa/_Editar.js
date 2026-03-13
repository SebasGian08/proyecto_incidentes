
var OnSuccessRegistroEmpresa, OnFailureRegistroEmpresa;
$(function(){

    const $modal = $("#modalMantenimientoEmpresa"), $form = $("form#registroEmpresa");
    
    OnSuccessRegistroEmpresa = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroEmpresa = () => onFailureForm();
});