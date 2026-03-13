<div id="modalMantenimientoProveedores" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form enctype="multipart/form-data" action="{{ route('auth.proveedores.store') }}" id="registroProveedor" method="POST"
            data-ajax="true" data-close-modal="true" data-ajax-loading="#loading"
            data-ajax-success="OnSuccessRegistroProveedor" data-ajax-failure="OnFailureRegistroProveedor">
            @csrf
            <input type="hidden" name="id_proveedor"
                   value="{{ $Entity ? $Entity->id_proveedor : 0 }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $Entity ? 'Modificar' : 'Registrar' }} Proveedor
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>RUC</label>
                        <input type="text" name="ruc" class="form-input"
                               value="{{ $Entity->ruc ?? '' }}" required>
                        <span data-valmsg-for="ruc"></span>
                    </div>

                    <div class="form-group">
                        <label>Razón Social</label>
                        <input type="text" name="razon_social" class="form-input"
                               value="{{ $Entity->razon_social ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-input"
                               value="{{ $Entity->direccion ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-input"
                               value="{{ $Entity->telefono ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-input"
                               value="{{ $Entity->email ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-control" required>
                            <option value="1" {{ $Entity && $Entity->estado == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="2" {{ $Entity && $Entity->estado == 2 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        {{ $Entity ? 'Modificar' : 'Registrar' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('auth/js/proveedores/_Mantenimiento.js') }}"></cript>
