var OnSuccessRegistroMarca, OnFailureRegistroMarca;

$(function () {
    const $modal = $("#modalMantenimientoMarcas");
    const $form = $("#registroMarca");

    OnSuccessRegistroMarca = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroMarca = () => onFailureForm();
});
