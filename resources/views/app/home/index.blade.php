<!-- @extends('app.index')
 -->
@section('styles')
<link rel="stylesheet" href="{{ asset('app/assets_web_principal/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_web_principal/css/lineicons.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_web_principal/css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_web_principal/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('app/assets_web_principal/carpetalogin/css/estilos.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@endsection



@section('scripts')
<script type="text/javascript" src="{{ asset('app/assets_web_principal/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('app/assets_web_principal/js/wow.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('app/assets_web_principal/js/main.js') }}"></script>

<script type="text/javascript" src="{{ asset('app/assets_web_principal/carpetalogin/js/categorias.js') }}"></script>
<script type="text/javascript" src="{{ asset('app/assets_web_principal/carpetalogin/js/preguntasFrecuentes.js') }}">
</script>
<script>
var csrfToken = '{{ csrf_token() }}';
</script>

@endsection



@section('content')
<!-- ======== preloader start ======== -->
<div class="preloader">
    <div class="loader">
        <div class="spinner">
            <div class="spinner-container">
                <div class="spinner-rotator">
                    <div class="spinner-left">
                        <div class="spinner-circle"></div>
                    </div>
                    <div class="spinner-right">
                        <div class="spinner-circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- preloader end -->

<!-- ======== header start ======== -->
<header class="header">
    <div class="navbar-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="{{ route('index') }}">
                            <img src="{{ asset('app/img/logo1.png') }}" alt="Logo" />
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                            <ul id="nav" class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="page-scroll active" href="#home">Inicio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="#features">¿Qué ofrecemos?</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="#about">Quiénes somos</a>
                                </li>

                                <!-- Botón de WhatsApp -->
                                <li class="nav-item">
                                    <a href="https://wa.me/51980812235" target="_blank" class="ms-2"
                                        style="background-color: #25d366; color: white; padding: 8px 15px; border-radius: 30px; font-weight: 500; text-decoration: none;">
                                        <i class="fab fa-whatsapp"></i> Contacto directo
                                    </a>
                                </li>
                            </ul>
                        </div>


                        <!-- navbar collapse -->
                    </nav>
                    <!-- navbar -->
                </div>
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </div>
    <!-- navbar area -->
</header>
<!-- ======== header end ======== -->

<!-- ======== hero-section start ======== -->
<section id="home" class="hero-section">
    <div class="container">
        <div class="row align-items-center position-relative">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="wow fadeInUp" data-wow-delay=".4s">
                        Más ventas, <br>menos complicaciones
                    </h1>
                    <p class="wow fadeInUp" data-wow-delay=".6s">
                        Vende más, crece sin límites y accede a las mejores tarifas del mercado
                    </p>
                    <a href="{{ route('app.registro.index') }}" class="main-btn border-btn btn-hover wow fadeInUp"
                        data-wow-delay=".6s" style="width: 100%;">Registrate ya</a><br><br>
                    <a href="{{ route('auth.login') }}" class="main-btn border-btn btn-hover wow fadeInUp"
                        data-wow-delay=".6s" style="width: 100%;background-color: #fdd446;color: #5864ff;">Ya soy
                        cliente</a>
                    <a href="#features" class="scroll-bottom">
                        <i class="lni lni-arrow-down"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-img wow fadeInUp" data-wow-delay=".5s">
                    <img src="{{ asset('app/assets_web_principal/img/hero/hero-img.png') }}" alt="" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======== hero-section end ======== -->

<!-- ======== feature-section start ======== -->
<section id="features" class="feature-section pt-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-8 col-sm-10">
                <div class="single-feature">
                    <div class="icon">
                        <img src="{{ asset('app/assets_web_principal/img/hero/hero-2.png') }}" alt="" />
                    </div>
                    <div class="content">
                        <h3>Regístrate y accede a cientos de productos</h3>
                        <p>
                            Crea tu cuenta GRATIS en 3 minutos y explora nuestro catálogo con miles de productos listos
                            para vender sin comprar stock
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-8 col-sm-10">
                <div class="single-feature">
                    <div class="icon">
                        <img src="{{ asset('app/assets_web_principal/img/hero/hero-3.png') }}" alt="" />
                    </div>
                    <div class="content">
                        <h3>Gestiona tus pedidos facilmente</h3>
                        <p>
                            Carga tus pedidos en segundos y administra cada envío de forma ágil. Supervisa su progreso
                            con un control rápido y eficiente.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-8 col-sm-10">
                <div class="single-feature">
                    <div class="icon">
                        <img src="{{ asset('app/assets_web_principal/img/hero/hero-4.png') }}" alt="" />
                    </div>
                    <div class="content">
                        <h3>Vende más y crece sin preocupaciones</h3>
                        <p>
                            Nos encargamos de la logística y el recaudo por ti. Olvídate de los procesos complicados y
                            haz crecer tu negocio con una plataforma diseñada para optimizar cada paso.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======== feature-section end ======== -->

<!-- ======== about-section start ======== -->
<section id="about" class="about-section pt-150">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6">
                <div class="about-img">
                    <img src="{{ asset('app/assets_web_principal/img/hero/hero-6.png') }}" alt="" class="w-100" />
                    <img src="{{ asset('app/assets_web_principal/img/about/about-left-shape.svg') }}" alt=""
                        class="shape shape-1" />
                    <img src="{{ asset('app/assets_web_principal/img/about/left-dots.svg') }}" alt=""
                        class="shape shape-2" />
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="about-content">
                    <div class="section-title mb-30">
                        <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">
                            Quiénes Somos – Essentium Group
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s" style="text-align: justify;">
                            Essentium Group es una empresa dedicada al abastecimiento, distribución y logística de
                            perfumes originales y auténticos, comprometida con ofrecer soluciones integrales que
                            potencien el desarrollo de empresas y emprendedores.
                            <br><br>
                            Nuestro modelo de gestión combina eficiencia, calidad y cooperación, permitiéndonos operar
                            como aliados estratégicos en cada etapa del proceso: desde la importación y almacenamiento
                            hasta la distribución o el servicio dropshipping, garantizando una experiencia confiable y
                            rentable para nuestros socios comerciales.
                            <br><br>
                            Essentium Group: Logística, distribución y confianza para hacer crecer tu negocio.
                        </p>
                    </div>
                    <!-- <a href="javascript:void(0)" class="main-btn btn-hover border-btn wow fadeInUp"
                        data-wow-delay=".6s">Discover More</a> -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======== about-section end ======== -->

<!-- ======== about2-section start ======== -->
<section id="about" class="about-section pt-150">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6">
                <div class="about-content">
                    <div class="section-title mb-30">
                        <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">
                            Essentium Group, tu ecommerce a un clik
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s" style="text-align: justify;">
                            En Essentium Group creemos en el crecimiento conjunto. Nuestro compromiso se basa en la
                            excelencia operativa, la transparencia y la innovación constante, valores que nos consolidan
                            como un socio sólido en la cadena de suministro del sector perfumero.
                        </p>
                    </div>
                    <ul>
                        <li>Acceso rápido</li>
                        <li>Fácil de gestionar</li>
                        <li>24/7 Soporte</li>
                    </ul>
                    <a href="https://wa.me/51980812235" target="_blank"
                        class="main-btn btn-hover border-btn wow fadeInUp" data-wow-delay=".6s">Más información</a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 order-first order-lg-last">
                <div class="about-img-2">
                    <img src="{{ asset('app/assets_web_principal/img/hero/hero-5.png') }}" alt="" class="w-100" />
                    <img src="{{ asset('app/assets_web_principal/img/about/about-right-shape.svg') }}" alt=""
                        class="shape shape-1" />
                    <img src="{{ asset('app/assets_web_principal/img/about/right-dots.svg') }}" alt=""
                        class="shape shape-2" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======== about2-section end ======== -->

<!-- ======== feature-section start ======== -->
<section id="why" class="feature-extended-section pt-100">
    <div class="feature-extended-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-xl-6 col-lg-8 col-md-9">
                    <div class="section-title text-center mb-60">
                        <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">
                            Preguntas frecuentes
                        </h2>
                        <!-- <p class="wow fadeInUp" data-wow-delay=".4s">
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed
                            diam nonumy eirmod tempor invidunt ut labore et dolore
                        </p> -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="categorias" id="categorias">
                    <div class="categoria activa" data-categoria="metodos-pago">
                        <svg viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                            clip-rule="evenodd">
                            <path
                                d="M21.19 7h2.81v15h-21v-5h-2.81v-15h21v5zm1.81 1h-19v13h19v-13zm-9.5 1c3.036 0 5.5 2.464 5.5 5.5s-2.464 5.5-5.5 5.5-5.5-2.464-5.5-5.5 2.464-5.5 5.5-5.5zm0 1c2.484 0 4.5 2.016 4.5 4.5s-2.016 4.5-4.5 4.5-4.5-2.016-4.5-4.5 2.016-4.5 4.5-4.5zm.5 8h-1v-.804c-.767-.16-1.478-.689-1.478-1.704h1.022c0 .591.326.886.978.886.817 0 1.327-.915-.167-1.439-.768-.27-1.68-.676-1.68-1.693 0-.796.573-1.297 1.325-1.448v-.798h1v.806c.704.161 1.313.673 1.313 1.598h-1.018c0-.788-.727-.776-.815-.776-.55 0-.787.291-.787.622 0 .247.134.497.957.768 1.056.344 1.663.845 1.663 1.746 0 .651-.376 1.288-1.313 1.448v.788zm6.19-11v-4h-19v13h1.81v-9h17.19z" />
                        </svg>
                        <p>Métodos de pago</p>
                    </div>
                    <div class="categoria" data-categoria="entregas">
                        <svg viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                            clip-rule="evenodd">
                            <path
                                d="M7 24h-5v-9h5v1.735c.638-.198 1.322-.495 1.765-.689.642-.28 1.259-.417 1.887-.417 1.214 0 2.205.499 4.303 1.205.64.214 1.076.716 1.175 1.306 1.124-.863 2.92-2.257 2.937-2.27.357-.284.773-.434 1.2-.434.952 0 1.751.763 1.751 1.708 0 .49-.219.977-.627 1.356-1.378 1.28-2.445 2.233-3.387 3.074-.56.501-1.066.952-1.548 1.393-.749.687-1.518 1.006-2.421 1.006-.405 0-.832-.065-1.308-.2-2.773-.783-4.484-1.036-5.727-1.105v1.332zm-1-8h-3v7h3v-7zm1 5.664c2.092.118 4.405.696 5.999 1.147.817.231 1.761.354 2.782-.581 1.279-1.172 2.722-2.413 4.929-4.463.824-.765-.178-1.783-1.022-1.113 0 0-2.961 2.299-3.689 2.843-.379.285-.695.519-1.148.519-.107 0-.223-.013-.349-.042-.655-.151-1.883-.425-2.755-.701-.575-.183-.371-.993.268-.858.447.093 1.594.35 2.201.52 1.017.281 1.276-.867.422-1.152-.562-.19-.537-.198-1.889-.665-1.301-.451-2.214-.753-3.585-.156-.639.278-1.432.616-2.164.814v3.888zm3.79-19.913l3.21-1.751 7 3.86v7.677l-7 3.735-7-3.735v-7.719l3.784-2.064.002-.005.004.002zm2.71 6.015l-5.5-2.864v6.035l5.5 2.935v-6.106zm1 .001v6.105l5.5-2.935v-6l-5.5 2.83zm1.77-2.035l-5.47-2.848-2.202 1.202 5.404 2.813 2.268-1.167zm-4.412-3.425l5.501 2.864 2.042-1.051-5.404-2.979-2.139 1.166z" />
                        </svg>
                        <p>Entregas</p>
                    </div>
                    <div class="categoria" data-categoria="seguridad">
                        <svg viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                            clip-rule="evenodd">
                            <path
                                d="M12 0c-3.371 2.866-5.484 3-9 3v11.535c0 4.603 3.203 5.804 9 9.465 5.797-3.661 9-4.862 9-9.465v-11.535c-3.516 0-5.629-.134-9-3zm0 1.292c2.942 2.31 5.12 2.655 8 2.701v10.542c0 3.891-2.638 4.943-8 8.284-5.375-3.35-8-4.414-8-8.284v-10.542c2.88-.046 5.058-.391 8-2.701zm5 7.739l-5.992 6.623-3.672-3.931.701-.683 3.008 3.184 5.227-5.878.728.685z" />
                        </svg>
                        <p>Seguridad</p>
                    </div>
                    <div class="categoria" data-categoria="cuenta">
                        <svg viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                            clip-rule="evenodd">
                            <path
                                d="M9.484 15.696l-.711-.696-2.552 2.607-1.539-1.452-.698.709 2.25 2.136 3.25-3.304zm0-5l-.711-.696-2.552 2.607-1.539-1.452-.698.709 2.25 2.136 3.25-3.304zm0-5l-.711-.696-2.552 2.607-1.539-1.452-.698.709 2.25 2.136 3.25-3.304zm10.516 11.304h-8v1h8v-1zm0-5h-8v1h8v-1zm0-5h-8v1h8v-1zm4-5h-24v20h24v-20zm-1 19h-22v-18h22v18z" />
                        </svg>
                        <p>Cuenta</p>
                    </div>
                </div>

                <div class="preguntas">

                    <!-- Preguntas Metodos de pago -->
                    <div class="contenedor-preguntas activo" data-categoria="metodos-pago">
                        <div class="contenedor-pregunta">
                            <p class="pregunta">¿Que metodos de pago disponibles tienen? <img
                                    src="{{ asset('app/assets_web_principal/carpetalogin/img/suma.svg') }}"
                                    alt="Abrir Respuesta" /></p>
                            <p class="respuesta">Aceptamos los siguientes métodos de pago: Yape, Plin y transferencia
                                bancaria..</p>
                        </div>
                        <div class="contenedor-pregunta">
                            <p class="pregunta">¿Tienen plazo de pago? <img
                                    src="{{ asset('app/assets_web_principal/carpetalogin/img/suma.svg') }}"
                                    alt="Abrir Respuesta" /></p>
                            <p class="respuesta">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatum
                                laborum porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates
                                soluta adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur,
                                doloribus.adipisicing elit. Voluptatum laborum porro voluptates, sequi aliquam mollitia!
                                Nostrum eius iure sapiente, voluptates soluta adipisci, perferendis voluptatibus
                                eligendi vel saepe harum. Consectetur, doloribus.</p>
                        </div>
                    </div>

                    <!-- Preguntas Entregas -->
                    <div class="contenedor-preguntas" data-categoria="entregas">
                        <div class="contenedor-pregunta">
                            <p class="pregunta">¿Tienen entregas a domicilio? <img
                                    src="{{ asset('app/assets_web_principal/carpetalogin/img/suma.svg') }}"
                                    alt="Abrir Respuesta" /></p>
                            <p class="respuesta">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatum
                                laborum porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates
                                soluta adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur,
                                doloribus.adipisicing elit. Voluptatum laborum porro voluptates, sequi aliquam mollitia!
                                Nostrum eius iure sapiente, voluptates soluta adipisci, perferendis voluptatibus
                                eligendi vel saepe harum. Consectetur, doloribus.adipisicing elit. Voluptatum laborum
                                porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates soluta
                                adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur, doloribus.</p>
                        </div>
                        <div class="contenedor-pregunta">
                            <p class="pregunta">¿En cuanto sale el envio a mi país? <img
                                    src="{{ asset('app/assets_web_principal/carpetalogin/img/suma.svg') }}"
                                    alt="Abrir Respuesta" /></p>
                            <p class="respuesta">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatum
                                laborum porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates
                                soluta adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur,
                                doloribus.adipisicing elit. Voluptatum laborum porro voluptates, sequi aliquam mollitia!
                                Nostrum eius iure sapiente, voluptates soluta adipisci, perferendis voluptatibus
                                eligendi vel saepe harum. Consectetur, doloribus.</p>
                        </div>
                    </div>

                    <!-- Preguntas Seguridad -->
                    <div class="contenedor-preguntas" data-categoria="seguridad">
                        <div class="contenedor-pregunta">
                            <p class="pregunta">¿Como puedo saber si son confiables? <img
                                    src="{{ asset('app/assets_web_principal/carpetalogin/img/suma.svg') }}"
                                    alt="Abrir Respuesta" /></p>
                            <p class="respuesta">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatum
                                laborum porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates
                                soluta adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur,
                                doloribus.adipisicing elit. Voluptatum laborum porro voluptates, sequi aliquam mollitia!
                                Nostrum eius iure sapiente, voluptates soluta adipisci, perferendis voluptatibus
                                eligendi vel saepe harum. Consectetur, doloribus.adipisicing elit. Voluptatum laborum
                                porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates soluta
                                adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur, doloribus.</p>
                        </div>
                        <div class="contenedor-pregunta">
                            <p class="pregunta">¿Que pasa con mis datos personales? <img
                                    src="{{ asset('app/assets_web_principal/carpetalogin/img/suma.svg') }}"
                                    alt="Abrir Respuesta" /></p>
                            <p class="respuesta">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatum
                                laborum porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates
                                soluta adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur,
                                doloribus.adipisicing elit. Voluptatum laborum porro voluptates, sequi aliquam mollitia!
                                Nostrum eius iure sapiente, voluptates soluta adipisci, perferendis voluptatibus
                                eligendi vel saepe harum. Consectetur, doloribus.</p>
                        </div>
                    </div>

                    <!-- Preguntas Cuenta -->
                    <div class="contenedor-preguntas" data-categoria="cuenta">
                        <div class="contenedor-pregunta">
                            <p class="pregunta">¿Como puedo acceder a mis pedidos? <img
                                    src="{{ asset('app/assets_web_principal/carpetalogin/img/suma.svg') }}"
                                    alt="Abrir Respuesta" /></p>
                            <p class="respuesta">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatum
                                laborum porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates
                                soluta adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur,
                                doloribus.adipisicing elit. Voluptatum laborum porro voluptates, sequi aliquam mollitia!
                                Nostrum eius iure sapiente, voluptates soluta adipisci, perferendis voluptatibus
                                eligendi vel saepe harum. Consectetur, doloribus.adipisicing elit. Voluptatum laborum
                                porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates soluta
                                adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur, doloribus.</p>
                        </div>
                        <div class="contenedor-pregunta">
                            <p class="pregunta">¿Como puedo cambiar mi contraseña? <img
                                    src="{{ asset('app/assets_web_principal/carpetalogin/img/suma.svg') }}"
                                    alt="Abrir Respuesta" /></p>
                            <p class="respuesta">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatum
                                laborum porro voluptates, sequi aliquam mollitia! Nostrum eius iure sapiente, voluptates
                                soluta adipisci, perferendis voluptatibus eligendi vel saepe harum. Consectetur,
                                doloribus.adipisicing elit. Voluptatum laborum porro voluptates, sequi aliquam mollitia!
                                Nostrum eius iure sapiente, voluptates soluta adipisci, perferendis voluptatibus
                                eligendi vel saepe harum. Consectetur, doloribus.</p>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======== feature-section end ======== -->
<!-- ======== subscribe-section start ======== -->
<section id="contact" class="subscribe-section pt-120">
    <div class="container">
        <div class="subscribe-wrapper img-bg">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-7">
                    <div class="section-title mb-15">
                        <h2 class="text-white mb-25">¿Aún no formas parte? ¡Te estamos esperando!</h2>
                        <p class="text-white pr-5">
                            Completa tu registro y descubre todo lo que tenemos para ti.
                        </p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-5 text-end">
                    <a href="{{ route('app.registro.index') }}" class="main-btn border-btn btn-hover wow fadeInUp"
                        data-wow-delay=".6s" style="color: white !important;border-color: white!important;">Registro</a>
                    <a href="{{ route('auth.login') }}" class="main-btn border-btn btn-hover wow fadeInUp"
                        data-wow-delay=".6s" style="color: white !important;border-color: white!important;">Ingresar</a>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- ======== subscribe-section end ======== -->

<!-- ======== footer start ======== -->
<footer class="footer">
    <div class="container">
        <div class="widget-wrapper">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <div class="logo mb-30">
                            <a href="{{ route('index') }}">
                                <img src="{{ asset('app/img/logo1.png') }}" alt="" />
                            </a>
                        </div>
                        <p class="desc mb-30 text-white" style="text-align: justify;">
                            En Essentium Group, entendemos que el verdadero crecimiento se construye con aliados
                            sólidos. Por eso, más que un proveedor, somos un socio estratégico que integra
                            abastecimiento, distribución y soporte para que tu negocio opere con eficiencia, seguridad y
                            proyección.
                        </p>
                        <ul class="socials">
                            <li>
                                <a href="https://www.facebook.com/share/1SxRAPxKmp/" target="_blank">
                                    <i class="lni lni-facebook-filled"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.tiktok.com/@essentium.import?_t=ZS-90a6GzNhKLf&_r=1" target="_blank">
                                    <i class="fa-brands fa-tiktok"></i>
                                </a>
                            </li>

                            <li>
                                <a href="https://www.instagram.com/essentium.import?igsh=MTQ5eTY3Y3N5MHNoMA==" target="_blank">
                                    <i class="lni lni-instagram-filled"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- ======== footer end ======== -->

<!-- ======== scroll-top ======== -->
<a href="#" class="scroll-top btn-hover">
    <i class="lni lni-chevron-up"></i>
</a>
@endsection