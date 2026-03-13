var OnSuccessRegistroCargo, OnFailureRegistroCargo;
$(function(){

    const $modal = $("#modalMantenimientoCargo"), $form = $("form#registroCargo");

    OnSuccessRegistroCargo = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroCargo = () => onFailureForm();
});
