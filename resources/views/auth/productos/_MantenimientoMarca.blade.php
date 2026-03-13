<div id="modalAgregarMarca" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm"> <form id="registroMarca" method="POST" action="{{ route('auth.marcas.store') }}" 
              data-ajax="true" data-ajax-success="OnSuccessRegistroMarca" data-ajax-failure="OnFailureRegistroMarca">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nueva Marca</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="marca_descripcion"><b>Nombre de la Marca</b></label>
                        <input type="text" class="form-control" name="descripcion" id="marca_descripcion" required>
                    </div>
                    <input type="hidden" name="estado" value="1">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">Guardar Marca</button>
                </div>
            </div>
        </form>
    </div>
</div>