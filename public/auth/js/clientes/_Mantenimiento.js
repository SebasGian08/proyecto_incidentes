var OnSuccessRegistroCliente, OnFailureRegistroCliente;

$(function () {
    const $modal = $("#modalMantenimientoClientes");
    const $form = $("#registroCliente");

    OnSuccessRegistroCliente = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroCliente = () => onFailureForm();
});
