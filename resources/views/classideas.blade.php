@extends('plantilla.layouts')
@section('titulo', 'Class')
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
                <h2>Semana 1</h2>
            </div>
            <div class="col-3"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <iframe width="100%" height="500px" src="https://www.youtube-nocookie.com/embed/e0ynchA_sBA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                   
                    <div class="card-body">
                        <h5 class="card-title">Actividad 1</h5>
                        <p class="card-text">This is a wider card with supporting text below as a
                            natural lead-in to additional content. This content is a little bit
                            longer.</p>
                        <p class="card-text">
                           
                            <button type="button"
                            id=""
                            class="bbtn btn-block btn-rounded btn-lg btn-primary waves-effect waves-light">Terminado</button>
                        </p>
                    </div>
                </div> <!-- end card-box-->
            </div> <!-- end col -->         
        </div>
        <div class="row">
            <div class="col-6">
                <h2>Semana 2</h2>
            </div>
            <div class="col-3"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <iframe width="100%" height="500px" src="https://www.youtube-nocookie.com/embed/e0ynchA_sBA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                   
                    <div class="card-body">
                        <h5 class="card-title">Actividad 2</h5>
                        <p class="card-text">This is a wider card with supporting text below as a
                            natural lead-in to additional content. This content is a little bit
                            longer.</p>
                        <p class="card-text">
                            <button type="button"
                            id=""
                            class="bbtn btn-block btn-rounded btn-lg btn-primary waves-effect waves-light">Terminado</button>
                        </p>
                    </div>
                </div> <!-- end card-box-->
            </div> <!-- end col -->         
        </div>   
            @endauth
        @endsection
