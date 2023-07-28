<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>@yield('titulo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A" name="description" />
    <meta content="Edna GB" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- plugin css -->
    <link href="assets/libs/jquery-vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
<style>
    .dot:hover,
    input[type="radio"] {
        cursor: pointer;
    }

    .far,
    .fas {
        font-size: 60px;
    }


    .fa-laugh {
        color: green;
    }

    .fa-smile {
        color: yellowgreen;
    }

    .fa-meh {
        color: rgb(241, 243, 95);
    }

    .fa-frown {
        color: orange;
    }

    .fa-sad-tear {
        color: red;
    }
    input[type="radio"] {
    -webkit-appearance: none; /* Safari y Chrome */
    -moz-appearance: none; /* Firefox */
    appearance: none; /* Estándar */
    outline: none; /* Eliminar el contorno al hacer clic */
    width: 16px; /* Ajusta el ancho del radio input */
    height: 16px; /* Ajusta la altura del radio input */
    border-radius: 50%; /* Hace que el input tenga forma de círculo */
    border: 2px solid transparent; /* Establece el borde del círculo */
}


/* Cambiar el tamaño del ícono cuando el radio button con id "mood1" está seleccionado */
input[type="radio"]#mood1:checked + label {
    font-size: 80px;
}

/* Cambiar el tamaño del ícono cuando el radio button con id "mood2" está seleccionado */
input[type="radio"]#mood2:checked + label {
    font-size: 80px;
}

/* Cambiar el tamaño del ícono cuando el radio button con id "mood3" está seleccionado */
input[type="radio"]#mood3:checked + label {
    font-size: 80px;
}

/* Cambiar el tamaño del ícono cuando el radio button con id "mood4" está seleccionado */
input[type="radio"]#mood4:checked + label {
    font-size: 80px;
}

/* Cambiar el tamaño del ícono cuando el radio button con id "mood5" está seleccionado */
input[type="radio"]#mood5:checked + label {
    font-size: 80px;
}
</style>

</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <div class="navbar-custom">
            <ul class="list-unstyled topnav-menu float-right mb-0">

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="pro-user-name ml-1">
                            {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0 text-white">
                                Bienvenido !
                            </h5>
                        </div>

                        <!-- item-->
                        <a href="{{ route('profile.show') }}" class="dropdown-item notify-item">
                            <i class="fe-user"></i>
                            <span>Mi cuenta</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                            <i class="fe-log-out"></i>
                            <span>Logout</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <a href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </a>
                        </form>



                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">{{ __('Log Out') }}</button>
                        </form>

                    </div>
                </li>



            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="index.html" class="logo text-center">
                    <span class="logo-lg">
                        <img src="assets/images/logo-light.png" alt="" height="16">
                        <!-- <span class="logo-lg-text-light">Xeria</span> -->
                    </span>
                    <span class="logo-sm">
                        <!-- <span class="logo-sm-text-dark">X</span> -->
                        <img src="assets/images/logo-sm.png" alt="" height="18">
                    </span>
                </a>
            </div>

            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile waves-effect">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </li>
            </ul>
        </div>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">

            <div class="slimscroll-menu">

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    <ul class="metismenu" id="side-menu">

                        <li class="menu-title">Navegación</li>

                        <li>
                            <a href="{{ route('indexideas') }}">
                                <i class="la la-dashboard"></i>
                                <span> Dashboards </span>
                            </a>
                        </li>
                        {{-- <div class="col-md-4">
                            <h5 class="text-dark mt-0">Class</h5>
                            <ul class=" fe-airplay list-unstyled megamenu-list mt-2">
                                <li>
                                    <a href="{{ route('classideas') }}">Semana 1</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Semana 2</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Semana 3</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Semana 4</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Extras</a>
                                </li>
                            </ul>
                        </div> --}}
                         <li>
                            <a href="{{ route('classideas') }}">
                                <i class="fe-airplay"></i>
                                <span>Class</span>
                            </a>
                        </li> 
                    </ul>

                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                @yield('contenido')
            </div> <!-- content -->
        </div>
        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        2023 &copy; <a href="https://www.mvsideas.com/"> MVS IDEAS</a>
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-right footer-links d-none d-sm-block">
                            <a href="javascript:void(0);">About Us</a>
                            <a href="javascript:void(0);">Help</a>
                            <a href="javascript:void(0);">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- Third Party js-->
    <script src="assets/libs/peity/jquery.peity.min.js"></script>
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="assets/libs/jquery-vectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="assets/libs/jquery-vectormap/jquery-jvectormap-us-merc-en.js"></script>
    <!-- Plugins js -->
    <script src="assets/libs/katex/katex.min.js"></script>
    <script src="assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
    <script src="assets/libs/quill/quill.min.js"></script>
    <!-- Dashboard init -->
    <script src="assets/js/pages/dashboard-1.init.js"></script>
    
    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    <!-- Init js-->
    <script src="assets/js/pages/form-wizard.init.js"></script>
    @yield('scripts')
</body>

</html>
