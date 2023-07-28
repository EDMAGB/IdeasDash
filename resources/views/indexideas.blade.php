@extends('plantilla.layouts')
@section('titulo', 'Dashboard')
@section('contenido')
    @php
        $semana = date('W');
        $anio = date('Y');
        $fechaActual = date('Y-m-d');
        $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));
        
    @endphp
    @auth

        <div class="row">
            <div class="col-6">
                <h2>HOLA, {{ Auth::user()->name }}! </h2>
            </div>
            <div class="col-3"></div>
        </div>
        {{-- @livewire('navigation-menu') --}}
        @if (count($proyectos) == 0)
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6">
                    <div class="card-box">
                        <h4 class="header-title mb-3">Crea tu primer proyecto</h4>
                        <button type="button" class="btn btn-block btn-rounded btn-lg btn-primary waves-effect waves-light"
                            data-toggle="modal" data-target=".bs-proyecto-modal-lg"><i class="icon-plus"></i> Proyecto</button>
                    </div>
                </div>
                <div class="col-3"></div>
            </div>
        @else
            <div class="row">
                <div class="col-9">
                </div>
                <div class="col-3">
                    <button type="button" class="btn btn-block btn-rounded btn-lg btn-primary waves-effect waves-light"
                        data-toggle="modal" data-target=".bs-proyecto-modal-lg"><i class="icon-plus"></i> Proyecto</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <div class="card-header">

                            <ul class="nav nav-tabs card-header-tabs">

                                @foreach ($proyectos as $pro)
                                    <li class="nav-item ">

                                        <a class="nav-link {{ $activeTab === 'tab' . $pro->id_proyecto ? 'active' : '' }}"
                                            href="{{ route('indexideas', ['tab' => 'tab' . $pro->id_proyecto]) }}">{{ $pro->nombre_proyecto }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @foreach ($proyectos as $pro)
                            @if ($activeTab === 'tab' . $pro->id_proyecto)
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <br>
                                            <h2>{{ $pro->nombre_proyecto }}</h2>
                                        </div>
                                        <div class="col-3">
                                            <form action="{{ route('rangos.proyecto', ['proyectoId' => $pro->id_proyecto]) }}"
                                                method="POST" id="rango-form">
                                                @csrf
                                                <div class="mx-sm-3">
                                                    <label for="status-select" class="mr-2">Rango semana</label>
                                                    <select class="custom-select" id="status-select" name="rango">
                                                        @foreach ($rangos as $rang)
                                                            @if ($rang->id_proyecto == $pro->id_proyecto)
                                                                <option value="{{ $rang->rango1 }}&{{ $rang->rango2 }}">
                                                                    {{ $rang->rango1 }} -
                                                                    {{ $rang->rango2 }}</option>
                                                            @endif
                                                        @endforeach
                                                        <option value="{{ $rangoInicioSemana }}&{{ $rangoFinSemana }}"
                                                            selected>
                                                            {{ $rangoInicioSemana }} - {{ $rangoFinSemana }}</option>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-3">
                                            <br>
                                            <button id="btnupdate{{ $pro->id_proyecto }}" type="button"
                                                class="btn btn-warning btn-rounded waves-effect waves-light" data-toggle="modal"
                                                data-target="#con-close-modal-{{ $pro->id_proyecto }}">Update Semanal</button>
                                            <button type="button" class="btn btn-danger btn-rounded waves-effect waves-light"
                                                data-toggle="modal" data-target="#equipo-modal-{{ $pro->id_proyecto }}"><i
                                                    class="icon-plus"></i> Integrantes</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4 col-md-4">
                                        <div class="card-box widget-rounded-circle bg-soft-warning rounded-0 card-box mb-0">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="avatar-lg rounded-circle bg-soft-warning">
                                                        <i class="fas fa-users font-24 avatar-title text-warning"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-right">
                                                        <h3 class="text-dark mt-1"><span data-plugin="counterup"
                                                                id="usuarios-d{{ $pro->id_proyecto }}"></span></h3>
                                                        <p class="text-warning mb-1 text-truncate">Usuarios Potenciales</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-xl-4 col-md-4">
                                        <div class="card-box widget-rounded-circle bg-soft-primary rounded-0 card-box mb-0">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="avatar-lg rounded-circle bg-soft-primary">
                                                        <i class="fas fa-money-bill-wave font-24 avatar-title text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-right">
                                                        <h3 class="text-dark mt-1"><span data-plugin="counterup"
                                                                id="ventas-d{{ $pro->id_proyecto }}"></span></h3>
                                                        <p class="text-primary mb-1 text-truncate">Ventas</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-xl-4 col-md-4">
                                        <div class="card-box widget-rounded-circle bg-soft-success rounded-0 card-box mb-0 ">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div id="carita1" class="avatar-lg rounded-circle bg-soft-success"
                                                        style="display: none">
                                                        <i class="far fa-sad-tear  font-24 avatar-title text-success"></i>
                                                    </div>
                                                    <div id="carita2" class="avatar-lg rounded-circle bg-soft-success"
                                                        style="display: none">
                                                        <i class="far fa-frown  font-24 avatar-title text-success"
                                                            style="display: none"></i>
                                                    </div>
                                                    <div id="carita3" class="avatar-lg rounded-circle bg-soft-success"
                                                        style="display: none">
                                                        <i class="far fa-meh  font-24 avatar-title text-success"></i>
                                                    </div>
                                                    <div id="carita4" class="avatar-lg rounded-circle bg-soft-success"
                                                        style="display: none">
                                                        <i class="far fa-smile  font-24 avatar-title text-success"></i>
                                                    </div>
                                                    <div id="carita4" class="avatar-lg rounded-circle bg-soft-success"
                                                        style="display: none">
                                                        <i class="far fa-laugh font-24 avatar-title text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <h3 class="text-dark mt-1"><span data-plugin="counterup"
                                                                id="animo-d{{ $pro->id_proyecto }}"></span></h3>
                                                        <p class="text-success mb-1 text-truncate">Estado de Animo</p>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col -->
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card-box">
                                            <h4 class="header-title mb-3">Objetivos de la Semana</h4>
                                            <div class="table-responsive">
                                                <table class="table table-borderless table-hover table-centered m-0"
                                                    id="tablaObj">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Integrante</th>
                                                            <th>Objetivo</th>
                                                            <th>Estatus</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaObjBody">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header bg-danger py-3 text-white">
                                                <div class="card-widgets">
                                                    <a data-toggle="collapse" href="#cardCollpase4" role="button"
                                                        aria-expanded="false" aria-controls="cardCollpase2"><i
                                                            class="mdi mdi-minus"></i></a>
                                                </div>
                                                <h5 class="card-title mb-0 text-white">Metrica</h5>
                                            </div>
                                            <div id="cardCollpase4" class="collapse show">
                                                <div class="card-body" id="metrica{{ $pro->id_proyecto }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header bg-info py-3 text-white">
                                                <div class="card-widgets">
                                                    <a data-toggle="collapse" href="#cardCollpase7" role="button"
                                                        aria-expanded="false" aria-controls="cardCollpase2"><i
                                                            class="mdi mdi-minus"></i></a>
                                                </div>
                                                <h5 class="card-title mb-0 text-white">Aprendizaje</h5>
                                            </div>
                                            <div id="cardCollpase7" class="collapse show">
                                                <div class="card-body" id="aprendizaje{{ $pro->id_proyecto }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header bg-warning py-3 text-white">
                                                <div class="card-widgets">
                                                    <a data-toggle="collapse" href="#cardCollpase6" role="button"
                                                        aria-expanded="false" aria-controls="cardCollpase2"><i
                                                            class="mdi mdi-minus"></i></a>
                                                </div>
                                                <h5 class="card-title mb-0 text-white">Obstaculo</h5>
                                            </div>
                                            <div id="cardCollpase6" class="collapse show">
                                                <div class="card-body" id="obstaculo{{ $pro->id_proyecto }}">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xl-6">
                                        <div class="card-box">
                                            <h4 class="header-title mb-3">Purpose Launchpad Radar</h4>
                                            <p>Texto que describe el estudio <a
                                                    href="https://platform.purposealliance.org/assessment/new/iframe/es/"
                                                    target="_blank">Contesta las preguntas</a></p>

                                            <br>
                                            <div id="contenidoimg{{ $pro->id_proyecto }}">

                                            </div>
                                        </div>
                                        <div class="card-box">
                                            <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip"
                                                data-placement="bottom" title="" data-original-title="More Info"></i>
                                            <h4 class="mt-0 font-16">Ganancias</h4>
                                            <h2 class="text-primary my-4 text-center">$<span data-plugin="counterup"
                                                    id="gananciastext{{ $pro->id_proyecto }}"></span></h2>
                                            <div class="row mb-4">
                                                <div class="col-6">
                                                    <p class="text-muted mb-1">Mes Actual</p>
                                                    <h3 class="mt-0 font-20 text-truncate"
                                                        id="gananciames{{ $pro->id_proyecto }}">
                                                        {{-- <small class="badge badge-light-success font-13">+15%</small> --}}
                                                    </h3>
                                                </div>

                                                <div class="col-6">
                                                    <p class="text-muted mb-1">Mes Anterior</p>
                                                    <h3 class="mt-0 font-20 text-truncate"
                                                        id="gananciasmesan{{ $pro->id_proyecto }}">
                                                        {{-- <small class="badge badge-light-danger font-13">-5%</small> --}}
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-box">
                                            <h4 class="header-title">Inversion / Ganancias</h4>                                        
                                            <div id="cardCollpase2" class="collapse pt-3 show" dir="ltr">
                                                <div id="grafInv" class="apex-charts"></div>
                                            </div> <!-- collapsed end -->
                                        </div> <!-- end card-box -->
                                        <div class="card-box">
                                            <div class="mt-5">
                                                <div class="card-box" dir="ltr">
                                                    <h4 class="header-title mb-1">Ventas</h4>
                                                    <div id="ventas-grafica{{ $pro->id_proyecto }}" class="apex-charts">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="card-box" dir="ltr">
                                                    <div class="float-right d-none d-md-inline-block">
                                                        <div class="btn-group mb-2">
                                                            <button type="button" class="btn btn-xs btn-secondary"
                                                                id="btn-semana">Semanas</button>
                                                            <button type="button" class="btn btn-xs btn-light"
                                                                id="btn-mes">Meses</button>
                                                            <button type="button" class="btn btn-xs btn-light"
                                                                id="btn-anio">Años</button>
                                                        </div>
                                                    </div>
                                                    <h4 class="header-title mb-1">Usuarios potenciales</h4>
                                                    <div id="user-potenciales{{ $pro->id_proyecto }}" class="apex-charts">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!-- Integrantes Modal -->
                            <div class="modal fade" id="equipo-modal-{{ $pro->id_proyecto }}" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">Agregar Integrante</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-1"></div>
                                                <div class="col-10">
                                                    <div class="card-box">
                                                        {{-- <form
                                                            action="{{ route('masintegrante.proyecto', ['proyectoId' => $pro->id_proyecto]) }}"
                                                            method="POST">
                                                            @csrf --}}
                                                        <div class="form-group mx-sm-3">
                                                            <input type="email" class="form-control"
                                                                id="inputcorreo{{ $pro->id_proyecto }}" name="email"
                                                                placeholder="Correo">
                                                        </div>
                                                        <button {{-- type="submit" --}} id="btnInte{{ $pro->id_proyecto }}"
                                                            class="btn btn-primary waves-effect waves-light"><i
                                                                class="icon-plus"></i>Agregar</button>
                                                        {{-- </form> --}}
                                                    </div> <!-- end card-box-->
                                                </div> <!-- end col-->
                                            </div>
                                            <hr>
                                            <div class="alert alert-danger" id="alertsInteg{{ $pro->id_proyecto }}"></div>
                                            <div class="alert alert-success" id="succesInteg{{ $pro->id_proyecto }}"></div>
                                            <div class="card-box">
                                                <h4 class="header-title">Integrantes</h4>
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nombre</th>
                                                                <th>Correo</th>
                                                                <th>Puesto</th>
                                                                <th>Acion</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tablaIntBody{{ $pro->id_proyecto }}">
                                                        </tbody>
                                                    </table>
                                                </div> <!-- end table-responsive-->

                                            </div> <!-- end card-box -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Update Semanal Modal -->
                            <div class="modal fade " id="con-close-modal-{{ $pro->id_proyecto }}" tabindex="-1"
                                role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Update Semanal</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <form>
                                                                <div id="progressbarwizard">

                                                                    <ul
                                                                        class="nav nav-pills bg-light nav-justified form-wizard-header mb-3">
                                                                        <li class="nav-item">
                                                                            <a href="#account-{{ $pro->id_proyecto }}"
                                                                                id="tab-users{{ $pro->id_proyecto }}"
                                                                                class="nav-link rounded-0 pt-2 pb-2 active">
                                                                                <i class="mdi mdi-account-circle mr-1"></i>
                                                                                <span
                                                                                    class="d-none d-sm-inline">Usuarios</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a href="#profile-tab-{{ $pro->id_proyecto }}"
                                                                                data-toggle="tab"
                                                                                id="tab-ventas{{ $pro->id_proyecto }}"
                                                                                class="nav-link rounded-0 pt-2 pb-2">
                                                                                <i class="mdi mdi-face-profile mr-1"></i>
                                                                                <span class="d-none d-sm-inline">Traccion y
                                                                                    Ventas</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a href="#finish-{{ $pro->id_proyecto }}"
                                                                                data-toggle="tab"
                                                                                id="tab-animo{{ $pro->id_proyecto }}"
                                                                                class="nav-link rounded-0 pt-2 pb-2">
                                                                                <i
                                                                                    class="mdi mdi-checkbox-marked-circle-outline mr-1"></i>
                                                                                <span class="d-none d-sm-inline">Metas y
                                                                                    Moral</span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>

                                                                    <div class="tab-content b-0 mb-0 pt-0">

                                                                        <div id="bar" class="progress mb-3"
                                                                            style="height: 7px;">
                                                                            <div id="barprogres{{ $pro->id_proyecto }}"
                                                                                class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                                                style="width: 33.3333%;"></div>
                                                                        </div>

                                                                        <div class="tab-pane active"
                                                                            id="account-{{ $pro->id_proyecto }}">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="row">
                                                                                        <div class="col-8 text-left"><label
                                                                                                class=""
                                                                                                for="personas">¿Usuarios
                                                                                                potenciales
                                                                                                con los que
                                                                                                hablaste en la última
                                                                                                semana?:</label>
                                                                                        </div>
                                                                                        <div class="col-3"> <input
                                                                                                type="text"
                                                                                                class="form-control"
                                                                                                id="personas{{ $pro->id_proyecto }}"
                                                                                                name="personas">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-6 text-left"><label
                                                                                                for="aprender">¿Que
                                                                                                aprendiste de
                                                                                                ellos?
                                                                                                :</label>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <textarea id="aprender{{ $pro->id_proyecto }}" class="form-control" name="aprender" data-parsley-trigger="keyup"
                                                                                                data-parsley-minlength="20" data-parsley-maxlength="10000"
                                                                                                data-parsley-minlength-message="vamos! Describe mas tu proyecto." data-parsley-validation-threshold="1000">
                                                                                                </textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> <!-- end col -->
                                                                            </div><br><!-- end row -->
                                                                            <div class="alert alert-success"
                                                                                style="display: none"
                                                                                id="succesUsers{{ $pro->id_proyecto }}"></div>
                                                                            <div class="row">
                                                                                <div class="col-4"></div>
                                                                                <div class="col-4 text-center">
                                                                                    <button type="button"
                                                                                        id="btnUsuarioG{{ $pro->id_proyecto }}"
                                                                                        class="bbtn btn-block btn-rounded btn-lg btn-primary waves-effect waves-light"><i
                                                                                            class="icon-plus"></i>
                                                                                        Guardar</button>
                                                                                </div>
                                                                                <div class="col-4"></div>
                                                                            </div>
                                                                            <br>
                                                                            <div class="row">
                                                                                <div class="col-6"></div>
                                                                                <div class="col-6">
                                                                                    <li class="next list-inline-item float-right"
                                                                                        id="btnusrs{{ $pro->id_proyecto }}">
                                                                                        <a href="javascript: void(0);"
                                                                                            class="btn btn-secondary"><i
                                                                                                class="fe-arrow-right"></i></a>
                                                                                    </li>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="tab-pane"
                                                                            id="profile-tab-{{ $pro->id_proyecto }}">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="form-group row mb-3">
                                                                                        <label class="col-md-3 col-form-label"
                                                                                            for="inve{{ $pro->id_proyecto }}">
                                                                                            Inversión</label>
                                                                                        <div class="col-md-9">
                                                                                            <input type="number"
                                                                                                id="inve{{ $pro->id_proyecto }}"
                                                                                                name="inve"
                                                                                                class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row mb-3">
                                                                                        <label class="col-md-3 col-form-label"
                                                                                            for="cantidad1{{ $pro->id_proyecto }}">
                                                                                            Venta</label>
                                                                                        <div class="col-md-9">
                                                                                            <input type="number"
                                                                                                id="cantidad1{{ $pro->id_proyecto }}"
                                                                                                name="cantidad1"
                                                                                                class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row mb-3">
                                                                                        <label class="col-md-3 col-form-label"
                                                                                            for="{{ $pro->id_proyecto }}">
                                                                                            Porcentaje</label>
                                                                                        <div class="col-md-9">
                                                                                            <input type="number"
                                                                                                id="porcentaje{{ $pro->id_proyecto }}"
                                                                                                name="porcentaje"
                                                                                                class="form-control">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="form-group row mb-3">
                                                                                        <label class="col-md-3 col-form-label"
                                                                                            for="ganancias{{ $pro->id_proyecto }}">Ganancias</label>
                                                                                        <div class="col-md-9">
                                                                                            <input type="money"
                                                                                                id="ganancias{{ $pro->id_proyecto }}"
                                                                                                name="ganancias"
                                                                                                class="form-control" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div> <!-- end row -->
                                                                            <br>
                                                                            <div class="alert alert-success"
                                                                                style="display: none"
                                                                                id="succesVenta{{ $pro->id_proyecto }}"></div>
                                                                            <br><!-- end row -->
                                                                            <div class="row">
                                                                                <div class="col-4"></div>
                                                                                <div class="col-4 text-center">
                                                                                    <button type="button"
                                                                                        id="btnVentaG{{ $pro->id_proyecto }}"
                                                                                        class="bbtn btn-block btn-rounded btn-lg btn-primary waves-effect waves-light"><i
                                                                                            class="icon-plus"></i>
                                                                                        Guardar/Actualizar</button>
                                                                                </div>
                                                                                <div class="col-4"></div>
                                                                            </div>
                                                                            <br>
                                                                            <div class="row text-left">
                                                                                <div class="col-6">
                                                                                    <li class="next list-inline-item"
                                                                                        id="btnaperfs{{ $pro->id_proyecto }}">
                                                                                        <a href="javascript: void(0);"
                                                                                            class="btn btn-secondary"><i
                                                                                                class="fe-arrow-left"></i></a>
                                                                                    </li>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <li class="next list-inline-item float-right"
                                                                                        id="btnperfs{{ $pro->id_proyecto }}">
                                                                                        <a href="javascript: void(0);"
                                                                                            class="btn btn-secondary"><i
                                                                                                class="fe-arrow-right"></i></a>
                                                                                    </li>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="tab-pane"
                                                                            id="finish-{{ $pro->id_proyecto }}">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="row">
                                                                                        <div class="col-12 text-left">
                                                                                            <div class="mood-selector">
                                                                                                ¿Cuál es tu estado de ánimo?:
                                                                                                <br>
                                                                                                <div id="selector"
                                                                                                    name="animo">
                                                                                                    <input type="radio"
                                                                                                        id="mood1"
                                                                                                        name="mood"
                                                                                                        value="1">
                                                                                                    <label for="mood1"
                                                                                                        class="far fa-sad-tear"></label>

                                                                                                    <input type="radio"
                                                                                                        id="mood2"
                                                                                                        name="mood"
                                                                                                        value="2">
                                                                                                    <label for="mood2"
                                                                                                        class="far fa-frown"></label>

                                                                                                    <input type="radio"
                                                                                                        id="mood3"
                                                                                                        name="mood"
                                                                                                        value="3">
                                                                                                    <label for="mood3"
                                                                                                        class="far fa-meh"></label>

                                                                                                    <input type="radio"
                                                                                                        id="mood4"
                                                                                                        name="mood"
                                                                                                        value="4">
                                                                                                    <label for="mood4"
                                                                                                        class="far fa-smile"></label>

                                                                                                    <input type="radio"
                                                                                                        id="mood5"
                                                                                                        name="mood"
                                                                                                        value="5"
                                                                                                        class="fa-laugh">
                                                                                                    <label for="mood5"
                                                                                                        class="far fa-laugh"></label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div> <!-- end col -->
                                                                                    </div> <!-- end row -->
                                                                                    <div class="row">
                                                                                        <div class="col-12 text-left">
                                                                                            <br>
                                                                                            <label for="metrica">¿Qué
                                                                                                mejoró más
                                                                                                la métrica principal?
                                                                                                :</label>
                                                                                            <br>
                                                                                            <textarea id="metricaUPD{{ $pro->id_proyecto }}" class="form-control" name="metrica" data-parsley-trigger="keyup"
                                                                                                data-parsley-minlength="20" data-parsley-maxlength="10000"
                                                                                                data-parsley-minlength-message="vamos! Describe mas tu proyecto." data-parsley-validation-threshold="100">
                                                                                                </textarea>
                                                                                        </div> <!-- end col -->
                                                                                    </div> <!-- end row -->
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-12 text-left">
                                                                                            <br>
                                                                                            <label for="obstaculo">¿Cuál ha
                                                                                                sido el
                                                                                                mayor obstáculo?
                                                                                                :</label>
                                                                                            <br>
                                                                                            <textarea id="obstaculoUPD{{ $pro->id_proyecto }}" class="form-control" name="obstaculo"
                                                                                                data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="10000"
                                                                                                data-parsley-minlength-message="vamos! Describe mas tu proyecto." data-parsley-validation-threshold="100">
                                                                                                </textarea>
                                                                                        </div> <!-- end col -->
                                                                                    </div> <!-- end row -->
                                                                                    <br>
                                                                                    <div class="alert alert-success"
                                                                                        style="display: none"
                                                                                        id="succesAnimos{{ $pro->id_proyecto }}">
                                                                                    </div>
                                                                                    <br><!-- end row -->
                                                                                    <div class="row">
                                                                                        <div class="col-4"></div>
                                                                                        <div class="col-4 text-center">
                                                                                            <button type="button"
                                                                                                id="btnMoralG{{ $pro->id_proyecto }}"
                                                                                                class="bbtn btn-block btn-rounded btn-lg btn-primary waves-effect waves-light"><i
                                                                                                    class="icon-plus"></i>
                                                                                                Guardar/Actualizar</button>
                                                                                        </div>
                                                                                        <div class="col-4"></div>
                                                                                    </div>
                                                                                </div> <!-- end col -->
                                                                            </div> <!-- end row -->
                                                                            <hr>
                                                                            <div class="row">
                                                                                <div class="col-8">
                                                                                    <div class="form-group">
                                                                                        <label for="objetivo">¿Cuáles son
                                                                                            sus
                                                                                            principales objetivos para la
                                                                                            próxima semana?
                                                                                            :</label>
                                                                                        <textarea id="objetivo{{ $pro->id_proyecto }}" class="form-control" name="objetivo" data-parsley-trigger="keyup"
                                                                                            data-parsley-minlength="20" data-parsley-maxlength="10000"
                                                                                            data-parsley-minlength-message="vamos! Describe mas tu proyecto." data-parsley-validation-threshold="100">
                                                                                    </textarea>
                                                                                    </div>
                                                                                </div> <!-- end col -->
                                                                                <div class="col-4" style="margin-top:5% ">
                                                                                    <select
                                                                                        name="usuario_id{{ $pro->id_proyecto }}">
                                                                                        <option value="a">Selecciona
                                                                                            un
                                                                                            colaborador</option>

                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-4"></div>
                                                                                <div class="col-4 text-center">
                                                                                    <button type="button"
                                                                                        id="btnObjetivoG{{ $pro->id_proyecto }}"
                                                                                        class="bbtn btn-block btn-rounded btn-lg btn-primary waves-effect waves-light"><i
                                                                                            class="icon-plus"></i>
                                                                                        Agregar</button>
                                                                                </div>
                                                                                <div class="col-4"></div>
                                                                            </div>
                                                                            <hr>
                                                                            <h4 class="header-title mb-3">Objetivos de la
                                                                                Semana</h4>
                                                                            <div class="table-responsive">
                                                                                <table
                                                                                    class="table table-borderless table-hover table-centered m-0">
                                                                                    <thead class="thead-light">
                                                                                        <tr>
                                                                                            <th>Integrante</th>
                                                                                            <th>Objetivo</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="tablaObjmoodal">
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            <br>
                                                                            <div class="row text-left">
                                                                                <div class="col-6">
                                                                                    <li class="next list-inline-item"
                                                                                        id="btnanimos{{ $pro->id_proyecto }}">
                                                                                        <a href="javascript: void(0);"
                                                                                            class="btn btn-secondary"><i
                                                                                                class="fe-arrow-left"></i></a>
                                                                                    </li>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <li
                                                                                        class="next list-inline-item float-right">
                                                                                        <a id="reloadLink"
                                                                                            class="btn btn-secondary">Finalizar</a>
                                                                                    </li>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div> <!-- tab-content -->
                                                                </div> <!-- end #progressbarwizard-->
                                                        </div> <!-- end card-body -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> <!-- end card-box-->
                </div> <!-- end col -->
            </div> <!-- end row -->
        @endif


        <!--  Modal PROYECTO-->
        <div class="modal fade bs-proyecto-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">Agregar Proyeto</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">
                                    <h4 class="header-title">Datos Generales</h4>
                                    <div class="alert alert-warning d-none fade show">
                                        <h4 class="mt-0">Oh no!</h4>
                                        <p class="mb-0">No es valido el formulario :(</p>
                                    </div>

                                    <div class="alert alert-info d-none fade show">
                                        <h4 class="mt-0">ok!</h4>
                                        <p class="mb-0">Todo se ve muy bien :)</p>
                                    </div>

                                    <form action=" {{ route('nuevoproyecto.proyecto') }}" method="POST" id="newproyect"
                                        data-parsley-validate="">
                                        @csrf
                                        <div class="form-group">
                                            <label for="proyectoname">Nombre del proyecto* :</label>
                                            <input type="text" class="form-control" name="proyectoname"
                                                id="proyectoname">
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion">Descripcion del proyecto (20 caracteres min, 10000 max)*
                                                :</label>
                                            <textarea id="descripcion" class="form-control" name="descripcion" data-parsley-trigger="keyup"
                                                data-parsley-minlength="20" data-parsley-maxlength="10000"
                                                data-parsley-minlength-message="vamos! Describe mas tu proyecto." data-parsley-validation-threshold="100">
                                     </textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="url">Pagina web del proyecto (url):</label>
                                            <input type="text" class="form-control" name="url" id="url">
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="div">División participantes MVS:</label>
                                            <label for="div" class="sr-only">Buscar</label>
                                            <input type="search" class="form-control" id="inputPassword2"
                                                placeholder="Search...">
                                            <button type="button" class="btn btn-success waves-effect waves-light mr-1"><i
                                                    class="la la-search"></i></button>
                                        </div> --}}
                                        <div class="form-group">
                                            <label>Etapa de la empresa*:</label>

                                            <div class="radio mb-1">
                                                <input type="radio" name="gender" id="etp1" value="1">
                                                <label for="etp1">
                                                    Etapa 1
                                                </label>
                                                <p>Estás en el proceso de validar tu idea y construir un producto inicial. Estás
                                                    hablando con usuarios potenciales sobre tu idea.</p>
                                            </div>
                                            <div class="radio">
                                                <input type="radio" name="gender" id="etp2" value="2">
                                                <label for="etp2">
                                                    Etapa 2
                                                </label>
                                                <p>Ha construido un MVP y está hablando con los primeros clientes. Es posible
                                                    que tenga algunos usuarios. Obtener comentarios de los clientes. Iterar.</p>
                                            </div>
                                            <div class="radio">
                                                <input type="radio" name="gender" id="etp3" value="2">
                                                <label for="etp3">
                                                    Etapa 3
                                                </label>
                                                <p>Tienes un producto con muchos usuarios activos. Estás trabajando para
                                                    aumentar los ingresos / usuarios / ventas.</p>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <input type="submit" class="btn btn-success" value="Crear">
                                        </div>
                                    </form>
                                </div> <!-- end card-box-->
                            </div> <!-- end col-->
                        </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->




    @endauth
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var selectElement = document.getElementById('status-select');
        var formElement = document.getElementById('rango-form');

        selectElement.addEventListener('change', function() {
            obtenerDatosconlosrangos();
            obtenerDatosGanancias();
            // formElement.submit(); 
        });
        // Obtener los datos de la gráfica
        function obtenerDatosGrafica(opcion) {
            var primer = $('#status-select').val();
            console.log('rango', primer);
            var fechas = primer.split("&");
            var fecha1 = fechas[0];
            var fecha2 = fechas[1];
            var activeHref = $('a.nav-link.active').attr('href');
            var matches = activeHref.match(/=tab(\d+)/);
            if (matches && matches.length > 1) {
                var digitos = matches[1];
                $.ajax({
                    url: "{{ route('datos.grafica', ['opcion' => ':opcion', 'idProyecto' => ':idProyecto', 'Rango1' => ':Rango1', 'Rango2' => ':Rango2']) }}"
                        .replace(':opcion', opcion).replace(':idProyecto', digitos).replace(':Rango1', fecha1)
                        .replace(':Rango2', fecha2),
                    method: 'GET',
                    success: function(response) {
                        console.log(response);
                        // Convertir los datos recibidos en un formato adecuado para ApexCharts
                        var datos = response.map(function(item) {
                            console.log('hola', item);
                            return {
                                x: item.semanaanio,
                                y: item.total_personas
                            };
                        });

                        // Configurar la gráfica
                        var options = {
                            chart: {
                                type: 'bar',
                                height: 350
                            },
                            series: [{
                                data: datos
                            }],
                            xaxis: {
                                type: 'category',
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'dark',
                                    gradientToColors: ['#66bb6a', '#9CE59F', '#D6ECA0'],
                                    shadeIntensity: 1,
                                    type: 'vertical',
                                    opacityFrom: 1,
                                    opacityTo: 1,
                                    stops: [0, 100, 100, 100]
                                },
                            },
                        };

                        var contenedorId = "user-potenciales" + digitos;

                        // Seleccionar el elemento dinámicamente
                        var contenedor = document.querySelector("#" + contenedorId);

                        // Crear la instancia de ApexCharts y renderizar la gráfica en el contenedor seleccionado
                        var chart = new ApexCharts(contenedor, options);
                        chart.render();
                    },

                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            } else {
                console.log('No se encontraron coincidencias');
            }


        }

        function obtenerDatosGraficaventas(opcion) {
            console.log(opcion)
            // Obtener el atributo href del elemento <a> activo
            var activeHref = $('a.nav-link.active').attr('href');

            // Aplicar expresión regular para obtener los dígitos después de 'tab'
            var matches = activeHref.match(/=tab(\d+)/);

            // Verificar si se encontraron coincidencias y obtener los dígitos
            if (matches && matches.length > 1) {
                var digitos = matches[1];
                console.log(digitos); // Imprimir los dígitos en la consola

                $.ajax({
                    url: "{{ route('datos.graficaventas', ['opcion' => ':opcion', 'idProyecto' => ':idProyecto']) }}"
                        .replace(':opcion', opcion).replace(':idProyecto', digitos),
                    method: 'GET',
                    success: function(response) {
                        console.log(response);
                        // Convertir los datos recibidos en un formato adecuado para ApexCharts
                        var datos = response.map(function(item) {
                            console.log('holaventas', item);
                            return {
                                x: item.semanaanio,
                                y: item.total_ganancias
                            };
                        });

                        // Configurar la gráfica
                        var options = {
                            chart: {
                                type: 'line',
                                height: 380,
                                shadow: {
                                    enabled: false,
                                    color: '#bbb',
                                    top: 3,
                                    left: 2,
                                    blur: 3,
                                    opacity: 1
                                },
                            },
                            stroke: {
                                width: 6,
                                curve: 'smooth'
                            },
                            series: [{
                                data: datos
                            }],
                            xaxis: {
                                type: 'category',
                                title: {
                                    text: 'Fecha',
                                },
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'dark',
                                    gradientToColors: ['#f0643b'],
                                    shadeIntensity: 1,
                                    type: 'horizontal',
                                    opacityFrom: 1,
                                    opacityTo: 1,
                                    stops: [0, 100, 100, 100]
                                },
                            },
                            markers: {
                                size: 4,
                                opacity: 0.9,
                                colors: ["#56c2d6"],
                                strokeColor: "#fff",
                                strokeWidth: 2,
                                style: 'inverted', // full, hollow, inverted
                                hover: {
                                    size: 7,
                                }
                            },

                            yaxis: {
                                title: {
                                    text: 'Ganancias',
                                },
                            },
                            grid: {
                                row: {
                                    colors: ['transparent',
                                        'transparent'
                                    ], // takes an array which will be repeated on columns
                                    opacity: 0.2
                                },
                                borderColor: '#185a9d'
                            },
                            responsive: [{
                                breakpoint: 600,
                                options: {
                                    chart: {
                                        toolbar: {
                                            show: false
                                        }
                                    },
                                    legend: {
                                        show: false
                                    },
                                }
                            }]
                        };

                        var contenedorId = "ventas-grafica" + digitos;

                        // Seleccionar el elemento dinámicamente
                        var contenedor = document.querySelector("#" + contenedorId);

                        // Crear la instancia de ApexCharts y renderizar la gráfica en el contenedor seleccionado
                        var chart = new ApexCharts(contenedor, options);
                        chart.render();
                    },

                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            } else {
                console.log('No se encontraron coincidencias');
            }


        }

        function obtenerDatosGraficaInv(opcion) {
            var primer = $('#status-select').val();
            console.log('rango', primer);
            var fechas = primer.split("&");
            var fecha1 = fechas[0];
            var fecha2 = fechas[1];
            var activeHref = $('a.nav-link.active').attr('href');
            var matches = activeHref.match(/=tab(\d+)/);
            if (matches && matches.length > 1) {
                var digitos = matches[1];
                $.ajax({
                    url: "{{ route('datos.graficaInv', ['opcion' => ':opcion', 'idProyecto' => ':idProyecto', 'Rango1' => ':Rango1', 'Rango2' => ':Rango2']) }}"
                        .replace(':opcion', opcion).replace(':idProyecto', digitos).replace(':Rango1', fecha1)
                        .replace(':Rango2', fecha2),
                    method: 'GET',
                    success: function(response) {
                        console.log(response);




                        var options = {
                            chart: {
                                height: 480,
                                type: 'line',
                                zoom: {
                                    enabled: false
                                },
                                toolbar: {
                                    show: false
                                }
                            },
                            colors: ['#f0643b', '#56c2d6'],
                            dataLabels: {
                                enabled: true,
                            },
                            stroke: {
                                width: [3, 3],
                                curve: 'smooth'
                            },
                            series: [{
                                    name: "Previus Week",
                                    data: [32, 42, 42, 62, 52, 75, 62]
                                },
                                {
                                    name: "Current Week",
                                    data: [42, 58, 66, 93, 82, 105, 92]
                                }
                            ],
                            grid: {
                                row: {
                                    colors: ['transparent',
                                    'transparent'], // takes an array which will be repeated on columns
                                    opacity: 0.2
                                },
                                borderColor: '#f1f3fa'
                            },
                            markers: {
                                style: 'inverted',
                                size: 6
                            },
                            xaxis: {
                                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                                title: {
                                    text: 'Week 12 - March 18, 2019 to March 24, 2019'
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Sales Analytics'
                                },
                                min: 5,
                                max: 120
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'right',
                                floating: true,
                                offsetY: -25,
                                offsetX: -5
                            },
                            responsive: [{
                                breakpoint: 600,
                                options: {
                                    chart: {
                                        toolbar: {
                                            show: false
                                        }
                                    },
                                    legend: {
                                        show: false
                                    },
                                }
                            }]
                        }

                        var contenedorId = "grafInv" + digitos;

                        // Seleccionar el elemento dinámicamente
                        var contenedor = document.querySelector("#" + contenedorId);

                        // Crear la instancia de ApexCharts y renderizar la gráfica en el contenedor seleccionado
                        var chart = new ApexCharts(contenedor, options);
                        chart.render();
                    },

                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            } else {
                console.log('No se encontraron coincidencias');
            }

        }

        function obtenerDatosGanancias() {
            var primer = $('#status-select').val();
            console.log('rango Ganancias', primer);
            var fechas = primer.split("&");
            var fecha1 = fechas[0];
            var fecha2 = fechas[1];
            var activeHref = $('a.nav-link.active').attr('href');
            var matches = activeHref.match(/=tab(\d+)/);
            if (matches && matches.length > 1) {
                var digitos = matches[1];
                $.ajax({
                    url: "{{ route('datos.ganancias', ['idProyecto' => ':idProyecto', 'Rango2' => ':Rango2']) }}"
                        .replace(':idProyecto', digitos).replace(':Rango2', fecha2),
                    method: 'GET',
                    success: function(response) {
                        console.log('ganancias->', response)
                        // Convertir los datos recibidos en un formato adecuado para ApexCharts
                        var datos = response.map(function(item) {
                            console.log('holaganancias', item);
                            $('#gananciastext' + digitos).text('');
                            $('#gananciames' + digitos).text('');
                            $('#gananciasmesan' + digitos).text('');
                            $('#usuarios-d' + digitos).text('');
                            $('#ventas-d' + digitos).text('');
                            $('#animo-d' + digitos).text('');
                            $('#aprendizaje' + digitos).text('');
                            $('#metrica' + digitos).text('');
                            $('#obstaculo' + digitos).text('');
                            $('#contenidoimg' + digitos).html('');
                            $('#aprender' + digitos).text('');
                            $('#personas' + digitos).val('');
                            $('#inve' + digitos).val('');
                            $('#cantidad1' + digitos).val('');
                            $('#porcentaje' + digitos).val('');
                            $('#ganancias' + digitos).val('');
                            $('#metricaUPD' + digitos).val('');
                            $('#obstaculoUPD' + digitos).val('');
                            $('input[name="mood"][value="' + item.animo + '"]').prop('checked', true);

                            $('#gananciastext' + digitos).text(item.suma_total);
                            $('#gananciames' + digitos).text('$' + item.suma_mesactual);
                            $('#gananciasmesan' + digitos).text('$' + item.suma_mesanterior);
                            $('#usuarios-d' + digitos).text(item.personas);
                            $('#aprender' + digitos).text(item.aprender);
                            $('#personas' + digitos).val(item.personas);
                            var valorMonetario = item.cantidad.toLocaleString('es-MX', {
                                style: 'currency',
                                currency: 'MXN'
                            });

                            $('#ventas-d' + digitos).text(valorMonetario);

                            if (item.animo == 1) {
                                $('#animo-d' + digitos).text('Angustia');
                                $('#carita1').css('display', 'block');
                            } else if (item.animo == 2) {
                                $('#animo-d' + digitos).text('Insatisfacción');
                                $('#carita2').css('display', 'block');
                            } else if (item.animo == 3) {
                                $('#animo-d' + digitos).text('Neutral');
                                $('#carita3').css('display', 'block');
                            } else if (item.animo == 4) {
                                $('#animo-d' + digitos).text('Satisfacción');
                                $('#carita4').css('display', 'block');
                            } else if (item.animo == 5) {
                                $('#animo-d' + digitos).text('Regocijo');
                                $('#carita5').css('display', 'block');
                            }
                            $('#aprendizaje' + digitos).text(item.aprender);
                            $('#metrica' + digitos).text(item.metrica);
                            $('#obstaculo' + digitos).text(item.obstaculo);
                            $('#inve' + digitos).val(item.inversion);
                            $('#cantidad1' + digitos).val(item.venta);
                            $('#porcentaje' + digitos).val(item.porcentaje);
                            $('#ganancias' + digitos).val(item.ganancia);
                            $('#metricaUPD' + digitos).val(item.metrica);
                            $('#obstaculoUPD' + digitos).val(item.obstaculo);

                            if (item.imagen == 0) {
                                $('#contenidoimg' + digitos).html(`<form action="{{ route('upload.image') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div>
                                                    <input value="` + digitos + `" name="id_proyect"
                                                        style="display:none">
                                                    <label for="image">Selecciona una imagen:</label>
                                                    <input type="file" name="imagen" id="imagen">
                                                </div>
                                                  <br>
                                                <div >
                                                    <button type="submit" class="btn btn-pink btn-rounded waves-effect waves-light">Subir imagen</button>
                                                </div>
                                            </form> `)
                            } else {
                                $('#contenidoimg' + digitos).html(` <img src="{{ asset('images/`+item.imagen+` ') }}" alt="Imagen" width="400" height="350">
                                `)
                            }
                            var tablaIntBody = document.getElementById("tablaIntBody" +
                                digitos);
                            tablaIntBody.innerHTML = "";
                            item.integrantes.forEach(function(integrante, index) {
                                var row = tablaIntBody.insertRow();
                                row.innerHTML = `  
                                 <td>${integrante.id_usuario}</td>
                                               <td>${integrante.name}</td>
                                                <td>${integrante.email}</td>
                                                <td>${integrante.puesto}</td>
                                                <td>
                                                    <form action="/eliminarintegrantes/${integrante.id_usuario}/${digitos}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-rounded waves-effect waves-light">
                                                            <i class="fe-trash-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                        `;

                                $('select[name="usuario_id' + digitos + '"').append(`<option value="${integrante.id_usuario}">
                                                                    ${integrante.name}
                                                                  </option>`);

                            });


                        });
                    },

                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            } else {
                console.log('No se encontraron coincidencias');
            }


        }

        function obtenerDatosconlosrangos() {
            var tablaBody = document.getElementById('tablaObjBody');
            while (tablaBody.firstChild) {
                tablaBody.removeChild(tablaBody.firstChild);
            }
            var tablaBodymodal = document.getElementById('tablaObjmoodal');
            while (tablaBodymodal.firstChild) {
                tablaBodymodal.removeChild(tablaBodymodal.firstChild);
            }
            var primer = $('#status-select').val();
            console.log('rango', primer);
            var fechas = primer.split("&");
            var fecha1 = fechas[0];
            var fecha2 = fechas[1];
            var activeHref = $('a.nav-link.active').attr('href');
            var matches = activeHref.match(/=tab(\d+)/);
            if (matches && matches.length > 1) {
                var digitos = matches[1];
                console.log('Proyecto->', digitos);
                console.log('rango1->', fecha1);
                console.log('rango2->', fecha2);
                $.ajax({
                    url: "{{ route('datos.objetivos', ['idProyecto' => ':idProyecto', 'Rango1' => ':Rango1', 'Rango2' => ':Rango2']) }}"
                        .replace(':idProyecto', digitos).replace(':Rango1', fecha1).replace(':Rango2', fecha2),
                    method: 'GET',
                    success: function(response) {
                        // Resto del código...
                        response.forEach(function(obj) {
                            // Crear una nueva fila
                            var fila = document.createElement('tr');
                            var fila2 = document.createElement('tr');
                            // Crear y agregar celdas a la fila...
                            var celdaNombre = document.createElement('td');
                            celdaNombre.innerHTML = '<h5 class="m-0 font-weight-normal">' + obj.name +
                                '</h5>';
                            var celdaNombre2 = document.createElement('td');
                            celdaNombre2.innerHTML = '<h5 class="m-0 font-weight-normal">' + obj.name +
                                '</h5>';

                            fila.appendChild(celdaNombre);
                            fila2.appendChild(celdaNombre2)

                            var celdaObjetivo = document.createElement('td');
                            celdaObjetivo.textContent = obj.objetivo;
                            var celdaObjetivo2 = document.createElement('td');
                            celdaObjetivo2.textContent = obj.objetivo;
                            fila.appendChild(celdaObjetivo);
                            fila2.appendChild(celdaObjetivo2);

                            var celdaStatus = document.createElement('td');
                            if (obj.status == 1) {
                                var form = document.createElement('form');
                                form.action = "{{ route('cambiarstatus', ['idObjt' => ':idObj']) }}"
                                    .replace(':idObj', obj.id_obj);
                                form.method = "GET";
                                form.onsubmit = function() {
                                    return confirm(
                                        '¿Estás seguro de que concluiste este objetivo?');
                                };

                                var button = document.createElement('button');
                                button.type = 'submit';
                                button.className =
                                    'btn btn-danger btn-rounded waves-effect waves-light';
                                button.innerHTML = '<i class="mdi mdi-close-outline"></i>';

                                form.appendChild(button);
                                celdaStatus.appendChild(form);
                            } else {
                                var button = document.createElement('button');
                                button.type = 'button';
                                button.className =
                                    'btn btn-success btn-rounded waves-effect waves-light';
                                button.disabled = true;
                                button.innerHTML = '<i class="mdi mdi-check-outline"></i>';

                                celdaStatus.appendChild(button);
                            }

                            fila.appendChild(celdaStatus);

                            // Agregar la fila a la tabla
                            tablaBody.appendChild(fila);
                            tablaBodymodal.appendChild(fila2)


                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });


            }

        }

        function obtebertalaintegrantes(correo, id_proyecto) {
            console.log('ENTRO AL METODO CON ', correo, id_proyecto)
            if (correo && id_proyecto) {
                $.ajax({
                    url: "{{ route('masintegrante.proyecto', ['proyectoId' => ':idProyecto', 'correo' => ':Correo']) }}"
                        .replace(':idProyecto', id_proyecto)
                        .replace(':Correo', correo),
                    method: 'GET',
                    success: function(response) {
                        console.log('integrantes', response)
                        var tablaIntBody = document.getElementById("tablaIntBody" + id_proyecto);
                        tablaIntBody.innerHTML = "";
                        response.forEach(function(integrante, index) {

                            var row = tablaIntBody.insertRow();
                            if (integrante.id_usuario) {
                                row.innerHTML = `   <td>${integrante.id_usuario}</td>
                                               <td>${integrante.name}</td>
                                                <td>${integrante.email}</td>
                                                <td>${integrante.puesto}</td>
                                                <td>
                                                    <form action="/eliminarintegrantes/${integrante.id_usuario}/${id_proyecto}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-rounded waves-effect waves-light">
                                                            <i class="fe-trash-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                        `;
                            } else {
                                console.log(integrante.mnsj);
                                $('#succesInteg' + id_proyecto).text(integrante.mnsj);
                            }
                        });

                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            } else {
                console.log('anun no ')
            }
        }

        function guardaruser(id_proyecto, aprender, personas) {
            var primer = $('#status-select').val();
            var fechas = primer.split("&");
            var fecha1 = fechas[0];
            var fecha2 = fechas[1];
            $.ajax({
                url: "{{ route('reportesemanal.proyecto', ['proyectoId' => ':idProyecto', 'aprender' => ':Aprender', 'personas' => ':Personas', 'rango' => ':Rango']) }}"
                    .replace(':idProyecto', id_proyecto)
                    .replace(':Aprender', aprender)
                    .replace(':Personas', personas)
                    .replace(':Rango', fecha2),
                method: 'GET',
                success: function(response) {
                    console.log('res', response);
                    $('#succesUsers' + id_proyecto).text(response)
                    $('#succesUsers' + id_proyecto).show();
                    setTimeout(function() {
                        $('#succesUsers' + id_proyecto).hide();
                    }, 10000);


                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        function guardarventa(id_pro, inve, cantidad1, porcentaje, ganancias) {
            var primer = $('#status-select').val();
            console.log('rang->>>', primer);
            var fechas = primer.split("&");
            var fecha1 = fechas[0];
            var fecha2 = fechas[1];
            console.log('rango2->', fecha2)
            $.ajax({
                url: "{{ route('reportesemanalventas.proyecto', ['proyectoId' => ':idProyecto', 'inver' => ':Inver', 'venta' => ':Venta', 'porcentaje' => ':Porcentaje', 'ganancia' => ':Ganancias', 'rango' => ':Rango']) }}"
                    .replace(':idProyecto', id_pro)
                    .replace(':Inver', inve)
                    .replace(':Venta', cantidad1)
                    .replace(':Porcentaje', porcentaje)
                    .replace(':Ganancias', ganancias)
                    .replace(':Rango', fecha2),
                method: 'GET',
                success: function(response) {
                    console.log('res', response);
                    $('#succesVenta' + id_pro).text(response)
                    $('#succesVenta' + id_pro).show();
                    setTimeout(function() {
                        $('#succesVenta' + id_pro).hide();
                    }, 10000);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        function guardaranimo(id_pro, animo, metricaUPD, obstaculoUPD) {
            var primer = $('#status-select').val();
            console.log('rang->>>', primer);
            var fechas = primer.split("&");
            var fecha1 = fechas[0];
            var fecha2 = fechas[1];
            var selectedValue = $('input[name="mood"]:checked').val();
            $.ajax({
                url: "{{ route('reporteAnimo.proyecto', ['proyectoId' => ':idProyecto', 'animo' => ':Animo', 'metrica' => ':Metrica', 'obstaculo' => ':Obstaculo', 'rango' => ':Rango']) }}"
                    .replace(':idProyecto', id_pro)
                    .replace(':Animo', selectedValue)
                    .replace(':Metrica', metricaUPD)
                    .replace(':Obstaculo', obstaculoUPD)
                    .replace(':Rango', fecha2),
                method: 'GET',
                success: function(response) {
                    console.log('res', response);
                    $('#succesAnimos' + id_pro).text(response)
                    $('#succesAnimos' + id_pro).show();
                    setTimeout(function() {
                        $('#succesAnimos' + id_pro).hide();
                    }, 10000);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        function guardarobjetivo(id_pro, objetivo, integrante) {
            var primer = $('#status-select').val();
            console.log('rang->>>', primer);
            var fechas = primer.split("&");
            var fecha1 = fechas[0];
            var fecha2 = fechas[1];
            var tablaBodymodal = document.getElementById('tablaObjmoodal');

            $.ajax({
                url: "{{ route('reportesemanalobjetivo.proyecto', ['proyectoId' => ':idProyecto', 'objetivo' => ':Objetivo', 'integrante' => ':Integrante', 'rango' => ':Rango']) }}"
                    .replace(':idProyecto', id_pro)
                    .replace(':Objetivo', objetivo)
                    .replace(':Integrante', integrante)
                    .replace(':Rango', fecha2),
                method: 'GET',
                success: function(response) {
                    console.log('Resp', response)
                    var nameValue = response[0].name;

                    var fila2 = document.createElement('tr');
                    var celdaNombre2 = document.createElement('td');
                    celdaNombre2.innerHTML = '<h5 class="m-0 font-weight-normal">' + nameValue +
                        '</h5>';
                    fila2.appendChild(celdaNombre2)


                    var celdaObjetivo = document.createElement('td');
                    celdaObjetivo.textContent = objetivo;
                    fila2.appendChild(celdaObjetivo);

                    tablaBodymodal.appendChild(fila2)
                    $('select[name="usuario_id' + id_pro + '"').val('a');
                    $("#objetivo" + id_pro).val('');

                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        // Evento de click para los botones
        $('#btn-semana').on('click', function() {
            obtenerDatosGrafica('semana');
            obtenerDatosGraficaventas('semana');
            obtenerDatosGraficaInv('semana');
        });

        $('#btn-mes').on('click', function() {
            obtenerDatosGrafica('mes');
            obtenerDatosGraficaventas('mes');
            obtenerDatosGraficaInv('mes');
        });

        $('#btn-anio').on('click', function() {
            obtenerDatosGrafica('anio');
            obtenerDatosGraficaventas('anio');
            obtenerDatosGraficaInv('anio')
        });

        // Obtener los datos de la gráfica al cargar la página
        $(document).ready(function() {
            obtenerDatosconlosrangos();
            // formElement.submit(); 
            obtenerDatosGrafica('semana');
            obtenerDatosGraficaventas('semana');
            obtenerDatosGraficaInv('semana');
            obtenerDatosGanancias();
            $("#usuarios-tab").addClass("active");
            document.getElementById('reloadLink').onclick = function() {
                location.reload();
            };

            // Obtener el atributo href del elemento <a> activo
            var activeHref = $('a.nav-link.active').attr('href');

            // Aplicar expresión regular para obtener los dígitos después de 'tab'
            var matches = activeHref.match(/=tab(\d+)/);

            // Verificar si se encontraron coincidencias y obtener los dígitos
            if (matches && matches.length > 1) {
                var id_pro = matches[1];
                console.log(id_pro)
            }
            var tablaBody = document.getElementById('tablaObjBody');
            while (tablaBody.firstChild) {
                tablaBody.removeChild(tablaBody.firstChild);
            }
            $('#btnusrs' + id_pro).on('click', function() {
                console.log('aqui');
                $("#account-" + id_pro).removeClass('active');
                $("#profile-tab-" + id_pro).addClass("active");
                $("#tab-users" + id_pro).removeClass('active');
                $("#tab-ventas" + id_pro).addClass("active");
                $("#barprogres" + id_pro).css("width", "66.66%");


            });
            $('#btnperfs' + id_pro).on('click', function() {
                console.log('aqui2');
                $("#profile-tab-" + id_pro).removeClass('active');
                $("#finish-" + id_pro).addClass("active");
                $("#tab-ventas" + id_pro).removeClass('active');
                $("#tab-animo" + id_pro).addClass("active");
                $("#barprogres" + id_pro).css("width", "100%");


            });
            $('#btnaperfs' + id_pro).on('click', function() {
                console.log('aqui3');
                $("#profile-tab-" + id_pro).removeClass('active');
                $("#account-" + id_pro).addClass("active");
                $("#tab-ventas" + id_pro).removeClass('active');
                $("#tab-users" + id_pro).addClass("active");
                $("#barprogres" + id_pro).css("width", "33.33%");


            });
            $('#btnanimos' + id_pro).on('click', function() {
                console.log('aqui4');
                $("#finish-" + id_pro).removeClass('active');
                $("#profile-tab-" + id_pro).addClass("active");
                $("#tab-animo" + id_pro).removeClass('active');
                $("#tab-ventas" + id_pro).addClass("active");
                $("#barprogres" + id_pro).css("width", "66.66%");


            });
            $('#btnInte' + id_pro).on('click', function() {
                console.log('Entro al boton')
                var correo = $('#inputcorreo' + id_pro).val();
                if (!correo) {
                    console.log('Vacio');
                    $('#alertsInteg' + id_pro).text('Ingresa un correo valido')
                } else {
                    console.log('Si tiene datos')
                    obtebertalaintegrantes(correo, id_pro);
                }
            });
            $('#btnUsuarioG' + id_pro).on('click', function() {
                console.log('btnguardarusur')
                var personas = $('#personas' + id_pro).val();
                var aprender = $('#aprender' + id_pro).val();
                if (personas != '' && aprender != '') {
                    console.log('Estan lleno lo dos')
                    guardaruser(id_pro, aprender, personas)
                } else {
                    console.log('llenar todo los campos')
                }


            });

            $('#btnVentaG' + id_pro).on('click', function() {
                console.log('btnguardarventa')
                var inve = $("#inve" + id_pro).val();
                var cantidad1 = $("#cantidad1" + id_pro).val();
                var porcentaje = $("#porcentaje" + id_pro).val();
                var ganancias = $("#ganancias" + id_pro).val();
                if (cantidad1 != 0 && porcentaje != 0 && ganancias != 0) {
                    console.log('Estan lleno lo dos')
                    guardarventa(id_pro, inve, cantidad1, porcentaje, ganancias);
                } else {
                    console.log('llenar todo los campos')
                }


            });
            $('#btnMoralG' + id_pro).on('click', function() {
                console.log('btnguardarmoral')
                var moodSelector = $("input[name='mood']");

                moodSelector.on("change", handleMoodChange);
                var selectedMood = '';

                function handleMoodChange() {
                    selectedMood = moodSelector.filter(":checked").val();
                    console.log("El estado de ánimo seleccionado es:", selectedMood);

                }
                var animo = selectedMood;
                console.log("El estado:", animo);
                var metricaUPD = $("#metricaUPD" + id_pro).val();
                var obstaculoUPD = $("#obstaculoUPD" + id_pro).val();
                if (metricaUPD != 0 && obstaculoUPD != 0) {
                    console.log('Estan lleno lo 3')
                    guardaranimo(id_pro, animo, metricaUPD, obstaculoUPD);
                } else {
                    console.log('llenar todo los campos')
                }


            });

            $('#btnObjetivoG' + id_pro).on('click', function() {
                console.log('btnguardarObjetivo')
                var objetivo = $("#objetivo" + id_pro).val();
                var integrante = $('select[name="usuario_id' + id_pro + '"').val();
                if (objetivo != '' && integrante != '') {
                    console.log('Estan lleno lo dos', integrante)
                    guardarobjetivo(id_pro, objetivo, integrante);
                } else {
                    console.log('llenar todo los campos')
                }


            });
            var typingTimer; // Temporizador
            var doneTypingInterval = 500; // Intervalo de tiempo en milisegundos

            $('#porcentaje' + id_pro).on('input', function() {
                clearTimeout(typingTimer); // Reiniciar el temporizador

                typingTimer = setTimeout(function() {
                    // Acciones a realizar después de que se haya terminado de escribir
                    console.log("Terminó de escribir");
                    var cantidad = $('#cantidad1' + id_pro).val();
                    var porcentaje = $('#porcentaje' + id_pro).val();
                    if (cantidad) {
                        var ganacia = (porcentaje / 100) * cantidad;
                        console.log('gana', ganacia)
                        $('#ganancias' + id_pro).val(ganacia);
                    } else {
                        alert("Debe de ingresar primero la cantidad")
                    }
                    // Llamar a la función correspondiente o ejecutar cualquier otra acción necesaria
                }, doneTypingInterval);
            });
            var selectedElement = $("i#mood.selected");

            // Verificar si hay alguna etiqueta seleccionada
            if (selectedElement.length > 0) {
                // Acceder al name del elemento seleccionado
                var selectedName = selectedElement.attr("name");
                console.log("La etiqueta <i> con id 'mood' seleccionada tiene el name:", selectedName);
            } else {
                console.log("Ninguna etiqueta <i> con id 'mood' está seleccionada.");
            }
        });
    </script>

@endsection
