@section('titulo')
<title>Registro de Incidente</title>
@endsection

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="{{ asset('app/assets_registro_login/style.css') }}">

@section('content')
@endsection

<div class="container-form">
    <div class="form-information">
        <div class="form-information-childs">
            <a class="navbar-brand" href="{{ route('index') }}">
                <img src="{{ asset('app/img/logo2.png') }}" alt="Logo" class="logo" />
            </a>
            <h2>Registrar Incidente</h2>
            <p class="sub-text">Completa los datos del incidente</p>
        </div>

        <form class="form form-incidente" action="{{ route('incidentes.store') }}" method="POST" novalidate>
            @csrf

            <div>
                <label>
                    <i class='bx bx-text'></i>
                    <input type="text" placeholder="Título del incidente" name="titulo" required>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-message-square-detail'></i>
                    <textarea name="descripcion" placeholder="Descripción detallada"></textarea>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-bolt'></i>
                    <select name="severidad" required>
                        <option value="" disabled selected>Seleccione la severidad</option>
                        @foreach(\DB::table('maestro_severidad')->get() as $sev)
                            <option value="{{ $sev->nombre }}">{{ $sev->nombre }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-cog'></i>
                    <select name="estado" required>
                        <option value="" disabled selected>Seleccione el estado</option>
                        @foreach(\DB::table('maestro_estado_ticket')->get() as $est)
                            <option value="{{ $est->nombre }}">{{ $est->nombre }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-desktop'></i>
                    <select name="activo_id">
                        <option value="" selected>Activo relacionado (opcional)</option>
                        @foreach(\DB::table('activos_ti')->get() as $activo)
                            <option value="{{ $activo->id }}">{{ $activo->nombre }} - {{ $activo->codigo_patrimonial }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-user-check'></i>
                    <select name="usuario_reporta_id">
                        <option value="" selected>Usuario que reporta (opcional)</option>
                        @foreach(\DB::table('users')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->nombres }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-user-circle'></i>
                    <select name="tecnico_asignado_id">
                        <option value="" selected>Técnico asignado (opcional)</option>
                        @foreach(\DB::table('users')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->nombres }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <input type="submit" value="Registrar Incidente">

            <div class="alerta-error" style="display:none;"></div>
            <div class="alerta-exito" style="display:none;"></div>
        </form>

    </div>
</div>

<script>
document.querySelector('.form-incidente').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const data = new FormData(form);
    const alertaError = document.querySelector('.alerta-error');
    const alertaExito = document.querySelector('.alerta-exito');

    alertaError.style.display = 'none';
    alertaExito.style.display = 'none';

    try {
        const response = await fetch(form.action, {
            method: "POST",
            body: data,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });

        const result = await response.json();

        if(response.ok && result.Success){
            alertaExito.innerText = result.Message;
            alertaExito.style.display = 'block';
            form.reset();
        } else {
            alertaError.innerText = "Error al registrar el incidente";
            if(result.Errors) {
                alertaError.innerText = Object.values(result.Errors).flat().join(", ");
            }
            alertaError.style.display = 'block';
        }
    } catch(error) {
        alertaError.innerText = "Error en la conexión con el servidor";
        alertaError.style.display = 'block';
    }
});
</script>

