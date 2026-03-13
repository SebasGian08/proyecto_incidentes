$(function () {
    const $table = $("#tableCelula");

    var $dataTableCelula = $table.DataTable({
        columnDefs: [
            {
                defaultContent: "-",
                targets: "_all",
            },
        ],
        stripeClasses: ["odd-row", "even-row"],
        lengthChange: true,
        lengthMenu: [
            [10, 10, 20, 50, -1],
            [10, 10, 20, 50, "Todo"],
        ],
        info: false,
        ajax: {
            url: "/auth/celula/list_all",
            data: function (s) {},
        },
        columns: [
            {
                title: "N°",
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            { title: "Nombre de Celula", data: "nombre", class: "text-center" },
            { title: "Lider Encargado", data: "nombrelider", class: "text-center" },
            { title: "Descripción", data: "descripcion", class: "text-center" },
            {
                title: "Estado",
                data: "estado",
                render: function (data) {
                    return data === 1 || data === '1'
                        ? "<span class='estado-activo'>Activo</span>"
                        : "<span class='estado-inactivo'>Inactivo</span>";
                },
            },
            {
                data: null,
                render: function (data) {
                    return (
                        '<div class="btn-group">' +
                        '<a href="javascript:void(0)" class="btn-update btn btn-primary" idDato="' +
                        data.id +
                        '" style="margin-right: 5px;"><i class="fa fa-edit"></i> </a>' +
                        '<a href="javascript:void(0)" class="btn-delete btn btn-danger" idDato="' +
                        data.id +
                        '"><i class="fa fa-trash"></i> </a>' +
                        '<a href="javascript:void(0)" class="btn-view-participants btn btn-info" idDato="' +
                        data.id +
                        '" style="margin-left: 5px;"><i class="fa fa-users"></i></a>' +
                        '</div>'
                    );
                                     
                },
            },           
        ],
    });
    /* Para abrir modal y editar */
    $table.on("click", ".btn-update", function () {
        const id = $dataTableCelula.row($(this).parents("tr")).data().id;
        invocarModalView(id);
    });

    function invocarModalView(id) {
        invocarModal(`/auth/celula/partialView/${id ? id : 0}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") $dataTableCelula.ajax.reload(null, false);
        });
    }



    /* Para abrir modal y ver participantes */
    $table.on("click", ".btn-view-participants", function () {
        const id = $dataTableCelula.row($(this).parents("tr")).data().id;
        invocarModalViewParticipantesE(id);
    });

    function invocarModalViewParticipantesE(id) {
        invocarModal(`/auth/celula/partialViewAsistentes/${id ? id : 0}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") $dataTableCelula.ajax.reload(null, false);
        });
    }
    

    $table.on("click", ".btn-delete", function () {
        const id = $(this).attr("idDato");
        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);
        confirmAjax(
            `/auth/celula/delete`,
            formData,
            "POST",
            null,
            null,
            function () {
                $dataTableCelula.ajax.reload(null, false);
            }
        );
    });

    

    
    
});
