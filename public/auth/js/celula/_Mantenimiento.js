var OnSuccessRegistroCelulas, OnFailureRegistroCelulas;
$(function(){

    const $modal = $("#modalMantenimientoCelulas"), $form = $("form#registroCelulas");

    OnSuccessRegistroCelulas = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroCelulas = () => onFailureForm();
});
