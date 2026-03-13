$(function () {
    var $tableProductos = $('#tableProductos').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10, // cantidad por página
        lengthMenu: [5, 10, 25, 50],
        scrollX: true, // agrega scroll horizontal si hay muchas columnas
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "→",
                previous: "←"
            },
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "No se encontraron productos",
            info: "Mostrando _START_ a _END_ de _TOTAL_ productos",
            infoEmpty: "No hay productos disponibles",
            infoFiltered: "(filtrado de _MAX_ productos totales)"
        },
        ajax: {
            url: '/auth/productos/list_all',
            type: 'GET'
        },
        columns: [
            { data: 'id_producto', title: 'ID' },
            { data: 'descripcion', title: 'Producto' },
            { data: 'precio_venta', title: 'Precio Venta', render: $.fn.dataTable.render.number(',', '.', 2, 'S/.') },
            { data: 'stock', title: 'Stock' },
            {
                data: 'estado',
                title: 'Estado',
                render: function (data) {
                    return data == 1
                        ? `<span class="badge text-success border border-success bg-light px-3 py-1 rounded-pill shadow-sm">
                   <i class="fa fa-check-circle me-1"></i> Activo
               </span>`
                        : `<span class="badge text-danger border border-danger bg-light px-3 py-1 rounded-pill shadow-sm">
                   <i class="fa fa-times-circle me-1"></i> Inactivo
               </span>`;
                }
            },
            {
                data: null,
                defaultContent:
                    "<button type='button' class='btn btn-secondary btn-xs btn-update' data-toggle='tooltip' title='Actualizar'><i class='fa fa-pencil'></i></button>",
                orderable: false,
                searchable: false,
                width: "26px",
            },
            {
                data: null,
                defaultContent:
                    "<button type='button' class='btn btn-danger btn-xs btn-delete' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash'></i></button>",
                orderable: false,
                searchable: false,
                width: "26px",
            },
        ],
        responsive: true,
        language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' }
    });

    // Editar producto
    $('#tableProductos').on("click", ".btn-update", function () {
        const id = $tableProductos.row($(this).parents("tr")).data().id_producto;
        invocarModalView(id);
    });

    // Eliminar producto
    $('#tableProductos').on("click", ".btn-delete", function () {
        const id = $tableProductos.row($(this).parents("tr")).data().id;
        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);

        confirmAjax(
            `/auth/productos/delete`,
            formData,
            "POST",
            null,
            null,
            function () {
                $tableProductos.ajax.reload(null, false);
            }
        );
    });

    // Registrar producto
    $("#modalRegistrarProductos").on("click", function () {
        invocarModalView();
    });

    function invocarModalView(id) {
        invocarModal(
            `/auth/productos/partialView/${id ? id : 0}`,
            function ($modal) {
                if ($modal.attr("data-reload") === "true")
                    $tableProductos.ajax.reload(null, false);
            }
        );
    }
});
