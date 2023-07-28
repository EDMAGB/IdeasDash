<?php

namespace App\Http\Controllers;

use App\Models\Integrantes_Equipo;
use App\Models\Integrantes_Equipos;
use App\Models\IntegrantesProyecto;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\proyint;
use App\Models\purpose;
use App\Models\updateanimo;
use App\Models\updateobjetivos;
use App\Models\updateuser;
use App\Models\updateventas;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use   App\Http\Controllers\Str;




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
        $restab = proyint::join("users", "users.id", "=", "proyints.id_usuario")->select("id_proyecto", "puesto", "name", "email", "id_usuario")->get();
        $primerProyecto = $proyectos->first();
        $activeTab = $request->query('tab', 'tab' . ($primerProyecto ? $primerProyecto->id_proyecto : ''));

        $rangos = updateuser::select('rango1', 'rango2', 'id_proyecto')
            ->groupBy('rango1', 'rango2', 'id_proyecto')
            ->whereNotIn('rango1', [$rangoInicioSemana])
            ->whereNotIn('rango2', [$rangoFinSemana])
            ->orderBy('rango1', 'asc')
            ->get();
        // return $tablaobj ;
        return view('indexideas', compact('proyectos', 'activeTab', 'rangos'));
    }
    public function RangosT(Request $request, $idProyecto)
    {
        $fechaActual = date($request->rango);
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));
        return  $request;
    }
    // public function __invoke(Request $request )
    // {


    //     $fechaActual = date('Y-m-d');
    //     $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
    //     $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));
    //     $userId = Auth::id();
    //     $listproyect = proyint::where('id_usuario', $userId)->pluck('id_proyecto');
    //     $proyectos = Proyecto::whereIn('id_proyecto', $listproyect)->get();
    //     $Integrantes =  proyint::whereIn('id_proyecto', $listproyect)->pluck('id_usuario');
    //     $usuariosint = User::whereIn('id', $Integrantes);
    //     $restab = proyint::join("users", "users.id", "=", "proyints.id_usuario")->select("id_proyecto", "puesto", "name", "email", "id_usuario")->get();
    //     $ventas = updateventas::where('rango1', $rangoInicioSemana)->where('rango2', $rangoFinSemana)->get();
    //     // return $proyectos;
    //     $activeTab = $request->query('tab', 'tab1');

    //     $tablaobj = User::from('users AS u')
    //         ->join('updateobjetivos AS d', 'd.usuario', '=', 'u.id')
    //         ->select('u.id', 'u.name', 'd.objetivo', 'd.status', 'd.id_proyecto', 'd.id as id_obj', 'd.status')
    //         ->get();
    //     $rangos = updateuser::select('rango1', 'rango2','id_proyecto')
    //         ->groupBy('rango1', 'rango2','id_proyecto')
    //         ->whereNotIn('rango1', [$rangoInicioSemana])
    //         ->whereNotIn('rango2', [$rangoFinSemana])
    //         ->orderBy('rango1', 'asc') 
    //         ->get();
    //     // return $tablaobj ;
    //     return view('indexideas', compact('proyectos', 'activeTab', 'restab', 'ventas', 'tablaobj', 'rangos'));
    // }
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
    public function updateVentas(Request $request, $proyectoId, $Inver, $Venta, $Porcentaje, $Ganancias,$rango)
    {
        $existe = updateventas::where('id_proyecto',  $proyectoId)->where('rango2', $rango)->exists();
        if ($existe) {
            // actualiza
            updateventas::where('id_proyecto', $proyectoId)->where('rango2', $rango)
                ->update([
                    'cantidad' => $Venta,
                    'porcentaje' => $Porcentaje,
                    'ganancia'=> $Ganancias,
                    'inversion'=> $Inver
                ]);
            $mnsj = 'Los datos se han actualizado exitosamente.';
        } else {
            $semana = date('W');
            $anio = date('Y');
            $numeroMesActual = date('n');
            $fechaActual = date($rango);
            $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
            $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));
            $updateventa = new updateventas();
            $updateventa->id_proyecto = $proyectoId;
            $updateventa->cantidad = $Venta;
            $updateventa->anio = $anio;
            $updateventa->porcentaje = $Porcentaje;
            $updateventa->ganancia = $Ganancias;
            $updateventa->inversion = $Inver;
            $updateventa->rango1 = $rangoInicioSemana;
            $updateventa->rango2 = $rangoFinSemana;
            $updateventa->mes = $numeroMesActual;
            $updateventa->fecha = $fechaActual;
            $updateventa->save();
            $mnsj = 'Los datos se han guardado exitosamente.';
        }
        // Retornar una respuesta JSON
        return response()->json($mnsj);
    }
    public function updateSemanal(Request $request, $proyectoId, $aprender, $personas,$rango)
    {
        $semana = date('W');
        $anio = date('Y');
        $numeroMesActual = date('n');
        $fechaActual = date($rango);
        $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));
        $mnsj = '';

        $existe = updateuser::where('id_proyecto',  $proyectoId)->where('rango2', $rango)->exists();
        if ($existe) {
            // actualiza
            updateuser::where('id_proyecto', $proyectoId)->where('rango2', $rango)
                ->update([
                    'personas' => $personas,
                    'aprender' => $aprender
                ]);
            $mnsj = 'Los datos se han actualizado exitosamente, con el rango'.$rango;
        } else {
            $updatesem = new updateuser();
            $updatesem->id_proyecto = $proyectoId;
            $updatesem->numero_semana = $semana;
            $updatesem->anio = $anio;
            $updatesem->personas = $personas;
            $updatesem->aprender = $aprender;
            $updatesem->rango1 = $rangoInicioSemana;
            $updatesem->rango2 = $rangoFinSemana;
            $updatesem->mes = $numeroMesActual;
            $updatesem->fecha = $fechaActual;
            $updatesem->save();
            $mnsj = 'Los datos se han guardado exitosamente.'.$rango;
        }

        return response()->json($mnsj);
    }
    public function updatemAnimo(Request $request, $proyectoId,$Animo , $Metrica, $Obstaculo, $rango)
    {
        $semana = date('W');
        $anio = date('Y');
        $numeroMesActual = date('n');
        $fechaActual = date($rango);
        $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));

        $existe = updateanimo::where('id_proyecto',  $proyectoId)->where('rango2', $rango)->exists();
        if ($existe) {
            // actualiza
            updateanimo::where('id_proyecto', $proyectoId)->where('rango2', $rango)
                ->update([
                    'animo' => $Animo,
                    'metrica' => $Metrica,
                    'obstaculo' => $Obstaculo
                ]);
                $mnsj = 'Los datos se han actualizado exitosamente.';
        } else {
            $updateanimo = new updateanimo();
            $updateanimo->id_proyecto = $proyectoId;
            $updateanimo->numero_semana = $semana;
            $updateanimo->anio = $anio;
            $updateanimo->animo = $Animo;
            $updateanimo->metrica = $Metrica;
            $updateanimo->obstaculo = $Obstaculo;
            $updateanimo->rango1 = $rangoInicioSemana;
            $updateanimo->rango2 = $rangoFinSemana;
            $updateanimo->mes = $numeroMesActual;
            $updateanimo->fecha = $fechaActual;
            $updateanimo->save();
            $mnsj = 'Los datos se han guardado exitosamente.';
        }

        // Retornar una respuesta JSON
        return response()->json($mnsj);
    }

    public function addIntegrante(Request $request, $proyectoId, $correo)
    {
        $userId = Auth::id();
        $existe = User::where('email', $correo)->where('id', '!=', $userId)->exists();
        $mnsj = '';

        if ($existe) {
            $id_new_user = User::where('email', $correo)->value('id');
            $proyint = new proyint();
            $existe2 = proyint::where('id_proyecto', $proyectoId)->where('id_usuario', $id_new_user)->exists();
            if ($existe2) {

                $mnsj = $correo . ' ya es parte del equipo';
            } else {
                $proyint->id_proyecto = $proyectoId;
                $proyint->id_usuario = $id_new_user;
                $proyint->puesto = 'Colaborador';
                $proyint->save();

                $mnsj =  'Usuario Registrado correctamente al proyecto';
            }
        } else {
            $mnsj = 'El registro de ese correo no existe. Por favor, crea tu cuenta.';
        }
        $restab = proyint::join("users", "users.id", "=", "proyints.id_usuario")->select("id_proyecto", "puesto", "name", "email", "id_usuario")->where('id_proyecto', $proyectoId)->get();
        $restab->push(['mnsj' => $mnsj]);

        return response()->json($restab);
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
    public function CambiarStatus($idObj)
    {
        // actualiza
        updateobjetivos::where('id', $idObj)
            ->update([
                'status' => 2
            ]);
        return redirect()->back();
    }
    public function obtenerDatos(Request $request, $opcion, $idProyecto, $rango1, $rango2)
    {
        if ($opcion === "semana") {
            // Obtener los datos por semana
            $datos = UpdateUser::select(UpdateUser::raw("CONCAT(rango1, ' - ', rango2) AS semanaanio"), UpdateUser::raw('SUM(personas) as total_personas'))
                ->where('id_proyecto', $idProyecto)
                // ->whereBetween('rango1', ['0', $rango1])
                // ->whereBetween('rango2', ['0', $rango2])
                ->groupBy('rango1', 'rango2')
                ->orderBy('rango1', 'asc')
                ->get();
        } elseif ($opcion === "mes") {
            // Obtener los datos por mes
            // Lógica para obtener los datos por mes
            $datos = UpdateUser::select(UpdateUser::raw("CONCAT(mes, '/', anio) AS semanaanio"), UpdateUser::raw('SUM(personas) as total_personas'))
                ->where('id_proyecto', $idProyecto)
                // ->whereBetween('rango1', ['0', $rango2])
                // ->whereBetween('rango2', ['0', $rango2])
                ->groupBy('mes', 'anio')
                ->orderBy('mes', 'asc')
                ->get();
        } elseif ($opcion === "anio") {
            // Obtener los datos por año
            // Lógica para obtener los datos por año
            $datos = UpdateUser::select(UpdateUser::raw("anio AS semanaanio"), UpdateUser::raw('SUM(personas) as total_personas'))
                ->where('id_proyecto', $idProyecto)
                // ->whereBetween('rango1', ['0', $rango2])
                // ->whereBetween('rango2', ['0', $rango2])
                ->groupBy('anio')
                ->orderBy('anio', 'asc')
                ->get();
        }

        return response()->json($datos);
    }
    public function obtenerDatosInv(Request $request, $opcion, $idProyecto, $rango1, $rango2)
    {
        if ($opcion === "semana") {
            // Obtener los datos por semana
            $datos = updateVentas::select(updateventas::raw("CONCAT(rango1, ' - ', rango2) AS semanaanio"), updateventas::raw('SUM(ganancia) as total_ganancia'), updateventas::raw('SUM(inversion) as total_inversion'))
                ->where('id_proyecto', $idProyecto)
                ->groupBy('rango1', 'rango2')
                ->orderBy('rango1', 'asc')
                ->get();
        } elseif ($opcion === "mes") {
            // Obtener los datos por mes
        
        } elseif ($opcion === "anio") {
            // Obtener los datos por año
           
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
                ->orderBy('rango1', 'asc')
                ->get();
        } elseif ($opcion === "mes") {
            // Obtener los datos por mes
            // Lógica para obtener los datos por mes
            $datos = updateVentas::select(updateVentas::raw("CONCAT(mes, '/', anio) AS semanaanio"), updateVentas::raw('SUM(ganancia) as total_ganancias'))
                ->where('id_proyecto', $idProyecto)
                ->groupBy('mes', 'anio')
                ->orderBy('mes', 'asc')
                ->get();
        } elseif ($opcion === "anio") {
            // Obtener los datos por año
            // Lógica para obtener los datos por año
            $datos = updateVentas::select(updateVentas::raw("anio AS semanaanio"), updateVentas::raw('SUM(ganancia) as total_ganancias'))
                ->where('id_proyecto', $idProyecto)
                ->groupBy('anio')
                ->orderBy('anio', 'asc')
                ->get();
        }


        return response()->json($datos);
    }

    public function obtenerDatosganancias($idProyecto, $rango2)
    {
        $numeroMesActual = date('n', strtotime($rango2));
        $mesanterior = date('n', strtotime('-1 month', strtotime($rango2)));
        $anoF = date('Y', strtotime($rango2));

        $datosVenta = updateventas::where('rango2', $rango2)
            ->where('id_proyecto', $idProyecto)
            ->select('cantidad', 'porcentaje', 'ganancia', 'inversion')
            ->first();

        $venta = $datosVenta ? $datosVenta->cantidad : '0';
        $porcentaje = $datosVenta ? $datosVenta->porcentaje : '0';
        $ganancia = $datosVenta ? $datosVenta->ganancia : '0';
        $inversion = $datosVenta ? $datosVenta->inversion : '0';
        $personaData = updateuser::where('rango2', $rango2)
            ->where('id_proyecto', $idProyecto)
            ->select('personas', 'aprender')
            ->first();

        $personas = $personaData ? $personaData->personas : 'Sin Información';
        $aprender = $personaData ? $personaData->aprender : 'Sin Información';

        $cantidad = updateventas::where('rango2', $rango2)
            ->where('id_proyecto', $idProyecto)
            ->value('cantidad') ?? 'Sin Información';

        $restab = proyint::join("users", "users.id", "=", "proyints.id_usuario")->select("id_proyecto", "puesto", "name", "email", "id_usuario")->where('id_proyecto', $idProyecto)->get() ?? 'Sin Información';;

        $ima = purpose::where('id_proyecto', $idProyecto)->value('nombre') ?? 0;

        $animodata = updateanimo::where('rango2', $rango2)
            ->where('id_proyecto', $idProyecto)
            ->select('animo', 'metrica', 'obstaculo')
            ->first();



        $animo = $animodata ? $animodata->animo : 'Sin Información';
        $metrica = $animodata ? $animodata->metrica : 'Sin Información';
        $obstaculo = $animodata ? $animodata->obstaculo : 'Sin Información';
        // Consulta principal
        $resultado = UpdateVentas::select(
            UpdateVentas::raw("SUM(CASE WHEN anio = $anoF AND mes =  $numeroMesActual THEN ganancia ELSE 0 END) AS suma_mesactual"),
            UpdateVentas::raw("SUM(CASE WHEN anio = $anoF AND mes =  $mesanterior THEN ganancia ELSE 0 END) AS suma_mesanterior"),
            UpdateVentas::raw("SUM(ganancia) AS suma_total")
        )
            ->where('id_proyecto', $idProyecto)
            ->get();


        // Agregar los resultados de las consultas adicionales al JSON
        $resultado[0]->personas = $personas;
        $resultado[0]->aprender = $aprender;
        $resultado[0]->cantidad = $cantidad;
        $resultado[0]->imagen = $ima;
        $resultado[0]->animo = $animo;
        $resultado[0]->metrica = $metrica;
        $resultado[0]->obstaculo = $obstaculo;
        $resultado[0]->integrantes = $restab;
        $resultado[0]->venta = $venta;
        $resultado[0]->porcentaje = $porcentaje;
        $resultado[0]->ganancia = $ganancia;
        $resultado[0]->inversion = $inversion;

        return response()->json($resultado);
    }


    public function obtenerDatosObjetivos($idProyecto, $rango1, $rango2)
    {
        $tablaobj = User::from('users AS u')
            ->join('updateobjetivos AS d', 'd.usuario', '=', 'u.id')
            ->select('u.id', 'u.name', 'd.objetivo', 'd.status', 'd.id_proyecto', 'd.id as id_obj', 'd.status')
            ->where('rango1', $rango1)
            ->where('rango2', $rango2)
            ->where('id_proyecto', $idProyecto)
            ->get();
        return response()->json($tablaobj);
    }
    public function updateObjetivo(Request $request, $idProyecto,$obj,$inte,$rango)
    {

        $mnsj = '';
        $semana = date('W');
        $anio = date('Y');
        $numeroMesActual = date('n');
        $fechaActual = date($rango);
        $rangoInicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($fechaActual)));
        $rangoFinSemana = date('Y-m-d', strtotime('sunday this week', strtotime($fechaActual)));
        $updateventa = new updateobjetivos();
        $updateventa->id_proyecto = $idProyecto;
        $updateventa->usuario = $inte;
        $updateventa->anio = $anio;
        $updateventa->objetivo = $obj;
        $updateventa->rango1 = $rangoInicioSemana;
        $updateventa->rango2 = $rangoFinSemana;
        $updateventa->mes = $numeroMesActual;
        $updateventa->fecha = $fechaActual;
        $updateventa->numero_semana = $semana;
        $updateventa->status = 1;
        $updateventa->save();
        $mnsj =  'Los datos se han guardado exitosamente.';
        // Retornar una respuesta JSON
        $restab =User::select("name")->where('id', $inte)->get();
        $restab->push(['mnsj' => $mnsj]);

        return response()->json($restab);
    }

    public function uploadImage(Request $request)
    {
        // dd($request->file('imagen'));

        if ($request->hasFile("imagen")) {
            $ima = new purpose();
            $ima->id_proyecto =  $request->id_proyect;
            $projectID = $ima->id_proyecto;
            $extension = $request->file('imagen')->getClientOriginalExtension();
            $imageName = $projectID  . '.' . $extension;
            $ima->imagen = $imageName;
            $ima->nombre = $imageName;
            $ruta = public_path("images");
            $request->file('imagen')->move($ruta, $imageName);
            $ima->save();
        } else {
            echo 'No se seleccionó ninguna imagen.';
        }

        return redirect()->back()->with('success', 'Imagen subida correctamente.');
    }
}
