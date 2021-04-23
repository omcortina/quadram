<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conteo;
use App\Models\SeguimientoConteo;
use App\Models\SeguimientoAuditoria;
use Illuminate\Support\Facades\DB;

class ConteoController extends Controller
{
    public function Imprimir(Request $request, $id_conteo)
	{
		$seguimientos = [];
		$conteo = Conteo::find($id_conteo);
		$num_conteo = null;
		$data = (object) $request->all();
		if(isset($data->conteo)) $num_conteo = $data->conteo;

		$condicion_conteo = $num_conteo == null ? "" : "AND cd.conteo = $num_conteo";

		if($num_conteo != null){
			$sql = "SELECT sc.id_seguimiento_conteo 
					FROM seguimiento_conteo sc
					LEFT JOIN conteo_detalle cd USING(id_conteo_detalle)
					WHERE cd.estado = 1 AND sc.estado = 1
					AND cd.id_conteo = $id_conteo
					$condicion_conteo
					ORDER BY cd.id_conteo_detalle, id_fila_estante ASC";
			foreach (DB::select($sql) as $result) {
				$seguimientos[] = SeguimientoConteo::find($result->id_seguimiento_conteo);
			}
		}else{
			$sql = "SELECT sa.id_seguimiento_auditoria 
					FROM seguimiento_auditoria sa
					LEFT JOIN auditoria_detalle ad USING(id_auditoria_detalle)
					WHERE ad.estado = 1 AND sa.estado = 1
					AND ad.id_auditoria = $conteo->id_auditoria
					ORDER BY ad.id_auditoria_detalle, id_fila_estante ASC";
			foreach (DB::select($sql) as $result) {
				$seguimientos[] = SeguimientoAuditoria::find($result->id_seguimiento_auditoria);
			}
		}

		//CUANDO ES UN INFORME GENERAL SE HACE SEGUN LOS SEGUIMIENTOS DE LA AUDITORIA
		
		


		$view = $num_conteo == null ? 'pdf.informe_general_conteo' : 'pdf.informe_por_conteo';
		$pdf = \PDF::loadView($view, compact([
			'seguimientos', 'conteo', 'num_conteo'
		]));
    	return $pdf->stream('Informe de conteo.pdf');
	}
}
