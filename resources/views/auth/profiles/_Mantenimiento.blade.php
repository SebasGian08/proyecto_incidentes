<div id="modalMantenimientoProfiles" class="modal modal-fill fade" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form enctype="multipart/form-data" action="{{ route('auth.profiles.store') }}" id="registroProfile" method="POST"
            data-ajax="true" data-close-modal="true" data-ajax-loading="#loading"
            data-ajax-success="OnSuccessRegistroProfile" data-ajax-failure="OnFailureRegistroProfile">
            @csrf
            <input type="hidden" name="id" value="{{ $Entity ? $Entity->id : 0 }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $Entity ? 'Modificar' : 'Registrar' }} Perfil
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ $Entity ? $Entity->name : '' }}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">
                        {{ $Entity ? 'Modificar' : 'Registrar' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('auth/js/profiles/_Mantenimiento.js') }}"></script>
