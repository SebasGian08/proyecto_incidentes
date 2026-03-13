var $tableProfiles;

$(function () {

    const $table = $("#tableProfiles");

    $tableProfiles = $table.DataTable({
        ajax: {
            url: "/auth/profiles/list_all",
        },
        columns: [
            {
                title: "N°",
                data: null,
                render: (d, t, r, m) => m.row + 1
            },
            {
                title: "Nombre",
                data: "name"
            },
            {
                title: "Acciones",
                data: null,
                orderable: false,
                searchable: false,
                className: "text-center",
                render: () => `
                    <button class="btn btn-secondary btn-xs btn-update">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button class="btn btn-danger btn-xs btn-delete">
                        <i class="fa fa-trash"></i>
                    </button>
                `
            }
        ]
    });

    $("#btnRegistrarProfile").on("click", () => invocarModalView());

    $table.on("click", ".btn-update", function () {
        const id = $tableProfiles.row($(this).parents("tr")).data().id;
        invocarModalView(id);
    });

    $table.on("click", ".btn-delete", function () {
        const id = $tableProfiles.row($(this).parents("tr")).data().id;

        const formData = new FormData();
        formData.append("_token", $("input[name=_token]").val());
        formData.append("id", id);

        confirmAjax(
            "/auth/profiles/delete",
            formData,
            "POST",
            null,
            null,
            () => $tableProfiles.ajax.reload(null, false)
        );
    });

    function invocarModalView(id = 0) {
        invocarModal(`/auth/profiles/partialView/${id}`, function ($modal) {
            if ($modal.attr("data-reload") === "true") {
                $tableProfiles.ajax.reload(null, false);
            }
        });
    }
});
