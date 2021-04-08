<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\Inventario;
use App\Models\Locacion;
use App\Models\Usuario;
use App\Models\AuditoriaDetalle;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
    public function Listado($id_inventario)
	{
		$inventario = Inventario::find($id_inventario);
		return view('auditoria.listado', compact(['inventario']));
	}

	public function Gestion(Request $request)
	{
		$data = (object) $request->all();
		$auditoria = new Auditoria;
		$inventario = new Inventario;
		if(isset($data->inventario)) $inventario = Inventario::find($data->inventario);
		if(isset($data->auditoria)) $auditoria = Auditoria::find($data->auditoria);
		$usuarios_auditoria = Usuario::all()->where('estado', 1)->where('id_dominio_tipo_usuario', 3);
		$usuarios_conteo = Usuario::all()->where('estado', 1)->where('id_dominio_tipo_usuario', 4);
		return view("auditoria.gestion", compact(['auditoria', 'inventario', 'usuarios_auditoria', 'usuarios_conteo']));
	}

	public function BuscarLocaciones(Request $request, $id_almacen)
	{
		$post = (object) $request->all();
		$data = [];
		$locaciones = Locacion::all()->where('id_almacen', $id_almacen);
		foreach ($locaciones as $locacion) {
			$detalle['id_locacion'] = $locacion->id_locacion;
			$detalle['nombre'] = $locacion->nombre;
			$detalle['estantes'] = [];
			foreach ($locacion->estantes as $estante) {

				$encargado = [ 'id_usuario' => 0, 'nombre' => "No asignado" ];
				
				if(isset($post->id_auditoria)){
					$detalle_encargado = AuditoriaDetalle::where('id_auditoria', $post->id_auditoria)
												 ->where('id_estante', $estante->id_estante)
												 ->first();
					if($detalle_encargado){
						$encargado = [ 
							'id_usuario' => $detalle_encargado->id_usuario, 
							'nombre' => $detalle_encargado->usuario->nombre_completo()
						];
					}
				}

				$detalle['estantes'][] = (object)[
					'id_estante' => $estante->id_estante,
					'nombre' => $estante->nombre,
					'encargado' => (object) $encargado
				];
			}

			$data[] = $detalle;
			
		}
		return response()->json([
			'data' => $data
		]);
	}
}
