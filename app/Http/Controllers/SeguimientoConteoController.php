<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\Inventario;
use App\Models\Locacion;
use App\Models\Usuario;
use App\Models\Conteo;
use App\Models\Estante;
use App\Models\ConteoDetalle;
use App\Models\SeguimientoAuditoria;
use App\Models\SeguimientoConteo;

class SeguimientoConteoController extends Controller
{
    public function Informe(Request $request)
    {
    	$data = (object) $request->all();
    	if ($data) {
    		$usuario = Usuario::find($data->usuario);
    		$conteo = Conteo::find($data->conteo);
    		$estante = isset($data->estante) ? Estante::find($data->estante) : null;
    		$num_conteo = isset($data->num_conteo) ? $data->num_conteo : null;
    		$detalles = ConteoDetalle::all()
    								->where('id_conteo',$conteo->id_conteo)
    							    ->where('id_usuario', $usuario->id_usuario);

   			return view('seguimiento_conteo.informe_personal', compact([
   				'usuario', 'conteo', 'estante', 'num_conteo']));
    	}
    }
}
