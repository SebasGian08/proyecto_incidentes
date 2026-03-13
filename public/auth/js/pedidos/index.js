$(document).ready(function () {

    /* ===============================
       VARIABLES
    ================================*/
    const productos = $(".producto-card");
    let productosFiltrados = productos;
    let paginaActual = 1;
    const productosPorPagina = 8;

    /* ===============================
       SELECT2
    ================================*/
    function initSelect2() {
        $('.producto-select').select2({
            placeholder: "Buscar producto...",
            allowClear: true,
            width: '100%'
        });
    }
    initSelect2();

    /* ===============================
       TOTALES
    ================================*/
    function actualizarTotales() {
        let subtotal = 0;

        $("#ticket-table tbody tr").each(function () {
            const total = parseFloat($(this).find(".total-item").text()) || 0;
            subtotal += total;
        });

        const igv = subtotal * 0.18;
        const total = subtotal + igv;

        $("#subtotal-ticket").text(subtotal.toFixed(2));
        $("#igv-ticket").text(igv.toFixed(2));
        $("#total-ticket").text(total.toFixed(2));
    }

    /* ===============================
       AGREGAR PRODUCTO
    ================================*/
    $(document).on("click", ".btn-add", function () {

        const card = $(this).closest(".producto-card");

        const id = card.data("id");
        const nombre = card.data("nombre");
        const precio = parseFloat(card.data("precio")) || 0;
        const cantidad = parseInt(card.find(".cantidad").val()) || 0;
        const disponible = parseInt(
            card.find(".stock-disponible").text().replace(/\D/g, "")
        ) || 0;


        // 📦 stock disponible desde el texto
        const stockDisponible = parseInt(
            card.find(".stock-disponible").text().replace(/\D/g, "")
        ) || 0;

        if (cantidad <= 0) {
            return Swal.fire({
                icon: 'warning',
                title: 'Cantidad inválida',
                text: 'Ingrese una cantidad mayor a cero'
            });
        }

        if (disponible <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Sin stock',
                text: 'No hay stock disponible para este producto'
            });
            return;
        }

        if (cantidad > disponible) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock insuficiente',
                text: `Solo hay ${disponible} unidades disponibles`
            });
            return;
        }



        const filaExistente = $("#ticket-table tbody tr[data-id='" + id + "']");

        // 🧮 cantidad ya agregada
        let cantidadActual = 0;
        if (filaExistente.length > 0) {
            cantidadActual = parseInt(filaExistente.find(".cant-item").text()) || 0;
        }

        const nuevaCantidad = cantidadActual + cantidad;

        // 🚫 no permitir pasar el stock
        if (nuevaCantidad > stockDisponible) {
            return Swal.fire({
                icon: 'warning',
                title: 'Stock insuficiente',
                html: `
                <b>Disponible:</b> ${stockDisponible} <br>
                <b>Intentas agregar:</b> ${nuevaCantidad}
            `
            });
        }

        // ✅ actualizar fila existente
        if (filaExistente.length > 0) {

            filaExistente.find(".cant-item").text(nuevaCantidad);
            filaExistente.find(".total-item").text((nuevaCantidad * precio).toFixed(2));
            filaExistente.find('input[name$="[cantidad]"]').val(nuevaCantidad);

        } else {

            const index = $("#ticket-table tbody tr").length;

            const fila = `
            <tr data-id="${id}">
                <td>
                    ${nombre}
                    <input type="hidden" name="productos[${index}][id_producto]" value="${id}">
                    <input type="hidden" name="productos[${index}][cantidad]" value="${cantidad}">
                    <input type="hidden" name="productos[${index}][precio]" value="${precio}">
                </td>
                <td class="cant-item">${cantidad}</td>
                <td>${precio.toFixed(2)}</td>
                <td class="total-item">${(cantidad * precio).toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-remove">X</button>
                </td>
            </tr>
        `;

            $("#ticket-table tbody").append(fila);
        }

        // ⚠️ aviso si queda poco stock
        if (stockDisponible - nuevaCantidad <= 5) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock limitado',
                text: 'Quedan pocas unidades disponibles'
            });
        }

        actualizarTotales();
    });


    /* ===============================
       PAGINACIÓN
    ================================*/
    function mostrarPagina(page) {

        productosFiltrados.hide();

        const start = (page - 1) * productosPorPagina;
        const end = start + productosPorPagina;

        productosFiltrados.slice(start, end).show();

        const totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
        $("#pageInfo").text(`Página ${page} de ${totalPaginas}`);
    }

    mostrarPagina(paginaActual);

    /* ===============================
       BOTONES PAGINACIÓN
    ================================*/
    $("#prevPage").on("click", function () {
        if (paginaActual > 1) {
            paginaActual--;
            mostrarPagina(paginaActual);
        }
    });

    $("#nextPage").on("click", function () {
        const totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
        if (paginaActual < totalPaginas) {
            paginaActual++;
            mostrarPagina(paginaActual);
        }
    });



    /* ===============================
       BUSCADOR
    ================================*/
    function normalizarTexto(texto) {
        return texto
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/[^a-z0-9\s]/g, "");
    }

    $("#buscarProducto").on("keyup", function () {

        const busqueda = normalizarTexto($(this).val().trim());

        $(".producto-card").hide();

        // si no hay texto, mostrar según paginación normal
        if (busqueda === '') {
            productosFiltrados = productos;
            paginaActual = 1;
            mostrarPagina(paginaActual);
            return;
        }

        const palabras = busqueda.split(/\s+/);

        productosFiltrados = $(".producto-card").filter(function () {
            const nombre = normalizarTexto($(this).data("nombre") || '');
            return palabras.every(p => nombre.includes(p));
        });

        productosFiltrados.show();

        paginaActual = 1;
        mostrarPagina(paginaActual);
    });





    /* ===============================
       ELIMINAR PRODUCTO
    ================================*/
    $(document).on("click", ".btn-remove", function () {
        $(this).closest("tr").remove();
        actualizarTotales();
    });

});
