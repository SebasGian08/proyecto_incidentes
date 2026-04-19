@extends('app.index')

@section('titulo')
<title>Iniciar Sesión</title>
@endsection

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="{{ asset('app/assets_registro_login/style.css') }}">

@section('content')
<div class="login-wrapper">
    <div class="form-information">
        <div class="form-information-childs">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('app/img/logo2.png') }}" alt="Logo" class="logo" />
            </a>
            <h2>Iniciar Sesión</h2>
            <p class="sub-text">Accede con tu cuenta registrada</p>
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <form class="form form-login" method="POST" action="{{ route('auth.login.post') }}">
                @csrf
                <div>
                    <label>
                        <i class='bx bx-user-circle'></i>
                        <input type="text" name="email" id="email" placeholder="Email" value="{{ old('email') }}"
                            required class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    </label>
                    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div>
                    <label>
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="password" id="password" placeholder="Contraseña" required
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                        <span id="togglePassword" class="toggle-password">👁️</span>
                    </label>
                    @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>

                <input type="submit" value="Iniciar Sesión">
                <br><br>
            </form>
        </div>
    </div>
</div>

<style>
.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    user-select: none;
}

label {
    position: relative;
}
</style>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.textContent = type === 'password' ? '👁️' : '🙈';
});
</script>
@endsection