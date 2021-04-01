<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estante;

class EstanteController extends Controller
{
    public function Listado(){
        $estantes = Estante::all();
        return response()->json([
            "estantes" => $estantes
        ]);
    }

    public function Guardar(Request $request){
        $data = $request->all();
        $mensaje = "";
        $error = true;
        if($data){
            $data = (object) $data;
            $estante_data = (object) $data->estante;
            $estante = new Estante;
            $estante->nombre = $estante_data->nombre;
            $estante->id_locacion = $estante_data->id_locacion;
            $estante->estado = $estante_data->estado;
            if($estante->save()){
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
}
