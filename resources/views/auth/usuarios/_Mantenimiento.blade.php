<div id="modalMantenimientoUsuarios" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form enctype="multipart/form-data" action="{{ route('auth.usuarios.store') }}" id="registroUsuarios" method="POST"
            data-ajax="true" data-close-modal="true" data-ajax-loading="#loading"
            data-ajax-success="OnSuccessRegistroUsuarios" data-ajax-failure="OnFailureRegistroUsuarios">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $Entity != null ? 'Modificar' : ' Registrar' }} Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{ $Entity != null ? $Entity->id : 0 }}">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="nombres">Nombres y Apellidos</label>
                                <input type="text" class="form-input" name="nombres"
                                    value="{{ $Entity ? $Entity->nombres : '' }}" id="nombres" autocomplete="off"
                                    required>
                                <span data-valmsg-for="nombres" class="text-danger"></span>
                            </div>

                            <div class="col-md-12">
                                <label for="email">Usuario de Ingreso</label>
                                <input type="text" class="form-input" name="email"
                                    value="{{ $Entity ? $Entity->email : '' }}" id="email" autocomplete="off"
                                    required>
                                <span data-valmsg-for="email" class="text-danger"></span>
                            </div>

                            <div class="col-md-12">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-input" name="password" id="password"
                                    autocomplete="off" {{ $Entity == null ? 'required' : '' }}>
                                <span data-valmsg-for="password" class="text-danger"></span>
                            </div>
                            <div class="col-md-12">
                                <label for="profile_id">Perfil</label>
                                <select name="profile_id" class="form-input" id="profile_id" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($Profiles as $q)
                                        <option value="{{ $q->id }}"
                                            {{ $Entity != null && $Entity->profile_id == $q->id ? 'selected' : '' }}>
                                            {{ $q->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span data-valmsg-for="profile_id"></span>
                            </div>
                            <div class="col-md-12">
                                <label for="estado">
                                    Estado
                                </label>
                                <div class="input-group">
                                    <select class="form-control" name="estado" id="estado" required>
                                        <option value="1"
                                            {{ $Entity && $Entity->estado == '1' ? 'selected' : '' }}>Activo
                                        </option>
                                        <option value="2"
                                            {{ $Entity && $Entity->estado == '2' ? 'selected' : '' }}>Inactivo
                                        </option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-bold btn-pure btn-primary">{{ $Entity != null ? 'Modificar' : ' Registrar' }}
                        Usuario</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="{{ asset('auth/js/usuarios/_Mantenimiento.js') }}"></script>
