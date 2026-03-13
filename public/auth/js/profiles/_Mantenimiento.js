var OnSuccessRegistroProfile, OnFailureRegistroProfile;

$(function () {
    const $modal = $("#modalMantenimientoProfiles");
    const $form = $("#registroProfile");

    OnSuccessRegistroProfile = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroProfile = () => onFailureForm();
});
