
var OnSuccessRegistroAviso, OnFailureRegistroAviso;
$(function(){

    const $modal = $("#modalMantenimientoAviso"), $form = $("form#registroAviso");
    
    OnSuccessRegistroAviso = (data) => onSuccessForm(data, $form, $modal);
    OnFailureRegistroAviso = () => onFailureForm();
});


$(document).on('change', '#solicita_grado_a', function(event) {
    inputs_validation()
});

function inputs_validation(){
    $("#ciclo_cursa").hide();
    var txt = $('#solicita_grado_a').val()
    if(txt != 0){
        $("#ciclo_cursa").hide();   
        $("#ciclo_cursa").attr('required', false);    
    }
    else{
        $("#ciclo_cursa").show();
        $("#ciclo_cursa").attr('required', true);
        valor = $("#ciclo_cursa").val();
        if(valor != ""){
            // $(".option_data").html("Grado Académico que solicita")
        }else{
            $(".option_data").html("Grado Académico que solicita")
        }
    }
}
inputs_validation()