var OnSuccessRegistroEditAsistente, OnFailureRegistroEditAsistente;
$(function(){

    const $modal = $("#modalMantenimientEdit"), $form = $("form#registroEditAsistente");

    OnSuccessRegistroEditAsistente = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroEditAsistente = () => onFailureForm();
});

