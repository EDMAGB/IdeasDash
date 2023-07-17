@extends('plantilla.layouts')
@section('titulo', 'Dashboard')
@section('contenido')
    @php
        $semana = date('W');
        $anio = date('Y');
        
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
                    <div class="card text-center">
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
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <h2>{{ $pro->nombre_proyecto }}</h2>
                                        </div>
                                        <div class="col-3">
                                            <h2>{{ $semana }}/{{ $anio }}</h2>
                                        </div>
                                        <div class="col-3">
                                            <button type="button" class="btn btn-warning btn-rounded waves-effect waves-light"
                                                data-toggle="modal"
                                                data-target="#con-close-modal-{{ $pro->id_proyecto }}">Update Semanal</button>
                                            <button type="button" class="btn btn-danger btn-rounded waves-effect waves-light"
                                                data-toggle="modal" data-target="#equipo-modal-{{ $pro->id_proyecto }}"><i
                                                    class="icon-plus"></i> Integrantes</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <div class="card-box">
                                                <h4 class="header-title mb-3">Objetivos de la Semana</h4>
                                                <div class="table-responsive">
                                                    <table class="table table-borderless table-hover table-centered m-0">

                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Integrante</th>
                                                                <th>Objetivo</th>
                                                                <th>Accion</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($tablaobj as $obj)
                                                            @if ($obj->id_proyecto == $pro->id_proyecto)
                                                                <tr>
                                                                    <td>
                                                                        <h5 class="m-0 font-weight-normal">{{$obj->name}}</h5>
                                                                    </td>
                                                                    <td>{{ $obj->objetivo }}</td>
                                                                    <td>
                                                                        <form
                                                                            action="{{ route('cambiarstatus', ['idObjt' => $obj->id_obj]) }}"
                                                                            method="GET"
                                                                            onsubmit="return confirm('¿Estás seguro de que concluiste este objetivo?')">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                class="btn btn-success btn-rounded waves-effect waves-light">
                                                                                <i class="mdi mdi-check-outline"></i>
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach 
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
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
                                                <div id="user-potenciales{{ $pro->id_proyecto }}" class="apex-charts"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
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

                                                <div class="mt-5">
                                                    <div class="card-box" dir="ltr">
                                                        <h4 class="header-title mb-1">Ventas</h4>
                                                        <div id="ventas-grafica{{ $pro->id_proyecto }}" class="apex-charts">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- end card-box-->
                                        </div>
                                    </div>
                                </div>
                                @if (Session::has('registro_no_existe'))
                                    <div class="alert alert-warning">
                                        {{ Session::get('registro_no_existe') }}
                                    </div>
                                @endif
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
                                                        <form
                                                            action="{{ route('masintegrante.proyecto', ['proyectoId' => $pro->id_proyecto]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="form-group mx-sm-3">
                                                                <input type="email" class="form-control" id="email"
                                                                    name="email" placeholder="Correo">
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-primary waves-effect waves-light"><i
                                                                    class="icon-plus"></i>Agregar</button>
                                                        </form>
                                                    </div> <!-- end card-box-->
                                                </div> <!-- end col-->
                                            </div>
                                            <hr>
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
                                                        <tbody>
                                                            @foreach ($restab as $integ)
                                                                @if ($integ->id_proyecto == $pro->id_proyecto)
                                                                    <tr>
                                                                        <th scope="row">{{ $integ->id_usuario }} </th>
                                                                        <td>{{ $integ->name }}</td>
                                                                        <td>{{ $integ->email }}</td>
                                                                        <td>{{ $integ->puesto }}</td>
                                                                        <td>
                                                                            <form
                                                                                action="{{ route('eliminarintegrantes', ['idUsuario' => $integ->id_usuario, 'idProyecto' => $integ->id_proyecto]) }}"
                                                                                method="POST"
                                                                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-danger btn-rounded waves-effect waves-light">
                                                                                    <i class="fe-trash-2"></i>
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div> <!-- end table-responsive-->

                                            </div> <!-- end card-box -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Update Semanal Modal -->
                            <div class="modal fade " id="con-close-modal-{{ $pro->id_proyecto }}"
                                tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                            <div id="progressbarwizard">
                                                                <ul
                                                                    class="nav nav-pills bg-light nav-justified form-wizard-header mb-3">
                                                                    <li class="nav-item">
                                                                        <a href="#account-{{ $pro->id_proyecto }}"
                                                                            data-toggle="tab"
                                                                            class="nav-link rounded-0 pt-2 pb-2">
                                                                            <i class="mdi mdi-account-circle mr-1"></i>
                                                                            <span class="d-none d-sm-inline">Usuarios</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#profile-tab-{{ $pro->id_proyecto }}"
                                                                            data-toggle="tab"
                                                                            class="nav-link rounded-0 pt-2 pb-2">
                                                                            <i class="mdi mdi-face-profile mr-1"></i>
                                                                            <span class="d-none d-sm-inline">Traccion y
                                                                                Ventas</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#finish-{{ $pro->id_proyecto }}"
                                                                            data-toggle="tab"
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
                                                                        <div
                                                                            class="bar progress-bar progress-bar-striped progress-bar-animated bg-success">
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="tab-pane"id="account-{{ $pro->id_proyecto }}">
                                                                        <form
                                                                            action="{{ route('reportesemanal.proyecto', ['proyectoId' => $pro->id_proyecto]) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @if (Session::has('success'))
                                                                                <div class="alert alert-success">
                                                                                    {{ Session::get('success') }}</div>
                                                                            @endif
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="form-group row mb-3">
                                                                                        <label class="col-md-8 col-form-label"
                                                                                            for="personas">¿Usuarios
                                                                                            potenciales
                                                                                            con los que
                                                                                            hablaste en la última
                                                                                            semana?</label>
                                                                                        <div class="col-md-4">
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="personas"
                                                                                                name="personas">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="aprender">¿Que
                                                                                            aprendiste de
                                                                                            ellos?
                                                                                            :</label>
                                                                                        <textarea id="aprender" class="form-control" name="aprender" data-parsley-trigger="keyup"
                                                                                            data-parsley-minlength="20" data-parsley-maxlength="10000"
                                                                                            data-parsley-minlength-message="vamos! Describe mas tu proyecto." data-parsley-validation-threshold="100">
                                                                                    </textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <button type="submit"
                                                                                    class="btn btn-primary waves-effect waves-light"><i
                                                                                        class="icon-plus"></i>Agregar</button>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-10"></div>
                                                                                <div class="col-2">
                                                                                    <ul class="list-inline mb-0 wizard">
                                                                                        <li
                                                                                            class="next list-inline-item float-right">
                                                                                            <a href="javascript: void(0);"
                                                                                                class="btn btn-secondary">Siguiente</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="tab-pane"
                                                                        id="profile-tab-{{ $pro->id_proyecto }}">
                                                                        <form
                                                                            action="{{ route('reportesemanalventas.proyecto', ['proyectoId' => $pro->id_proyecto]) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @if (Session::has('successventas'))
                                                                                <div class="alert alert-success">
                                                                                    {{ Session::get('successventas') }}</div>
                                                                            @endif
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="form-group row mb-3">
                                                                                        <label class="col-md-3 col-form-label"
                                                                                            for="namecliente">
                                                                                            Cliente</label>
                                                                                        <div class="col-md-9">
                                                                                            <input type="text"
                                                                                                id="namecliente"
                                                                                                name="namecliente"
                                                                                                class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row mb-3">
                                                                                        <label class="col-md-3 col-form-label"
                                                                                            for="cantidad1{{ $pro->id_proyecto }}">
                                                                                            Cantidad 1</label>
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
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary waves-effect waves-light"><i
                                                                                            class="icon-plus"></i>Agregar</button>
                                                                                </div> <!-- end col -->
                                                                            </div> <!-- end row -->
                                                                        </form>
                                                                        <hr>
                                                                        <div class="card-box">
                                                                            <h4 class="header-title">Ventas</h4>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover mb-0">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Nombre Cliente</th>
                                                                                            <th>Cantidad 1</th>
                                                                                            <th>Porcentaje</th>
                                                                                            <th>Ganacia</th>
                                                                                            <th>Accion</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach ($ventas as $ventaarr)
                                                                                            @if ($ventaarr->id_proyecto == $pro->id_proyecto)
                                                                                                <tr>
                                                                                                    <th scope="row">
                                                                                                        {{ $ventaarr->cliente }}
                                                                                                    </th>
                                                                                                    <td>${{ $ventaarr->cantidad }}
                                                                                                    </td>
                                                                                                    <td>{{ $ventaarr->porcentaje }}%
                                                                                                    </td>
                                                                                                    <td>${{ $ventaarr->ganancia }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <form
                                                                                                            action="{{ route('eliminarventa', ['idVenta' => $ventaarr->id]) }}"
                                                                                                            method="POST"
                                                                                                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                                                                                            @csrf
                                                                                                            @method('DELETE')
                                                                                                            <button
                                                                                                                type="submit"
                                                                                                                class="btn btn-danger btn-rounded waves-effect waves-light">
                                                                                                                <i
                                                                                                                    class="fe-trash-2"></i>
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div> <!-- end table-responsive-->

                                                                        </div> <!-- end card-box -->
                                                                    </div>
                                                                    <div class="tab-pane"
                                                                        id="finish-{{ $pro->id_proyecto }}">
                                                                        <form
                                                                            action="{{ route('reportesemanalanimo.proyecto', ['proyectoId' => $pro->id_proyecto]) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @if (Session::has('successventas'))
                                                                                <div class="alert alert-success">
                                                                                    {{ Session::get('successventas') }}</div>
                                                                            @endif
                                                                            <div class="row">

                                                                                <div class="col-12">
                                                                                    <div class="mood-selector">
                                                                                        ¿Cuál es tu estado de animo?:
                                                                                        <div id="selector" name="animo">
                                                                                            <i id="mood" name="mood1"
                                                                                                class="far fa-sad-tear"></i>
                                                                                            <i id="mood" name="mood2"
                                                                                                class="far fa-frown"></i>
                                                                                            <i id="mood" name="mood3"
                                                                                                class="far fa-meh"></i>
                                                                                            <i id="mood" name="mood4"
                                                                                                class="far fa-smile"></i>
                                                                                            <i id="mood" name="mood5"
                                                                                                class="far fa-laugh"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> <!-- end col -->
                                                                            </div> <!-- end row -->
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <label for="metrica">¿Qué mejoró más
                                                                                            la métrica principal?
                                                                                            :</label>
                                                                                        <textarea id="metrica" class="form-control" name="metrica" data-parsley-trigger="keyup"
                                                                                            data-parsley-minlength="20" data-parsley-maxlength="10000"
                                                                                            data-parsley-minlength-message="vamos! Describe mas tu proyecto." data-parsley-validation-threshold="100">
                                                                                    </textarea>
                                                                                    </div>
                                                                                </div> <!-- end col -->
                                                                            </div> <!-- end row -->
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <label for="obstaculo">¿Cuál ha sido el
                                                                                            mayor obstáculo?
                                                                                            :</label>
                                                                                        <textarea id="obstaculo" class="form-control" name="obstaculo" data-parsley-trigger="keyup"
                                                                                            data-parsley-minlength="20" data-parsley-maxlength="10000"
                                                                                            data-parsley-minlength-message="vamos! Describe mas tu proyecto." data-parsley-validation-threshold="100">
                                                                                    </textarea>
                                                                                    </div>
                                                                                </div> <!-- end col -->
                                                                            </div> <!-- end row -->
                                                                            <button type="submit"
                                                                                class="btn btn-primary waves-effect waves-light"><i
                                                                                    class="icon-plus"></i>Agregar</button>
                                                                        </form>
                                                                        <hr>
                                                                        <form
                                                                            action="{{ route('reportesemanalobjetivo.proyecto', ['proyectoId' => $pro->id_proyecto]) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <div class="row">
                                                                                <div class="col-8">
                                                                                    <div class="form-group">
                                                                                        <label for="objetivo">¿Cuáles son sus
                                                                                            principales objetivos para la
                                                                                            próxima semana?
                                                                                            :</label>
                                                                                        <textarea id="objetivo" class="form-control" name="objetivo" data-parsley-trigger="keyup"
                                                                                            data-parsley-minlength="20" data-parsley-maxlength="10000"
                                                                                            data-parsley-minlength-message="vamos! Describe mas tu proyecto." data-parsley-validation-threshold="100">
                                                                                    </textarea>
                                                                                    </div>
                                                                                </div> <!-- end col -->
                                                                                <div class="col-4">
                                                                                    <select name="usuario_id">
                                                                                        <option value="">Selecciona un
                                                                                            colaborador</option>
                                                                                        @foreach ($restab as $integ)
                                                                                            @if ($integ->id_proyecto == $pro->id_proyecto)
                                                                                                <option
                                                                                                    value="{{ $integ->id_usuario }}">
                                                                                                    {{ $integ->name }}
                                                                                                </option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>


                                                                            </div>
                                                                            <button type="submit"
                                                                                class="btn btn-primary waves-effect waves-light"><i
                                                                                    class="icon-plus"></i>Agregar</button>
                                                                        </form>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <h2>{{ $pro->nombre_proyecto }}</h2>
                                </div>
                                <div class="col-3">
                                    <h2>{{ $semana }}/{{ $anio }}</h2>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-warning btn-rounded waves-effect waves-light"
                                        data-toggle="modal" data-target="#con-close-modal">Update Semanal</button>
                                    <button type="button" class="btn btn-danger btn-rounded waves-effect waves-light"
                                        data-toggle="modal" data-target="#equipo-modal"><i class="icon-plus"></i>
                                        Integrantes</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="card-box">
                                        <h4 class="header-title mb-3">Objetivos de la Semana</h4>
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-hover table-centered m-0">

                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Integrante</th>
                                                        <th>Objetivo</th>
                                                        <th>Accion</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h5 class="m-0 font-weight-normal">Edna Garcia</h5>
                                                            <p class="mb-0 text-muted"><small>Programadora</small></p>
                                                        </td>

                                                        <td>
                                                            Version 2 Pagina web
                                                        </td>

                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="btn btn-success btn-rounded waves-effect waves-light"><i
                                                                    class="mdi mdi-check-outline"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5 class="m-0 font-weight-normal">Monst</h5>
                                                            <p class="mb-0 text-muted"><small>Diseñadora</small></p>
                                                        </td>

                                                        <td>
                                                            Version 2 Diseño pagina
                                                        </td>

                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="btn btn-success btn-rounded waves-effect waves-light"><i
                                                                    class="mdi mdi-check-outline"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>

                                                        <td>
                                                            <h5 class="m-0 font-weight-normal">Edna Garcia</h5>
                                                            <p class="mb-0 text-muted"><small>Programadora</small></p>
                                                        </td>

                                                        <td>
                                                            Version 2 Pagina web
                                                        </td>

                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="btn btn-success btn-rounded waves-effect waves-light"><i
                                                                    class="mdi mdi-check-outline"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>

                                                        <td>
                                                            <h5 class="m-0 font-weight-normal">Monst</h5>
                                                            <p class="mb-0 text-muted"><small>Diseñadora</small></p>
                                                        </td>

                                                        <td>
                                                            Version 2 Diseño pagina
                                                        </td>

                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="btn btn-success btn-rounded waves-effect waves-light"><i
                                                                    class="mdi mdi-check-outline"></i></a>
                                                        </td>
                                                    </tr>



                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                                <div class="col-xl-4">
                                    <div class="card cta-box bg-info text-white">
                                        <div class="card-body">
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <div class="avatar-md bg-soft-light rounded-circle text-center mb-2">
                                                        <i class="mdi mdi-store font-22 avatar-title text-light"></i>
                                                    </div>
                                                    <h3 class="m-0 font-weight-normal text-white sp-line-1 cta-box-title">
                                                        ESTADO DE ANIMO :)</h3>
                                                    <p class="text-light mt-2 sp-line-2">FRASE DEL DIA</p>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- end card-body -->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card-box">
                                            <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip"
                                                data-placement="bottom" title="" data-original-title="More Info"></i>
                                            <h4 class="mt-0 font-16">Wallet Balance</h4>
                                            <h2 class="text-primary my-4 text-center">$<span
                                                    data-plugin="counterup">31,570</span></h2>
                                            <div class="row mb-4">
                                                <div class="col-6">
                                                    <p class="text-muted mb-1">This Month</p>
                                                    <h3 class="mt-0 font-20 text-truncate">$120,254 <small
                                                            class="badge badge-light-success font-13">+15%</small></h3>
                                                </div>

                                                <div class="col-6">
                                                    <p class="text-muted mb-1">Last Month</p>
                                                    <h3 class="mt-0 font-20 text-truncate">$98,741 <small
                                                            class="badge badge-light-danger font-13">-5%</small></h3>
                                                </div>
                                            </div>

                                            <div class="mt-5">
                                                <span data-plugin="peity-line" data-fill="#56c2d6" data-stroke="#4297a6"
                                                    data-width="100%" data-height="50">3,5,2,9,7,2,5,3,9,6,5,9,7</span>
                                            </div>

                                        </div> <!-- end card-box-->
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="card-box">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="avatar-sm bg-light rounded">
                                                        <i class="fe-shopping-cart avatar-title font-22 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-right">
                                                        <h3 class="text-dark my-1"><span data-plugin="counterup">1576</span>
                                                        </h3>
                                                        <p class="text-muted mb-1 text-truncate">Ventas de Julio</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <h6 class="text-uppercase">Objetivo<span class="float-right">49%</span>
                                                </h6>
                                                <div class="progress progress-sm m-0">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        aria-valuenow="49" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: 49%">
                                                        <span class="sr-only">49% Complete</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end card-box-->


                                    </div>
                                </div>

                            </div>
                        </div> --}}
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
                                            <input type="text" class="form-control" name="proyectoname" id="proyectoname"
                                                required="">
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
                                        <div class="form-group">
                                            <label for="div">División participantes MVS:</label>
                                            <label for="div" class="sr-only">Buscar</label>
                                            <input type="search" class="form-control" id="inputPassword2"
                                                placeholder="Search...">
                                            <button type="button" class="btn btn-success waves-effect waves-light mr-1"><i
                                                    class="la la-search"></i></button>
                                        </div>
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
        // Obtener los datos de la gráfica
        function obtenerDatosGrafica(opcion) {
            // Obtener el atributo href del elemento <a> activo
            var activeHref = $('a.nav-link.active').attr('href');

            // Aplicar expresión regular para obtener los dígitos después de 'tab'
            var matches = activeHref.match(/=tab(\d+)/);

            // Verificar si se encontraron coincidencias y obtener los dígitos
            if (matches && matches.length > 1) {
                var digitos = matches[1];
                console.log(digitos); // Imprimir los dígitos en la consola

                $.ajax({
                    url: "{{ route('datos.grafica', ['opcion' => ':opcion', 'idProyecto' => ':idProyecto']) }}"
                        .replace(':opcion', opcion).replace(':idProyecto', digitos),
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
                            colors: ['#0000FF', '#FFFF00', '#808080', '#00FF00']
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
                                type: 'bar',
                                height: 350
                            },
                            series: [{
                                data: datos
                            }],
                            xaxis: {
                                type: 'category',
                            },
                            colors: ['#0000FF', '#FFFF00', '#808080', '#00FF00']
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

        function obtenerDatosGanancias() {
            // Obtener el atributo href del elemento <a> activo
            var activeHref = $('a.nav-link.active').attr('href');

            // Aplicar expresión regular para obtener los dígitos después de 'tab'
            var matches = activeHref.match(/=tab(\d+)/);

            // Verificar si se encontraron coincidencias y obtener los dígitos
            if (matches && matches.length > 1) {
                var digitos = matches[1];
                console.log(digitos); // Imprimir los dígitos en la consola

                $.ajax({
                    url: "{{ route('datos.ganancias', ['idProyecto' => ':idProyecto']) }}"
                        .replace(':idProyecto', digitos),
                    method: 'GET',
                    success: function(response) {
                        // Convertir los datos recibidos en un formato adecuado para ApexCharts
                        var datos = response.map(function(item) {
                            console.log('holaganancias', item);
                            console.log(item.suma_mesactual);
                            console.log(item.suma_mesanterior);
                            console.log(item.suma_total);
                            $('#gananciastext' + digitos).text(item.suma_total);
                            $('#gananciames' + digitos).text('$' + item.suma_mesactual);
                            $('#gananciasmesan' + digitos).text('$' + item.suma_mesanterior);
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

        // Evento de click para los botones
        $('#btn-semana').on('click', function() {
            obtenerDatosGrafica('semana');
            obtenerDatosGraficaventas('semana');
        });

        $('#btn-mes').on('click', function() {
            obtenerDatosGrafica('mes');
            obtenerDatosGraficaventas('mes');
        });

        $('#btn-anio').on('click', function() {
            obtenerDatosGrafica('anio');
            obtenerDatosGraficaventas('anio');
        });



        // Obtener los datos de la gráfica al cargar la página
        $(document).ready(function() {
            obtenerDatosGrafica('semana');
            obtenerDatosGraficaventas('semana');
            obtenerDatosGanancias();
            // Mood Selector
            const moodSelector = document.querySelectorAll("#mood")
            moodSelector.forEach(mood => {
                mood.addEventListener("click", handleMoodClick)
            })


            function handleMoodClick() {
                moodSelector.forEach(node => {
                    node.classList.remove("fas")
                    node.classList.add("far")
                })
                // Modify the mood color Variable
                // Get clicked mood color
                let color = getComputedStyle(this);
                console.log(color.color) // rgb(0,128,0)
                document.documentElement.style.setProperty('--moodColor', color.color)
                // remove class far 
                this.classList.remove("far")
                this.classList.add("fas")
                // <i class="fas fa-laugh"></i>


            }

            //Change of Color Handler
            function handleClickOnDay(event) {
                let color = getComputedStyle(document.documentElement).getPropertyValue('--moodColor');

                if (color == " #bbb") {
                    console.log("igual")
                } else {
                    event.target.setAttribute('style', `background-color: ${color}`)
                    //event.target.classList.toggle("selected")
                }



            }

            // Obtener el atributo href del elemento <a> activo
            var activeHref = $('a.nav-link.active').attr('href');

            // Aplicar expresión regular para obtener los dígitos después de 'tab'
            var matches = activeHref.match(/=tab(\d+)/);

            // Verificar si se encontraron coincidencias y obtener los dígitos
            if (matches && matches.length > 1) {
                var id_pro = matches[1];
                console.log(id_pro)
            }
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
        });
    </script>
@endsection
