console.log("esto es nuevo js")
var $dataTableAviso;
const $table = $("#tableAvisoPostulantes"), $desde = $("#desde"), $hasta = $("#hasta"), $carrera = $("#carrera"), $provincia = $("#provincia"), $tipo_estudiante = $("#tipo_estudiante");

function consultarAvisosPostulantes(){
    $('#btn_mostrar').attr('mostrar', '')
    $dataTableAviso.ajax.reload();
}

function mostrarTodo(){
    $('#btn_mostrar').attr('mostrar', 'todo')
    $dataTableAviso.ajax.reload();
}

function clickExcelAvisosPostulantes(){
    $('.dt-buttons .buttons-excel').click()
} 

$(function(){
    $dataTableAviso = $table.DataTable({
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }],
        "stripeClasses": ['odd-row', 'even-row'],
        "lengthChange": true,
        "lengthMenu": [[10,20,50,100,-1],[10,20,50,100,"Todo"]],
        "info": false,
        "ajax": {
            url: "/auth/avisoPostulacion/list_all",
            data: function(s){
                if($('#btn_mostrar').attr('mostrar') != ''){ s.mostrar = $('#btn_mostrar').attr('mostrar') }
                s.desde = $desde.val()
                s.hasta = $hasta.val()
                s.carrera = $carrera.val()
                s.provincia = $provincia.val()
                s.tipo_estudiante = $tipo_estudiante.val() 
            }
        },
        "columns": [
            { title: "N°", 
              data : null,
              render: function(data, type, row, meta){
                return meta.row + 1;
              }
            },
            { title: "Fecha Postulación", data : 'fecha_postulacion'},
            { title: "Nombre", data: "nombres", class: "text-left"},
            { title: "Apellidos", data: "apellidos", class: "text-left"},
            { title: "DNI", data: "dni"},
            { title: "Carrera del Alumno", data:"carrera"},
            { title: "Grado Académico", data: "grado_academico"},
            { title: "Estado", data: "estado"},
            { title: "Titulo Oferta", data: "titulo", class: "text-left"},
            { title: "Empresa", data: "nombre_comercial", class: "text-left"},
            { title: "Ruc", data: "ruc"}
        ]
        
    });

});
