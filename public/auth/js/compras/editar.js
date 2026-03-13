$(document).ready(function () {

    // SELECT2
    $('.select2').select2();

    // CARGAR DETALLE AL EDITAR
    if (productosEditar.length > 0) {

        productosEditar.forEach(p => {

            let total = parseFloat(p.cantidad) * parseFloat(p.costo_unitario);

            let fila = `
            <tr>

                <td>
                    ${p.descripcion}
                    <input type="hidden" name="id_producto[]" value="${p.id_producto}">
                </td>

                <td>
                    <input type="number"
                        name="cantidad[]"
                        class="form-control cantidad"
                        min="1"
                        value="${p.cantidad}">
                </td>

                <td>
                    <input type="number"
                        name="costo[]"
                        class="form-control costo"
                        step="0.01"
                        min="0"
                        value="${p.costo_unitario}">
                </td>

                <td class="total">${total.toFixed(2)}</td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-eliminar">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>

            </tr>
            `;

            $("#detalle-compra tbody").append(fila);

        });

        calcularTotales();
    }

});


/* ============================
   ELIMINAR PRODUCTO
============================ */

$(document).on("click", ".btn-eliminar", function () {

    $(this).closest("tr").remove();

    calcularTotales();

});


/* ============================
   CAMBIO DE CANTIDAD O COSTO
============================ */

$(document).on("keyup change", ".cantidad, .costo", function () {

    let fila = $(this).closest("tr");

    let cantidad = parseFloat(fila.find(".cantidad").val()) || 0;
    let costo = parseFloat(fila.find(".costo").val()) || 0;

    let total = cantidad * costo;

    fila.find(".total").text(total.toFixed(2));

    calcularTotales();

});


/* ============================
   CALCULAR TOTALES
============================ */

function calcularTotales() {

    let subtotal = 0;

    $("#detalle-compra tbody tr").each(function () {

        let total = parseFloat($(this).find(".total").text()) || 0;

        subtotal += total;

    });

    let igv = subtotal * 0.18;
    let total = subtotal + igv;

    $("#subtotal").text(subtotal.toFixed(2));
    $("#igv").text(igv.toFixed(2));
    $("#total").text(total.toFixed(2));

    $("#input-subtotal").val(subtotal.toFixed(2));
    $("#input-igv").val(igv.toFixed(2));
    $("#input-total").val(total.toFixed(2));

}


/* ============================
   VALIDAR DETALLE
============================ */

$("#form-compra").submit(function (e) {

    if ($("#detalle-compra tbody tr").length === 0) {

        alert("Debe agregar al menos un producto");

        e.preventDefault();

    }

});