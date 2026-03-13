var OnSuccessRegistroArea, OnFailureRegistroArea;
$(function(){

    const $modal = $("#modalMantenimientoArea"), $form = $("form#registroArea");

    OnSuccessRegistroArea = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroArea = () => onFailureForm();
});
