<style>
.input-contador {
    position: relative;
}

.contador-inside {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    color: #888;
    pointer-events: none;
}
</style>
<div id="modalMantenimientoClientes" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form enctype="multipart/form-data" action="{{ route('auth.clientes.store') }}" id="registroCliente"
            method="POST" data-ajax="true" data-close-modal="true" data-ajax-loading="#loading"
            data-ajax-success="OnSuccessRegistroCliente" data-ajax-failure="OnFailureRegistroCliente">
            @csrf

            <input type="hidden" name="id_cliente" value="{{ $Entity ? $Entity->id_cliente : 0 }}">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $Entity ? 'Modificar Cliente' : 'Registrar Cliente' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Tipo de documento</label>
                        <select id="tipo_documento" class="form-input">
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                            <option value="CE">CE</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>N° Documento</label>

                        <div class="input-contador">
                            <input type="text" name="documento" id="documento" class="form-input"
                                value="{{ $Entity ? $Entity->documento : '' }}" required>

                            <span id="contadorDocumento" class="contador-inside">0</span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label>Nombres completos</label>
                        <input type="text" name="nombres" class="form-input"
                            value="{{ $Entity ? $Entity->nombres : '' }}" required>
                        <span data-valmsg-for="nombres"></span>
                    </div>

                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-input"
                            value="{{ $Entity ? $Entity->direccion : '' }}">
                    </div>

                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-input"
                            value="{{ $Entity ? $Entity->telefono : '' }}" maxlength="9" placeholder="Ej: 987654321">
                    </div>

                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input type="email" name="email" id="email" class="form-input"
                            value="{{ $Entity ? $Entity->email : '' }}" placeholder="ejemplo@correo.com">
                    </div>


                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-input" required>
                            <option value="1" {{ $Entity && $Entity->estado == 1 ? 'selected' : '' }}>
                                Activo
                            </option>
                            <option value="0" {{ $Entity && $Entity->estado == 0 ? 'selected' : '' }}>
                                Inactivo
                            </option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-bold btn-pure btn-primary">
                        {{ $Entity ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script src="{{ asset('auth/js/clientes/_Mantenimiento.js') }}"></script>

<script>
$(document).ready(function() {
    $('#telefono').on('blur', function() {
        if (this.value.length > 0 && this.value.length < 9) {
            this.style.borderColor = 'red';
        } else {
            this.style.borderColor = '';
        }
    });

    $('#email').on('blur', function() {
        const email = this.value.trim();
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email !== '' && !regex.test(email)) {
            this.style.borderColor = 'red';
        } else {
            this.style.borderColor = '';
        }
    });

    function detectarTipoPorDocumento() {
        const $doc = $('#documento');
        const valor = $doc.val();
        const length = valor.length;

        if (length === 8) {
            $('#tipo_documento').val('DNI');
            $doc.attr('maxlength', 8);
        } else if (length === 11) {
            $('#tipo_documento').val('RUC');
            $doc.attr('maxlength', 11);
        } else if (length > 0) {
            $('#tipo_documento').val('CE');
            $doc.attr('maxlength', 12);
        }

        $('#contadorDocumento').text(length + '/' + $doc.attr('maxlength'));
    }

    function configurarDocumento() {
        const tipo = $('#tipo_documento').val();
        const $doc = $('#documento');

        if (tipo === 'DNI') {
            $doc.attr('maxlength', 8);
        } else if (tipo === 'RUC') {
            $doc.attr('maxlength', 11);
        } else {
            $doc.attr('maxlength', 12);
        }

        $('#contadorDocumento').text($doc.val().length + '/' + $doc.attr('maxlength'));
    }

    $('#tipo_documento').on('change', function() {
        $('#documento').val('');
        configurarDocumento();
    });

    $('#documento').on('input', function() {
        $('#contadorDocumento').text(this.value.length + '/' + this.maxLength);
    });

    if ($('#documento').val() !== '') {
        detectarTipoPorDocumento(); // edición
    } else {
        configurarDocumento(); // nuevo
    }

});
</script>