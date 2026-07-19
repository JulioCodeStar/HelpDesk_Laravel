<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
    <!-- Google Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <!-- Links Of CSS File -->
    <link href="{{ asset('assets/css/sidebar-menu.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/simplebar.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/prism.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/quill.snow.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/remixicon.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/swiper-bundle.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/jsvectormap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet"/>
    <!-- Favicon -->
    <link href="{{ asset('assets/images/favicon.png') }}" rel="icon" type="image/png"/>
    <!-- Title -->
    <title>
        Iniciar sesión - HelpDesk PandaNoir
    </title>
</head>
<body class="bg-body-bg">
<!-- Start Preloader Area -->
<div class="preloader" id="preloader">
    <div class="preloader">
        <div class="waviy position-relative">
                    <span class="d-inline-block">
                    P
                    </span>
            <span class="d-inline-block">
                    A
                    </span>
            <span class="d-inline-block">
                    N
                    </span>
            <span class="d-inline-block">
                    D
                    </span>
        </div>
    </div>
</div>
<!-- End Preloader Area -->
<div class="container-fluid">
    <div class="main-content d-flex flex-column p-0">
        <div class="m-lg-auto my-auto w-930 py-4">
            <div class="card bg-white border rounded-10 border-white py-100 px-130">
                <div class="p-md-5 p-4 p-lg-0">
                    <div class="text-center mb-4">
                        <h3 class="fs-26 fw-medium" style="margin-bottom: 6px;">
                            Iniciar sesión
                        </h3>
                        <p class="fs-16 text-secondary lh-1-8">
                            ¿Aún no tienes una cuenta?
                            <a class="text-primary text-decoration-none" href="{{ route('register') }}">
                                Regístrate
                            </a>
                        </p>
                    </div>

                    <!-- Mensaje de estado (ej. contraseña restablecida) -->
                    @if (session('status'))
                        <div class="alert alert-success fs-14" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-20">
                            <label class="label fs-16 mb-2">
                                Correo electrónico
                            </label>
                            <div class="form-floating">
                                <input class="form-control @error('email') is-invalid @enderror"
                                       id="floatingInput1"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Ingresa tu correo *"
                                       type="email"
                                       required
                                       autofocus/>
                                <label for="floatingInput1">
                                    Ingresa tu correo *
                                </label>
                            </div>
                            @error('email')
                            <div class="text-danger fs-14 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-20">
                            <label class="label fs-16 mb-2">
                                Tu contraseña
                            </label>
                            <div class="form-group" id="password-show-hide">
                                <div class="password-wrapper position-relative password-container">
                                    <input class="form-control text-secondary password @error('password') is-invalid @enderror"
                                           name="password"
                                           placeholder="Ingresa tu contraseña *"
                                           type="password"
                                           required/>
                                    <i aria-hidden="true" class="ri-eye-off-line password-toggle-icon translate-middle-y top-50 position-absolute cursor text-secondary" style="color: #A9A9C8; font-size: 22px; right: 15px;">
                                    </i>
                                </div>
                            </div>
                            @error('password')
                            <div class="text-danger fs-14 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-20">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                <div class="form-check">
                                    <input class="form-check-input" id="flexCheckDefault" name="remember" type="checkbox" value="1"/>
                                    <label class="form-check-label fs-16" for="flexCheckDefault">
                                        Recordarme
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="fs-16 text-primary fw-normal text-decoration-none" href="{{ route('password.request') }}">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-primary fw-normal text-white w-100" style="padding-top: 18px; padding-bottom: 18px;" type="submit">
                                Iniciar sesión
                            </button>
                        </div>
                        <div class="position-relative text-center z-1 mb-12">
                                    <span class="fs-16 bg-white px-4 text-secondary card d-inline-block border-0">
                                    o inicia sesión con
                                    </span>
                            <span class="d-block border-bottom border-2 position-absolute w-100 z-n1" style="top: 13px;">
                                    </span>
                        </div>
                        <!-- NOTA: estos botones sociales son solo visuales.
                             No están conectados a ningún login. Para activarlos
                             necesitarías Laravel Socialite. -->
                        <ul class="p-0 mb-0 list-unstyled d-flex justify-content-center" style="gap: 10px;">
                            <li>
                                <a class="d-inline-block rounded-circle text-decoration-none text-center text-white transition-y fs-16" href="#" style="width: 30px; height: 30px; line-height: 30px; background-color: #3a559f;">
                                    <i class="ri-facebook-fill">
                                    </i>
                                </a>
                            </li>
                            <li>
                                <a class="d-inline-block rounded-circle text-decoration-none text-center text-white transition-y fs-16" href="#" style="width: 30px; height: 30px; line-height: 30px; background-color: #0f1419;">
                                    <i class="ri-twitter-x-line">
                                    </i>
                                </a>
                            </li>
                            <li>
                                <a class="d-inline-block rounded-circle text-decoration-none text-center text-white transition-y fs-16" href="#" style="width: 30px; height: 30px; line-height: 30px; background-color: #e02f2f;">
                                    <i class="ri-google-fill">
                                    </i>
                                </a>
                            </li>
                            <li>
                                <a class="d-inline-block rounded-circle text-decoration-none text-center text-white transition-y fs-16" href="#" style="width: 30px; height: 30px; line-height: 30px; background-color: #007ab9;">
                                    <i class="ri-linkedin-fill">
                                    </i>
                                </a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<button class="switch-toggle dark-btn p-0 bg-transparent lh-0 border-0" id="switch-toggle">
</button>
<!-- Start Theme Setting Area -->
<button aria-controls="offcanvasScrolling" class="btn btn-primary theme-settings-btn p-0 position-fixed z-2 text-center rounded-circle" data-bs-target="#offcanvasScrolling" data-bs-toggle="offcanvas" style="bottom: 24px; right: 24px; width: 56px; height: 56px; line-height: 54px;" type="button">
    <i class="text-white ri-settings-3-fill fs-28" data-bs-placement="left" data-bs-title="Clic en configuración de tema" data-bs-toggle="tooltip">
    </i>
</button>
<!-- Start Theme Setting Area -->
<div aria-labelledby="offcanvasScrollingLabel" class="offcanvas offcanvas-end bg-white border-0" data-bs-backdrop="true" data-bs-scroll="true" id="offcanvasScrolling" style="box-shadow: 0 4px 20px #2f8fe812 !important; max-width: 300px;" tabindex="-1">
    <div class="offcanvas-header bg-light p-20">
        <h5 class="offcanvas-title fs-18 fw-medium" id="offcanvasScrollingLabel">
            Panel de configuración
        </h5>
        <button aria-label="Cerrar" class="btn-close" data-bs-dismiss="offcanvas" type="button">
        </button>
    </div>
    <div class="offcanvas-body p-0 overflow-hidden">
        <div class="last-child-none" data-simplebar="" style="max-height: 858px;">
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Modo RTL
                </h4>
                <div class="rtl-btn">
                    <label id="switch">
                        <input class="toggle-switch rtl-switch" id="slider" onchange="toggleTheme()" type="checkbox"/>
                    </label>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Solo barra lateral oscura
                </h4>
                <div class="sidebar-light-dark" id="sidebar-light-dark">
                    <input class="toggle-switch sidebar-dark-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Solo encabezado oscuro
                </h4>
                <div class="header-light-dark" id="header-light-dark">
                    <input class="toggle-switch header-dark-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Barra lateral derecha
                </h4>
                <div class="right-sidebar" id="right-sidebar">
                    <input class="toggle-switch right-sidebar-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Ocultar barra lateral
                </h4>
                <div class="icon-sidebar" id="icon-sidebar">
                    <input class="toggle-switch icon-sidebar-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Tarjeta con borde
                </h4>
                <div class="card-border" id="card-border">
                    <input class="toggle-switch border-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Radio de borde de tarjeta
                </h4>
                <div class="card-radius-square" id="card-radius-square">
                    <input class="toggle-switch border-radius-switch" type="checkbox"/>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Theme Setting Area -->
<!-- Link Of JS File -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/prism.js') }}"></script>
<script src="{{ asset('assets/js/clipboard.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/echarts.min.js') }}"></script>
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/fullcalendar.main.js') }}"></script>
<script src="{{ asset('assets/js/jsvectormap.min.js') }}"></script>
<script src="{{ asset('assets/js/world-merc.js') }}"></script>
<script src="{{ asset('assets/js/custom/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/custom/echarts.js') }}"></script>
<script src="{{ asset('assets/js/custom/maps.js') }}"></script>
<script src="{{ asset('assets/js/custom/custom.js') }}"></script>
</body>
</html>
