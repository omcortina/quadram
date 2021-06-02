<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FilaEstante;
use App\Models\SeguimientoAuditoria;
use App\Models\SeguimientoConteo;

class FilaEstanteController extends Controller
{
    public function EliminarFila($id_fila){
        $fila = FilaEstante::find($id_fila);
        $error = true;
        $mensaje = "";
        if($fila){
            $seguimiento_auditoria = SeguimientoAuditoria::where("id_fila_estante", $fila->id_fila_estante)->first();
            $seguimiento_conteo = SeguimientoConteo::where("id_fila_estante", $fila->id_fila_estante)->first();
            if($seguimiento_auditoria or $seguimiento_conteo){
                $mensaje = "No se puede eliminar esta fila debido a que existen auditorías o conteos en curso";
            }else{
                $fila->estado = 0;
                if($fila->update()){
                    $mensaje = "Fila eliminada correctamente";
                    $error = false;
                }
            }
        }

        return response()->json([
            "mensaje" => $mensaje,
            "error" => $error
        ]);
    }
}
