@section('titulo')
<title>Registro Usuario</title>
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
            <h2>Crear una Cuenta</h2>
            <p class="sub-text">Completa tus datos para crear tu cuenta</p>
        </div>

        <form class="form form-register" action="{{ route('registro.store') }}" method="POST" novalidate>
            @csrf

            <div>
                <label>
                    <i class='bx bx-globe'></i>
                    <select name="pais" required>
                        <option value="" disabled selected>Selecciona tu país</option>
                        <option value="Perú">Perú</option>
                        <option value="Chile">Chile</option>
                        <option value="México">México</option>
                        <option value="Colombia">Colombia</option>
                    </select>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-store-alt'></i>
                    <input type="text" placeholder="Nombre Ecommerce" name="ecommerce" required>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-user'></i>
                    <input type="text" placeholder="Nombres y Apellidos" name="nombres" required>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-envelope'></i>
                    <input type="email" placeholder="Correo Electrónico" name="correo" required>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-user-circle'></i>
                    <input type="text" placeholder="Usuario" name="user" required>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" placeholder="Contraseña" name="password" required>
                </label>
            </div>

            <div>
                <label>
                    <i class='bx bx-phone'></i>
                    <input type="text" placeholder="Teléfono" name="telefono" required>
                </label>
            </div>

            <input type="submit" value="Registrarse">

            <div class="alerta-error" style="display:none;">Todos los campos son obligatorios</div>
            <div class="alerta-exito" style="display:none;">Te registraste correctamente</div>
            <p class="text">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar Sesión</a></p>
            <p class="return-text">
                <a href="{{ route('index') }}" class="return-link">← Regresar a la página principal</a>
            </p>
        </form>

    </div>
</div>
</div>


<script>
document.querySelector('.form-register').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const data = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: "POST",
            body: data,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const result = await response.json();

        if (response.ok && result.Success) {
            document.querySelector('.alerta-exito').style.display = 'block';
            document.querySelector('.alerta-error').style.display = 'none';
            form.reset();
        } else {
            console.error(result.Errors);
            document.querySelector('.alerta-error').style.display = 'block';
            document.querySelector('.alerta-exito').style.display = 'none';
        }
    } catch (error) {
        console.error('Error en la solicitud:', error);
        document.querySelector('.alerta-error').style.display = 'block';
    }
});
</script>
<script>
document.querySelector('.form-register').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;

    const pais = form.pais.value.trim();
    const ecommerce = form.ecommerce.value.trim();
    const nombres = form.nombres.value.trim();
    const correo = form.correo.value.trim();
    const user = form.user.value.trim();
    const password = form.password.value.trim();
    const telefono = form.telefono.value.trim();

    const alertaError = document.querySelector('.alerta-error');
    const alertaExito = document.querySelector('.alerta-exito');

    alertaError.style.display = 'none';
    alertaExito.style.display = 'none';

    // Validar campos vacíos
    if (!pais || !ecommerce || !nombres || !correo || !user || !password || !telefono) {
        alertaError.innerText = "Todos los campos son obligatorios";
        alertaError.style.display = 'block';
        return;
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(correo)) {
        alertaError.innerText = "Ingrese un correo válido";
        alertaError.style.display = 'block';
        return;
    }

    // Validar usuario
    if (user.length < 4) {
        alertaError.innerText = "El usuario debe tener mínimo 4 caracteres";
        alertaError.style.display = 'block';
        return;
    }

    // Validar contraseña
    if (password.length < 6) {
        alertaError.innerText = "La contraseña debe tener mínimo 6 caracteres";
        alertaError.style.display = 'block';
        return;
    }

    // Validar teléfono
    const phoneRegex = /^[0-9]{7,15}$/;
    if (!phoneRegex.test(telefono)) {
        alertaError.innerText = "Ingrese un teléfono válido (solo números)";
        alertaError.style.display = 'block';
        return;
    }

    const data = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: "POST",
            body: data,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const result = await response.json();

        if (response.ok && result.Success) {
            alertaExito.innerText = "Te registraste correctamente";
            alertaExito.style.display = 'block';
            form.reset();
        } else {
            alertaError.innerText = "Error al registrar usuario";
            alertaError.style.display = 'block';
        }

    } catch (error) {
        alertaError.innerText = "Error en la conexión con el servidor";
        alertaError.style.display = 'block';
    }
});
</script>

<script src="{{ asset('app/assets_registro_login/js/script.js') }}"></script>