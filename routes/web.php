<?php

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
Route:: post('nuevoproyecto',[IndexIdeasController::class , 'proyectoadd'])->name('nuevoproyecto.proyecto');
 Route:: post('reportesemanal/{proyectoId}',[IndexIdeasController::class , 'updateSemanal'])->name('reportesemanal.proyecto');
 Route:: post('reportesemanalventas/{proyectoId}',[IndexIdeasController::class , 'updateVentas'])->name('reportesemanalventas.proyecto');
 Route:: post('reportesemanalanimo/{proyectoId}',[IndexIdeasController::class , 'updateAnimo'])->name('reportesemanalanimo.proyecto');
 Route:: post('reportesemanalobjetivo/{proyectoId}',[IndexIdeasController::class , 'updateObjetivo'])->name('reportesemanalobjetivo.proyecto');
// Route:: post('masintegrante',[IndexIdeasController::class , 'addIntegrante'])->name('masintegrante.proyecto');
Route::post('masintegrante/{proyectoId}', [IndexIdeasController::class, 'addIntegrante'])->name('masintegrante.proyecto');
Route::delete('/eliminarintegrantes/{idUsuario}/{idProyecto}', [IndexIdeasController::class, 'eliminarRegistro'])->name('eliminarintegrantes');

Route::delete('/eliminarventa/{idVenta}', [IndexIdeasController::class, 'eliminarVenta'])->name('eliminarventa');
Route::get('/cambiarstatus/{idObjt}', [IndexIdeasController::class, 'CambiarStatus'])->name('cambiarstatus');
Route::get('obtenerDatos/{opcion}/{idProyecto}', [IndexIdeasController::class, 'obtenerDatos'])->name('datos.grafica');
Route::get('obtenerDatosVentas/{opcion}/{idProyecto}', [IndexIdeasController::class, 'obtenerDatosventas'])->name('datos.graficaventas');
Route::get('obtenerDatosGanancias/{idProyecto}', [IndexIdeasController::class, 'obtenerDatosganancias'])->name('datos.ganancias');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

