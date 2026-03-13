<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="MAJML">
    @yield('titulo')
    <link rel="stylesheet" href="{{ asset('auth/plugins/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/bootstrap/css/bootstrap-extend.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/plugins/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/css/layout/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('auth/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets_header_sistema/style.css') }}">
    <link rel="stylesheet" href="{{ asset('app/assets_header_sistema/wsp.css') }}">

    @yield('styles')
</head>

<body>
    <div class="wrapper">
        <div id="loading">
            <i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
        </div>
        <header class="main-header">
            <div class="inside-header">
                <a href="{{ route('auth.inicio') }}" class="logo">
                    <span class="logo-m">
                        <img src="{{ asset('app/img/logo1.png') }}" alt="logo" class="light-logo logo-animado">
                    </span>
                </a>

                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle d-block d-lg-none" data-toggle="push-menu" role="button"
                        style="color: #ffffff;">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav mt-5">
                            <li class="dropdown user user-menu"
                                style="background: #2d338c; border-radius: 50px;height: 50px !important;">
                                <a href="#" class="dropdown-toggle d-flex align-items-center" data-toggle="dropdown"
                                    style="padding: 10px 20px;height: 45px;text-decoration: none;">
                                    <img src="{{ asset('auth/image/icon/usuario.png') }}" alt="User Image"
                                        style="width: 28px; height: 28px; border-radius: 50%; margin-right: 6px;">
                                    <div class="user-info text-left" style="line-height: 1;">
                                        <small style="color: #ffffffff; font-size: 10px;">
                                            Bienvenido(a),
                                        </small>
                                        <p style="margin: 0; font-weight: 600; font-size: 12px; color: #ffffffff;">
                                            {{ Auth::user()->nombres }}
                                        </p>
                                    </div>
                                </a>

                                <ul class="dropdown-menu scale-up">
                                    <li class="user-header">
                                        <div class="user-image-wrapper">
                                            <img src="{{ asset('auth/image/icon/usuario.png') }}" class="float-left"
                                                alt="User Image">
                                        </div>
                                        <p>
                                            {{ Auth::user()->nombres }}
                                            <a href="#" class="btn btn-danger btn-sm btn-rounded">
                                                <i class="fa fa-user"></i>
                                                {{ Auth::guard('web')->user()->profile->name }}
                                            </a>
                                        </p>
                                    </li>
                                    <li class="user-body">
                                        <div class="row no-gutters">
                                            <div class="col-12 text-left">
                                                <a href="javascript:void(0)">
                                                    <b class="text-success">●</b> En Línea
                                                </a>
                                                <a id="ModalCambiarPassword" href="javascript:void(0)">
                                                    <i class="fa fa-key"></i> Cambiar Contraseña
                                                </a>
                                                <a
                                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                    <i class="fa fa-power-off"></i> {{ __('Cerrar Sesión') }}
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    <input type="text" name="validacion"
                                                        value="{{ Auth::guard('web')->user()->email }}">
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>

        <div class="main-nav">
            <nav class="navbar navbar-expand-lg">
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">

                        {{-- Inicio --}}
                        <li class="nav-item {{ Route::currentRouteName() == 'auth.inicio' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('auth.inicio') }}">
                                <span class="active-item-here"></span>
                                <i class="fa fa-home mr-5"></i> Inicio
                            </a>
                        </li>

                        @if(in_array(Auth::user()->profile_id, [
                        \BolsaTrabajo\App::$PERFIL_CLIENTE_ASOCIADO,
                        \BolsaTrabajo\App::$PERFIL_DESARROLLADOR,
                        \BolsaTrabajo\App::$PERFIL_ADMINISTRADOR
                        ]))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fa fa-shopping-cart mr-2"></i> Dropshipping
                            </a>
                            <ul class="dropdown-menu multilevel scale-up-left">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.pedidos.listado') }}">
                                        <i class="fa fa-list mr-2"></i> Listado de pedidos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.pedidos') }}">
                                        <i class="fa fa-plus-circle mr-2"></i> Generar Pedido
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.clientes') }}">
                                        <i class="fa fa-users mr-2"></i> Gestión de Clientes
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(in_array(Auth::user()->profile_id, [
                        \BolsaTrabajo\App::$PERFIL_DESARROLLADOR,
                        \BolsaTrabajo\App::$PERFIL_ADMINISTRADOR,
                        \BolsaTrabajo\App::$PERFIL_JEFE
                        ]))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fa fa-archive mr-2"></i> Almacén
                            </a>
                            <ul class="dropdown-menu multilevel scale-up-left">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.compras.listado') }}">
                                        <i class="fa fa-list-alt mr-2"></i> Listado de Compras
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.compras') }}">
                                        <i class="fa fa-cart-plus mr-2"></i> Generar Compra
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.pedidos.gestion') }}">
                                        <i class="fa fa-shopping-cart mr-2"></i> Aprobación de Pedidos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.movimientos') }}">
                                        <i class="fa fa-exchange mr-2"></i> Ver movimientos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.ajustes.create') }}">
                                        <i class="fa fa-balance-scale mr-2"></i> Ajustes de Cuadre
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.devoluciones.create') }}">
                                        <i class="fa fa-undo mr-2"></i> Devoluciones
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auth.reportes') }}">
                                <i class="fa fa-bar-chart mr-2"></i> Reportes
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fa fa-cog mr-2"></i> Mantenimiento
                            </a>
                            <ul class="dropdown-menu multilevel scale-up-left">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.profiles') }}">
                                        <i class="fa fa-key mr-2"></i> Gestión de Roles
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.usuarios') }}">
                                        <i class="fa fa-users mr-2"></i> Gestión de Usuarios
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.marcas') }}">
                                        <i class="fa fa-tags mr-2"></i> Gestión de Marcas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.proveedores') }}">
                                        <i class="fa fa-truck mr-2"></i> Gestión de Proveedores
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(in_array(Auth::user()->profile_id, [
                        \BolsaTrabajo\App::$PERFIL_MOTORIZADO,
                        \BolsaTrabajo\App::$PERFIL_DESARROLLADOR
                        ]))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auth.pedidos.motorizado') }}">
                                <i class="fa fa-motorcycle mr-2"></i> Despacho
                            </a>
                        </li>
                        @endif

                    </ul>
                </div>
            </nav>
        </div>

        @yield('contenido')


        <!-- Botón flotante de WhatsApp -->
        <a href="https://wa.me/51980812235?text=Hola%20necesito%20ayuda%20con%20mi%20pedido" class="btn-wsp"
            target="_blank">
            <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp" class="icon-wsp">
            <span class="tooltip-wsp">Soporte — ¿Necesitas ayuda?</span>
        </a>
        <div class="conta mt-15" style=" padding-right: 0px !important; padding-left: 0px !important;">
            <footer class="text-center text-white" style="background-color: #313131 !important">
                <!-- Copyright -->
                <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                    © 2024 Todos los derechos reservados para
                    <a class="text-white" href="https://www.grupocodware.com/" target="_blank">Grupo Codware</a>
                </div>
                <!-- Copyright -->
            </footer>
        </div>


    </div>
    <script type="text/javascript" src="{{ asset('auth/plugins/popper.min.js') }}"></script>
    {{-- <script type="" src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> --}}
    <script type="text/javascript" src="{{ asset('auth/plugins/jquery-3.3.1/jquery-3.3.1.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('auth/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/toggle-sidebar/index.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/toastr/js/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/datatable/dataTables.config.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/plugins/moment/es.js') }}"></script>
    <script type="text/javascript" src="{{ asset('auth/js/_Layout.js') }}"></script>
    {{-- Para bloquear el F12 y otras funciones --}}
    {{-- <script>
        document.addEventListener("keydown", function(e) {
            // Deshabilitar F12
            if (e.keyCode === 123) {
                e.preventDefault();
                return false;
            }

            // Deshabilitar Ctrl+Shift+I (DevTools)
            if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
                e.preventDefault();
                return false;
            }

            // Deshabilitar Ctrl+U (Ver fuente)
            if (e.ctrlKey && e.keyCode === 85) {
                e.preventDefault();
                return false;
            }
        });

        document.addEventListener("contextmenu", function(e) {
            e.preventDefault();
        });
    </script> --}}


    @yield('scripts')

</body>

</html>