<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locacion;
use App\Models\AuditoriaDetalle;
use App\Models\ConteoDetalle;

class LocacionController extends Controller
{
    public function Listado($id_almacen){
        $locaciones = Locacion::all()->where("id_almacen", $id_almacen)->where("estado", 1);
        $nuevas_locaciones = [];
        foreach ($locaciones as $locacion) {
            $locacion->estantes;
            foreach ($locacion->estantes as $estante) {
                $estante->locacion;
            }
            array_push($nuevas_locaciones, $locacion);
        }
        return response()->json([
            "locaciones" => $nuevas_locaciones
        ]);
    }

    public function Guardar(Request $request){
        $data = $request->all();
        $mensaje = "";
        $error = true;
        if($data){
            $data = (object) $data;
            $locacion = new Locacion;
            $locacion->nombre = $data->nombre_locacion;
            if($data->descripcion != null){
                $locacion->descripcion = $data->descripcion;
            }
            $locacion->id_almacen = $data->id_almacen;
            if($locacion->save()){
                $mensaje = "Proceso exitoso";
                $error = false;
            }else{
                $mensaje = "Ocurrio un error";
            }
        }else{
            $mensaje = "Ocurrio un error en el envio de la data";
        }

        return response()->json([
            "mensaje" => $mensaje,
            "error" => $error
        ]);
    }

    public function EstantesPorLocacion($id_locacion){
        $locacion = Locacion::findOrFail($id_locacion);
        $estantes = $locacion->estantes;
        $nuevos_estantes = [];
        foreach ($estantes as $estante) {
            if($estante->estado == 1){
                array_push($nuevos_estantes, $estante);
            }
        }
        return response()->json([
            "estantes" => $nuevos_estantes
        ]);
    }

    public function EliminarLocacion($id_locacion){
        $locacion = Locacion::find($id_locacion);
        $mensaje = "";
        $error = true;
        $puede_eliminar = false;
        if($locacion){
            if(count($locacion->estantes) > 0){
                foreach ($locacion->estantes as $estante) {
                    $auditoria_detalle = AuditoriaDetalle::where("id_estante", $estante->id_estante)->first();
                    $conteo_detalle = ConteoDetalle::where("id_estante", $estante->id_estante)->first();
                    if($auditoria_detalle or $conteo_detalle){
                        $mensaje = "No se puede eliminar esta locacion debido a que existen auditorias o conteos en curso";
                        return response()->json([
                            "mensaje" => $mensaje,
                            "error" => $error
                        ]);
                        die;
                    }else{
                        $puede_eliminar = true;
                    }
                }
            }else{
                $puede_eliminar = true;
            }

            if($puede_eliminar){
                if(count($locacion->estantes) > 0){
                    foreach ($locacion->estantes as $estante) {
                        if(count($estante->filas) > 0){
                            foreach ($estante->filas as $fila) {
                                $fila->estado = 0;
                                $fila->update();
                            }
                        }
                        $estante->estado = 0;
                        $estante->update();
                    }
                }
                $locacion->estado = 0;
                if($locacion->update()){
                    $mensaje = "Locacion eliminada correctamente";
                    $error = false;
                }else{
                    $mensaje = "Ocurrio un error";
                }
            }
        }

        return response()->json([
            "mensaje" => $mensaje,
            "error" => $error
        ]);
    }
}
