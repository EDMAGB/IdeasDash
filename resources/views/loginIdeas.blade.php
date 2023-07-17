@extends('plantilla.inicioideas')
@section('titulo', 'Dashboard')
@section('contenido')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <a href="index.html">
                                    <span><img src="assets/images/logo-dark.png" alt="" height="18"></span>
                                </a>
                                <p class="text-muted mb-4 mt-3">Ingrese sus Credenciales</p>
                            </div>
                            <x-validation-errors class="mb-4" />
                            @if (session('status'))
                                <div class="mb-4 font-medium text-sm text-green-600">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="email">Correo</label>
                                    <input class="form-control" type="email" id="email" name="email"
                                        :value="old('email')" required autofocus autocomplete="username">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Contraseña</label>
                                    <input class="form-control" type="password" required="" id="password"
                                        name="password" required autocomplete="current-password">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="remember_me" class="flex items-center">
                                        <x-checkbox id="remember_me" name="remember" />
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Recuerdame') }}</span>
                                    </label>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-danger btn-block" type="submit">Ingresar</button>
                                </div>

                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p> <a href="{{ route('password.request') }}" class="text-muted ml-1">¿Olvidaste tu
                                    contraseña?</a></p>
                            <p class="text-muted"><a href="{{ route('register') }}" class="text-muted ml-1"> Registrate </a>
                            </p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
@endsection
