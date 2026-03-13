$(function(){
    console.log('nueva carga');
    //codigo hecho por marco
    const $table = $("#tablePostulante");

    const $dataTablePostulante = $table.DataTable({
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }],
        "stripeClasses": ['odd-row', 'even-row'],
        "lengthChange": true,
        "lengthMenu": [[10,25,50,100,-1],[10,25,50,100,"Todo"]],
        "info": false,
        //"buttons": [],
        "ajax": {
            url: "/auth/aviso/list_all_postulantes",
            // url: "/auth/alumno/list_all",
            data: function (s) {
                s.id = $("#id").val();
            }
        },
        "columns": [
            { 
                title: "ID", data: "alumnos.id", className: "text-center"
            },
            { title: "Alumno", data: "alumnos", className: "text-center", render: function (data){
                return data.nombres + " " + data.apellidos;
            }},{
                title: "CV",data: "alumnos", className: "text-center", render: function (data){
                    return "<a target='_blank' href='/auth/alumno/print_cv_pdf/" + data.id + "' class='btn btn-success btn-xs p-1' data-toggle='tooltip' title='CV'><i class='fa fa-address-card'></i></button>";
            }},
            { title: "DNI", data: "alumnos.dni", className: "text-center" },
            { title: "Teléfono", data: "alumnos.telefono", className: "text-center" },
            {
                title: "Grado Acádemico", data: "alumnos", className: "text-center", render: function (data){
                    if (data.egresado == 0){
                        return "Estudiante";
                    }else if (data.egresado == 1){
                        return "Egresado";
                    }else if (data.egresado == 2){
                        return "Titulado";
                    }
            }},
            { title: "E-mail", data: "alumnos.email", className: "text-center" },
            { title: "Estado", data: "estados.nombre", className: "text-center" },
            { title: "Fecha de Registro", data: "created_at", render:function(data)
            {
                if(data != null)return moment(data).format("DD-MM-YYYY");
                return "No hay dato";
            }},
            {
                data: null,
                defaultContent:
                    "<button type='button' class='btn-primary p-3 btn-xs btn-editarEstado' data-toggle='tooltip' title='Editar Estado'><i class='fa fa-edit'></i></button>",
                "orderable": false,
                "searchable": false,
                "width": "26px"
            }
        ]
    });

    $table.on("click", ".btn-editarEstado", function () {
        const idalumno = $dataTablePostulante.row($(this).parents("tr")).data().alumno_id;
        const idaviso = $dataTablePostulante.row($(this).parents("tr")).data().aviso_id;
        invocarModalViewEditEstado(idalumno, idaviso)
    });
    
    function invocarModalViewEditEstado(idalumno, idaviso) {
        invocarModal(`/auth/aviso/partialViewEditarEstado/${idalumno ? idalumno : 0}/${idaviso ? idaviso : 0}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") $dataTablePostulante.ajax.reload(null, false);
        });
    }

});


