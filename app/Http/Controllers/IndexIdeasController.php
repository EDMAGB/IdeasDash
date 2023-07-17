<?php

namespace App\Http\Controllers;

use App\Models\Integrantes_Equipo;
use App\Models\Integrantes_Equipos;
use App\Models\IntegrantesProyecto;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\proyint;
use App\Models\updateanimo;
use App\Models\updateobjetivos;
use App\Models\updateuser;
use App\Models\updateventas;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;




class IndexIdeasController extends Controller
{
    public function __invoke(Request $request)
    {

        $fechaActual = date('Y-m-d');
        $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));
        $userId = Auth::id();
        $listproyect = proyint::where('id_usuario', $userId)->pluck('id_proyecto');
        $proyectos = Proyecto::whereIn('id_proyecto', $listproyect)->get();
        $Integrantes =  proyint::whereIn('id_proyecto', $listproyect)->pluck('id_usuario');
        $usuariosint = User::whereIn('id', $Integrantes);
        $restab = proyint::join("users", "users.id", "=", "proyints.id_usuario")->select("id_proyecto", "puesto", "name", "email", "id_usuario")->get();
        $ventas = updateventas::where('rango1', $rangoInicioSemana)->where('rango2', $rangoFinSemana)->get();
        // return $proyectos;
        $activeTab = $request->query('tab', 'tab1');

        $tablaobj = User::from('users AS u')
        ->join('updateobjetivos AS d', 'd.usuario', '=', 'u.id')
        ->select('u.id', 'u.name', 'd.objetivo', 'd.status', 'd.id_proyecto','d.id as id_obj')
        ->where('d.status',1)
        ->get();
         // return $tablaobj ;
        return view('indexideas', compact('proyectos', 'activeTab', 'restab', 'ventas','tablaobj'));
    }
    public function proyectoadd(Request $request)
    {
        $proyecto = new Proyecto();
        $proyecto->nombre_proyecto = $request->proyectoname;
        $proyecto->descripcion_proyecto = $request->descripcion;
        $proyecto->url = $request->url;
        $proyecto->fase = $request->gender;
        $proyecto->save();
        $idProyecto = $proyecto->id;
        $userId = Auth::id();
        $proyint = new proyint();
        $proyint->id_proyecto = $idProyecto;
        $proyint->id_usuario = $userId;
        $proyint->puesto = 'Lider';
        $proyint->save();
        // $listproyect = proyint::where('id_usuario', $userId)->pluck('id_proyecto');
        // $proyectos = Proyecto::whereIn('id_proyecto', $listproyect)->get();
        // return view('indexideas',compact('proyectos'));
        return redirect()->route('indexideas');
    }
    public function updateVentas(Request $request, $proyectoId)
    {
        $semana = date('W');
        $anio = date('Y');
        $numeroMesActual = date('n');
        $fechaActual = date('Y-m-d');
        $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));
        $updateventa = new updateventas();
        $updateventa->id_proyecto = $proyectoId;
        $updateventa->cantidad = $request->cantidad1;
        $updateventa->anio = $anio;
        $updateventa->porcentaje = $request->porcentaje;
        $updateventa->ganancia = $request->ganancias;
        $updateventa->cliente = $request->namecliente;
        $updateventa->rango1 = $rangoInicioSemana;
        $updateventa->rango2 = $rangoFinSemana;
        $updateventa->mes = $numeroMesActual;
        $updateventa->fecha = $fechaActual;
        $updateventa->save();
        Session::flash('successventas', 'Los datos se han guardado exitosamente.');
        // Retornar una respuesta JSON
        return redirect()->back();
    }
    public function updateSemanal(Request $request, $proyectoId)
    {
        $semana = date('W');
        $anio = date('Y');
        $numeroMesActual = date('n');
        $fechaActual = date('Y-m-d');
        $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));

        $existe = updateuser::where('id_proyecto',  $proyectoId)->where('numero_semana', $semana)->where('anio', $anio)->exists();
        if ($existe) {
            // actualiza
            updateuser::where('id_proyecto', $proyectoId)->where('numero_semana', $semana)->where('anio', $anio)
                ->update([
                    'personas' => $request->personas,
                    'aprender' => $request->aprender
                ]);
            Session::flash('success', 'Los datos se han actualizado exitosamente.');
        } else {
            $updatesem = new updateuser();
            $updatesem->id_proyecto = $proyectoId;
            $updatesem->numero_semana = $semana;
            $updatesem->anio = $anio;
            $updatesem->personas = $request->personas;
            $updatesem->aprender = $request->aprender;
            $updatesem->rango1 = $rangoInicioSemana;
            $updatesem->rango2 = $rangoFinSemana;
            $updatesem->mes = $numeroMesActual;
            $updatesem->fecha = $fechaActual;
            $updatesem->save();
            Session::flash('success', 'Los datos se han guardado exitosamente.');
        }

        // Retornar una respuesta JSON
        return redirect()->back();
    }
    public function updateAnimo(Request $request, $proyectoId)
    {
        $semana = date('W');
        $anio = date('Y');
        $numeroMesActual = date('n');
        $fechaActual = date('Y-m-d');
        $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));

        $existe = updateanimo::where('id_proyecto',  $proyectoId)->where('numero_semana', $semana)->where('anio', $anio)->exists();
        if ($existe) {
            // actualiza
            updateanimo::where('id_proyecto', $proyectoId)->where('numero_semana', $semana)->where('anio', $anio)
                ->update([
                    // 'animo' => $request->animo,
                    'animo' => 3,
                    'metrica' => $request->metrica,
                    'obstaculo' => $request->obstaculo
                ]);
            Session::flash('success', 'Los datos se han actualizado exitosamente.');
        } else {
            $updateanimo = new updateanimo();
            $updateanimo->id_proyecto = $proyectoId;
            $updateanimo->numero_semana = $semana;
            $updateanimo->anio = $anio;
            // $updateanimo->animo = $request->animo;
            $updateanimo->animo = 1;
            $updateanimo->metrica = $request->metrica;
            $updateanimo->obstaculo = $request->obstaculo;
            $updateanimo->rango1 = $rangoInicioSemana;
            $updateanimo->rango2 = $rangoFinSemana;
            $updateanimo->mes = $numeroMesActual;
            $updateanimo->fecha = $fechaActual;
            $updateanimo->save();
            Session::flash('success', 'Los datos se han guardado exitosamente.');
        }

        // Retornar una respuesta JSON
        return redirect()->back();
    }

    public function addIntegrante(Request $request, $proyectoId)
    {
        echo  $request->email;
        echo  $proyectoId;
        $correo =  $request->email;
        $userId = Auth::id();
        $existe = User::where('email', $correo)->where('id', '!=', $userId)->exists();
        if ($existe) {
            // El registro existe       
            $id_new_user = User::where('email', $correo)->value('id');
            $proyint = new proyint();
            $existe2 = proyint::where('id_proyecto', $proyectoId)->where('id_usuario', $id_new_user)->exists();
            if ($existe2) {
                Session::flash('registro_no_existe', $correo . ' ya es parte del equipo');
                return redirect()->back();
            } else {
                $proyint->id_proyecto = $proyectoId;
                $proyint->id_usuario = $id_new_user;
                $proyint->puesto = 'Colaborador';
                $proyint->save();
                Session::flash('registro_no_existe', 'Usuario Registrado');
                return redirect()->back();
            }
        } else {
            Session::flash('registro_no_existe', 'El registro de ese correo no existe. Por favor, crea tu cuenta.');
            return redirect()->back();
        }
    }

    public function eliminarRegistro($idUsuario, $idProyecto)
    {
        $existe = proyint::where('id_proyecto', $idProyecto)->where('id_usuario', $idUsuario)->where('puesto', 'Lider')->exists();
        if ($existe) {
            Session::flash('registro_no_existe', 'No puedes eliminar al Lider de este proyecto.');
            return redirect()->back();
        } else {
            proyint::where('id_usuario', $idUsuario)->where('id_proyecto', $idProyecto)->delete();

            // return response()->json(['mensaje' => 'Registro eliminado correctamente']);
            Session::flash('registro_no_existe', 'Usuario eliminado correctamente.');
            return redirect()->back();
        }
    }
    public function eliminarVenta($idVenta)
    {
        updateVentas::where('id', $idVenta)->delete();
        // return response()->json(['mensaje' => 'Registro eliminado correctamente']);
        Session::flash('successventas', 'Venta eliminada correctamente.');
        return redirect()->back();
    }
    public function CambiarStatus($idObj){
        // actualiza
        updateobjetivos::where('id', $idObj)
        ->update([
            'status' => 2
        ]);
    return redirect()->back();

    }
    public function obtenerDatos(Request $request, $opcion, $idProyecto)
    {
        if ($opcion === "semana") {
            // Obtener los datos por semana
            $datos = UpdateUser::select(UpdateUser::raw("CONCAT(rango1, ' - ', rango2) AS semanaanio"), UpdateUser::raw('SUM(personas) as total_personas'))
                ->where('id_proyecto', $idProyecto)
                ->groupBy('rango1', 'rango2')
                ->get();
        } elseif ($opcion === "mes") {
            // Obtener los datos por mes
            // Lógica para obtener los datos por mes
            $datos = UpdateUser::select(UpdateUser::raw("CONCAT(mes, '/', anio) AS semanaanio"), UpdateUser::raw('SUM(personas) as total_personas'))
                ->where('id_proyecto', $idProyecto)
                ->groupBy('mes', 'anio')
                ->get();
        } elseif ($opcion === "anio") {
            // Obtener los datos por año
            // Lógica para obtener los datos por año
            $datos = UpdateUser::select(UpdateUser::raw("anio AS semanaanio"), UpdateUser::raw('SUM(personas) as total_personas'))
                ->where('id_proyecto', $idProyecto)
                ->groupBy('anio')
                ->get();
        }

        return response()->json($datos);
    }
    public function obtenerDatosventas(Request $request, $opcion, $idProyecto)
    {
        if ($opcion === "semana") {
            // Obtener los datos por semana
            $datos = updateVentas::select(updateVentas::raw("CONCAT(rango1, ' - ', rango2) AS semanaanio"), updateVentas::raw('SUM(ganancia)  as total_ganancias'))
                ->where('id_proyecto', $idProyecto)
                ->groupBy('rango1', 'rango2')
                ->get();
        } elseif ($opcion === "mes") {
            // Obtener los datos por mes
            // Lógica para obtener los datos por mes
            $datos = updateVentas::select(updateVentas::raw("CONCAT(mes, '/', anio) AS semanaanio"), updateVentas::raw('SUM(ganancia) as total_ganancias'))
                ->where('id_proyecto', $idProyecto)
                ->groupBy('mes', 'anio')
                ->get();
        } elseif ($opcion === "anio") {
            // Obtener los datos por año
            // Lógica para obtener los datos por año
            $datos = updateVentas::select(updateVentas::raw("anio AS semanaanio"), updateVentas::raw('SUM(ganancia) as total_ganancias'))
                ->where('id_proyecto', $idProyecto)
                ->groupBy('anio')
                ->get();
        }


        return response()->json($datos);
    }
    public function obtenerDatosganancias($idProyecto)
    {
        $numeroMesActual = date('n');
        $mesanterior = date('n') - 1;

        $resultado = updateVentas::select(
            updateVentas::raw("SUM(CASE WHEN mes =  $numeroMesActual THEN ganancia ELSE 0 END) AS suma_mesactual"),
            updateVentas::raw("SUM(CASE WHEN mes =  $mesanterior THEN ganancia ELSE 0 END) AS suma_mesanterior"),
            updateVentas::raw("SUM(ganancia) AS suma_total")
        )
            ->where('id_proyecto', $idProyecto)
            ->get();
        return response()->json($resultado);
    }
    public function updateObjetivo(Request $request, $idProyecto)
    {


        $semana = date('W');
        $anio = date('Y');
        $numeroMesActual = date('n');
        $fechaActual = date('Y-m-d');
        $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));
        $updateventa = new updateobjetivos();
        $updateventa->id_proyecto = $idProyecto;
        $updateventa->usuario = $request->usuario_id;
        $updateventa->anio = $anio;
        $updateventa->objetivo = $request->objetivo;
        $updateventa->rango1 = $rangoInicioSemana;
        $updateventa->rango2 = $rangoFinSemana;
        $updateventa->mes = $numeroMesActual;
        $updateventa->fecha = $fechaActual;
        $updateventa->numero_semana = $semana;
        $updateventa->status = 1;
        $updateventa->save();
        Session::flash('successventas', 'Los datos se han guardado exitosamente.');
        // Retornar una respuesta JSON
        return redirect()->back();
        return $request;
    }
}
