var OnSuccessRegistroProveedor, OnFailureRegistroProveedor;

$(function () {
    const $modal = $("#modalMantenimientoProveedores");
    const $form  = $("#registroProveedor");

    OnSuccessRegistroProveedor = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroProveedor = () => onFailureForm();
});
