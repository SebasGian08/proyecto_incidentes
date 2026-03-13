var OnSuccessRegistroCalendario, OnFailureRegistroCalendario;
$(function(){

    const $modal = $("#modalMantenimientoCalendario"), $form = $("form#registroCalendario");

    OnSuccessRegistroCalendario = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroCalendario = () => onFailureForm();
});
