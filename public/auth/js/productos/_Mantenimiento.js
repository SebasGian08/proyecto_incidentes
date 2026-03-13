var OnSuccessRegistroProductos, OnFailureRegistroProductos;
$(function(){

    const $modal = $("#modalMantenimientoProductos"), $form = $("form#registroProductos");

    OnSuccessRegistroProductos = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroProductos = () => onFailureForm();
});

