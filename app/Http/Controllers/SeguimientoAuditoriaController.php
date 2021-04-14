<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\Inventario;
use App\Models\Locacion;
use App\Models\Usuario;
use App\Models\Conteo;
use App\Models\Estante;
use App\Models\AuditoriaDetalle;
use App\Models\SeguimientoAuditoria;

class SeguimientoAuditoriaController extends Controller
{
    public function Informe(Request $request)
    {
    	$data = (object) $request->all();
    	if ($data) {
    		$usuario = Usuario::find($data->usuario);
    		$auditoria = Auditoria::find($data->auditoria);
    		$estante = isset($data->estante) ? Estante::find($data->estante) : null;
    		$detalles = AuditoriaDetalle::all()
    								->where('id_auditoria',$auditoria->id_auditoria)
    							    ->where('id_usuario', $usuario->id_usuario);

   			return view('seguimiento_auditoria.informe_personal', compact([
   				'usuario', 'auditoria', 'estante', 'detalles']));
    	}
    }
}
