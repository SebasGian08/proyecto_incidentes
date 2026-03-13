function clickExcel() {
    $(".dt-buttons .buttons-excel").click();
}

$(function () {
    const $table = $("#tableAsistentes");

    /* CALCULAR LA EDAD SEGUN LA FECHA DE NACIMIENTO */
    function calculateAge(birthDate) {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDifference = today.getMonth() - birth.getMonth();
        if (
            monthDifference < 0 ||
            (monthDifference === 0 && today.getDate() < birth.getDate())
        ) {
            age--;
        }
        return age;
    }

    var $dataTableAsistentes = $table.DataTable({
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
            url: "/auth/asistentes/list_all",
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
            /* { title: "DNI", data: "dni", class: "text-left" }, */
            {
                title: "Nombres y Apellidos",
                data: null,
                render: function (data, type, row) {
                    return row.nombre + " " + row.apellido; // Asegúrate de que 'nombre' y 'apellido' sean los nombres correctos
                },
                class: "text-center",
            },
            {
                title: "Fecha de Nacimiento",
                data: "fecha_nac",
                class: "text-center",
            },
            {
                title: "Edad",
                data: "fecha_nac",
                class: "text-center",
                render: function (data) {
                    return calculateAge(data);
                },
            },
            {
                title: "Distrito",
                data: "distrito_nombre",
                class: "text-center",
            },
            { title: "Dirección", data: "direccion", class: "text-center" },
            { title: "Teléfono", data: "tel", class: "text-center" },
            { title: "Género", data: "genero", class: "text-center" },
            { title: "Celula", data: "celula_nombre", class: "text-center" },
            { 
                title: "Bautizado", 
                data: "bautizado", 
                class: "text-center",
                render: function (data) {
                    return data === 1 || data === '1' ? "SI" : "NO";
                }
            },
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
                    // Formatear el número de teléfono para WhatsApp
                    var phoneNumber = data.tel;
                    var whatsappUrl = `https://wa.me/${phoneNumber}`;

                    return (
                        '<div class="btn-group">' +
                        '<a href="javascript:void(0)" class="btn-update btn btn-primary" idDato="' +
                        data.id +
                        '" style="margin-right: 5px;"><i class="fa fa-edit"></i> </a>' +
                        '<a href="javascript:void(0)" class="btn-delete btn btn-danger" idDato="' +
                        data.id +
                        '"><i class="fa fa-trash"></i> </a>' +
                        '<a href="' +
                        whatsappUrl +
                        '" target="_blank" class="btn-whatsapp btn btn-success" style="margin-left: 5px;"><i class="fa fa-whatsapp"></i> </a>' +
                        "</div>"
                    );
                },
            },
        ],
        drawCallback: function () {
            // Se ejecuta cada vez que se dibuja la tabla
            this.api()
                .rows()
                .every(function () {
                    var data = this.data();
                    var age = calculateAge(data.fecha_nac);
                    if (age > 25) {
                        $(this.node()).addClass("highlight-row");
                    } else {
                        $(this.node()).removeClass("highlight-row");
                    }
                });
        },
    });

    /* Para abrir modal y editar */
    $table.on("click", ".btn-update", function () {
        const id = $dataTableAsistentes.row($(this).parents("tr")).data().id;
        invocarModalView(id);
    });

    function invocarModalView(id) {
        invocarModal(
            `/auth/asistentes/partialView/${id ? id : 0}`,
            function ($modal) {
                if ($modal.attr("data-reload") === "true")
                    $dataTableAsistentes.ajax.reload(null, false);
            }
        );
    }

    $table.on("click", ".btn-delete", function () {
        const id = $(this).attr("idDato");
        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);
        confirmAjax(
            `/auth/asistentes/delete`,
            formData,
            "POST",
            null,
            null,
            function () {
                $dataTableAsistentes.ajax.reload(null, false);
            }
        );
    });

    $table.on("click", "tbody tr", function () {
        const data = $dataTableAsistentes.row(this).data();
        invocarModalView(data.id);
    });
    
    // Para evitar que el evento de clic en la fila se dispare al hacer clic en los botones
    $table.on("click", ".btn-update, .btn-delete", function (e) {
        e.stopPropagation(); // Evitar que el clic en los botones propague el evento hacia la fila
    });
    
});
