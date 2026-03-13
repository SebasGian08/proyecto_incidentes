<div id="modalMantenimientoMarcas" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form enctype="multipart/form-data" action="{{ route('auth.marcas.store') }}" id="registroMarca" method="POST"
            data-ajax="true" data-close-modal="true" data-ajax-loading="#loading"
            data-ajax-success="OnSuccessRegistroMarca" data-ajax-failure="OnFailureRegistroMarca">
            @csrf

            <input type="hidden" name="id_producto_marca"
                   value="{{ $Entity ? $Entity->id_producto_marca : 0 }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $Entity ? 'Modificar' : 'Registrar' }} Marca
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" name="descripcion" class="form-input"
                               value="{{ $Entity ? $Entity->descripcion : '' }}" required>
                        <span data-valmsg-for="descripcion"></span>
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

<script src="{{ asset('auth/js/marcas/_Mantenimiento.js') }}"></script>
