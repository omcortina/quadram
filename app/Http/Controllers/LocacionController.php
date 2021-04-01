<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locacion;

class LocacionController extends Controller
{
    public function Listado($id_almacen){
        $locaciones = Locacion::all()->where("id_almacen", $id_almacen);
        foreach ($locaciones as $locacion) {
            $locacion->estantes;
            foreach ($locacion->estantes as $estante) {
                $estante->locacion;
            }
        }
        return response()->json([
            "locaciones" => $locaciones
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
        return response()->json([
            "estantes" => $estantes
        ]);
    }
}
