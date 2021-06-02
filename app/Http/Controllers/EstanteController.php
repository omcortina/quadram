<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estante;
use App\Models\FilaEstante;
use App\Models\AuditoriaDetalle;
use App\Models\ConteoDetalle;

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

    public function GuardarFila(Request $request){
        $data = $request->all();
        $mensaje = "";
        $error = true;
        if($data){
            $data = (object) $data;
            $fila_data = (object) $data->fila;
            $fila = new FilaEstante;
            $fila->nombre = $fila_data->nombre;
            $fila->id_estante = $fila_data->id_estante;
            $fila->estado = $fila_data->estado;
            if($fila->save()){
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

    public function FilasPorEstante($id_estante){
        $estante = Estante::findOrFail($id_estante);
        $filas = $estante->filas;
        $nuevas_filas = [];
        foreach ($filas as $fila) {
            if($fila->estado == 1){
                array_push($nuevas_filas, $fila);
            }
        }
        return response()->json([
            "filas" => $nuevas_filas
        ]);
    }

    public function EliminarEstante($id_estante){
        $estante = Estante::find($id_estante);
        $error = true;
        $mensaje = "";
        if($estante){
            $auditoria_detalle = AuditoriaDetalle::where("id_estante", $estante->id_estante)->first();
            $conteo_detalle = ConteoDetalle::where("id_estante", $estante->id_estante)->first();
            if($auditoria_detalle or $conteo_detalle){
                $mensaje = "No se puede eliminar el estante debido a que existen auditorias o conteos en curso";
            }else{
                $estante->estado = 0;
                if($estante->update()){
                    $error = false;
                    $mensaje = "Estante eliminado correctamente";
                }
            }
        }

        return response()->json([
            "mensaje" => $mensaje,
            "error" => $error
        ]);
    }
}
