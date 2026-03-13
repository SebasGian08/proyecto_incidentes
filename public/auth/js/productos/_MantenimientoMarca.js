var OnSuccessRegistroMarca, OnFailureRegistroMarca;
$(function(){

    const $modal = $("#modalAgregarMarca"), $form = $("form#registroMarca");

    OnSuccessRegistroMarca = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroMarca = () => onFailureForm();
});
