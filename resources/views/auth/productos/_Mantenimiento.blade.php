<div id="modalMantenimientoProductos" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="registroProductos" method="POST" action="{{ route('auth.productos.store') }}"
            enctype="multipart/form-data" data-ajax="true" data-close-modal="true" data-ajax-loading="#loading"
            data-ajax-success="OnSuccessRegistroProductos" data-ajax-failure="OnFailureRegistroProductos">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $Producto != null ? 'Modificar' : 'Registrar' }} Producto</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body row">
                    @csrf
                    <input type="hidden" name="id_producto" id="id_producto"
                        value="{{ $Producto != null ? $Producto->id_producto : 0 }}">
                    <!-- Código del producto -->
                    <div class="form-group col-md-6">
                        <label for="codigo_producto"><b>Código Producto</b></label>
                        <input type="text" class="form-control" name="codigo_producto" id="codigo_producto"
                            value="{{ $Producto ? $Producto->codigo_producto : '' }}">
                        <span data-valmsg-for="codigo_producto" class="text-danger"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="id_producto_marca" class="mb-0"><b>Marca</b></label>
                        </div>

                        <select class="form-control" name="id_producto_marca" id="id_producto_marca" required>
                            <option value="">Seleccione una marca</option>
                            @foreach($Marcas as $marca)
                            <option value="{{ $marca->id_producto_marca }}"
                                {{ $Producto && $Producto->id_producto_marca == $marca->id_producto_marca ? 'selected' : '' }}>
                                {{ $marca->descripcion }}
                            </option>
                            @endforeach
                        </select>
                        <span data-valmsg-for="id_producto_marca" class="text-danger"></span>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="descripcion">Descripción</label>
                        <input type="text" class="form-input" name="descripcion" id="descripcion"
                            value="{{ $Producto ? $Producto->descripcion : '' }}" required>
                        <span data-valmsg-for="descripcion" class="text-danger"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="precio_compra">Precio de Compra</label>
                        <input type="number" class="form-input" name="precio_compra" id="precio_compra"
                            value="{{ $Producto ? $Producto->precio_compra : '' }}" step="0.01" required>
                        <span data-valmsg-for="precio_compra" class="text-danger"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="precio_venta">Precio de Venta</label>
                        <input type="number" class="form-input" name="precio_venta" id="precio_venta"
                            value="{{ $Producto ? $Producto->precio_venta : '' }}" step="0.01" required>
                        <span data-valmsg-for="precio_venta" class="text-danger"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="stock">Stock</label>
                        <input type="number" class="form-input" id="stock"
                            value="{{ $Producto ? $Producto->stock : '0' }}" step="1" disabled>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="estado">Estado</label>
                        <select class="form-input" name="estado" id="estado" required>
                            <option value="1" {{ $Producto && $Producto->estado == '1' ? 'selected' : '' }}>Activo
                            </option>
                            <option value="2" {{ $Producto && $Producto->estado == '2' ? 'selected' : '' }}>Inactivo
                            </option>
                        </select>
                    </div>
                    <!-- Imagen del producto -->
                    <div class="form-group col-md-6">
                        <label for="imagen">Imagen del producto</label>
                        <input type="file" class="form-input" name="imagen" id="imagen" accept="image/*">
                        <span data-valmsg-for="imagen" class="text-danger"></span>

                        <div class="mt-2">
                            <img id="previewImagen"
                                src="{{ $Producto && $Producto->imagen ? asset($Producto->imagen) : '#' }}"
                                style="{{ $Producto && $Producto->imagen ? '' : 'display:none;' }} max-width: 150px; border-radius:5px;"
                                alt="Preview">
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-bold btn-pure btn-primary">
                        {{ $Producto != null ? 'Modificar' : 'Registrar' }} Producto
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Previsualización de imagen al seleccionar
document.getElementById('imagen').addEventListener('change', function(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewImagen');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
});
</script>
<script type="text/javascript" src="{{ asset('auth/js/productos/_Mantenimiento.js') }}"></script>
<script type="text/javascript" src="{{ asset('auth/js/productos/_MantenimientoMarca.js') }}"></script>