<?php

use App\Http\Controllers\ClassIdeasController;
use App\Http\Controllers\IndexIdeasController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('loginIdeas');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

Route::get('/indexideas', IndexIdeasController::class)-> name('indexideas');
Route::get('/classideas', ClassIdeasController::class)-> name('classideas');
Route:: post('nuevoproyecto',[IndexIdeasController::class , 'proyectoadd'])->name('nuevoproyecto.proyecto');
 Route:: get('reportesemanal/{proyectoId}/{aprender}/{personas}/{rango}',[IndexIdeasController::class , 'updateSemanal'])->name('reportesemanal.proyecto');
 Route:: get('reportesemanalventas/{proyectoId}/{inver}/{venta}/{porcentaje}/{ganancia}/{rango}',[IndexIdeasController::class , 'updateVentas'])->name('reportesemanalventas.proyecto');

 Route:: get('reporteanimo/{proyectoId}/{animo}/{metrica}/{obstaculo}/{rango}',[IndexIdeasController::class , 'updatemAnimo'])->name('reporteAnimo.proyecto');

 Route:: post('rangos/{proyectoId}',[IndexIdeasController::class , 'RangosT'])->name('rangos.proyecto');
 Route:: get('reportesemanalobjetivo/{proyectoId}/{objetivo}/{integrante}/{rango}',[IndexIdeasController::class , 'updateObjetivo'])->name('reportesemanalobjetivo.proyecto');
// Route:: post('masintegrante',[IndexIdeasController::class , 'addIntegrante'])->name('masintegrante.proyecto');
Route::get('masintegrante/{proyectoId}/{correo}', [IndexIdeasController::class, 'addIntegrante'])->name('masintegrante.proyecto');
Route::post('/upload/image', [IndexIdeasController::class, 'uploadImage'])->name('upload.image');
Route::delete('/eliminarintegrantes/{idUsuario}/{idProyecto}', [IndexIdeasController::class, 'eliminarRegistro'])->name('eliminarintegrantes');


Route::delete('/eliminarventa/{idVenta}', [IndexIdeasController::class, 'eliminarVenta'])->name('eliminarventa');
Route::get('/cambiarstatus/{idObjt}', [IndexIdeasController::class, 'CambiarStatus'])->name('cambiarstatus');
Route::get('obtenerDatos/{opcion}/{idProyecto}/{Rango1}/{Rango2}}', [IndexIdeasController::class, 'obtenerDatos'])->name('datos.grafica');
Route::get('obtenerDatosInv/{opcion}/{idProyecto}/{Rango1}/{Rango2}}', [IndexIdeasController::class, 'obtenerDatosInv'])->name('datos.graficaInv');
Route::get('obtenerDatosVentas/{opcion}/{idProyecto}', [IndexIdeasController::class, 'obtenerDatosventas'])->name('datos.graficaventas');
Route::get('obtenerDatosObjetivos/{idProyecto}/{Rango1}/{Rango2}', [IndexIdeasController::class, 'obtenerDatosObjetivos'])->name('datos.objetivos');
Route::get('obtenerDatosGanancias/{idProyecto}/{Rango2}', [IndexIdeasController::class, 'obtenerDatosganancias'])->name('datos.ganancias');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

