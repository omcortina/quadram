<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Almacen;

class AlmacenController extends Controller
{
    public function VerListado(){
        return view('almacen.listado');
    }

    public function Listado(){
        $almacenes = Almacen::all();
        return response()->json([
            'almacenes' => $almacenes
        ]);
    }

    public function Guardar(Request $request){
        $data = $request->all();
        $mensaje = "";
        $error = true;
        if($data){
            $data = (object) $data;
            $data_almacen = (object) $data->almacen;
            $almacen = new Almacen;
            $almacen->nombre = $data_almacen->nombre;
            $almacen->direccion = $data_almacen->direccion;
            $almacen->telefono = $data_almacen->telefono;
            $almacen->estado = $data_almacen->estado;
            if($almacen->save()){
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

    public function Informacion($id_almacen){
        $almacen = Almacen::findOrFail($id_almacen);
        return view('almacen.informacion', compact('almacen'));
    }

    public function CambiarEstado($id_almacen){
        $almacen = Almacen::find($id_almacen);
        $mensaje = "";
        $error = true;
        if($almacen){
            if($almacen->estado == 1){
                $almacen->estado = 0;
                if($almacen->update()){
                    $mensaje = "Se actualizó el estado del almacen";
                    $error = false;
                }else{
                    $mensaje = "Ocurrio un error";
                }
            }else{
                $almacen->estado = 1;
                if($almacen->update()){
                    $mensaje = "Se actualizó el estado del almacen";
                    $error = false;
                }else{
                    $mensaje = "Ocurrio un error";
                }
            }
        }else{
            $mensaje = "El almacen por actualizar no existe";
        }

        return response()->json([
            "mensaje" => $mensaje,
            "error" => $error
        ]);
    }
}
