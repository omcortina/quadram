<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\Inventario;
use App\Models\Locacion;
use App\Models\Usuario;
use App\Models\Conteo;
use App\Models\AuditoriaDetalle;
use App\Models\SeguimientoAuditoria;
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
		$conteo = new Conteo;
		if(isset($data->inventario)) $inventario = Inventario::find($data->inventario);
		if(isset($data->auditoria)){
			 $auditoria = Auditoria::find($data->auditoria);
			 $auditoria->fecha_inicio = str_replace(" ", "T", $auditoria->fecha_inicio);
			 $auditoria->fecha_fin = str_replace(" ", "T", $auditoria->fecha_fin);
			 if($auditoria->conteo() != null){
			 	$conteo = $auditoria->conteo();
			 	$conteo->fecha_inicio = str_replace(" ", "T", $conteo->fecha_inicio);
			 	$conteo->fecha_fin = str_replace(" ", "T", $conteo->fecha_fin);
			 }
		}
		$usuarios_auditoria = Usuario::all()->where('estado', 1)->where('id_dominio_tipo_usuario', 3);
		$usuarios_conteo = Usuario::all()->where('estado', 1)->where('id_dominio_tipo_usuario', 4);
		return view("auditoria.gestion", compact(['auditoria', 'conteo', 'inventario', 'usuarios_auditoria', 'usuarios_conteo']));
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
				$encargados_conteo = [];
				$seguimientos = [];
				if(isset($post->id_auditoria)){
					$detalle_encargado = AuditoriaDetalle::where('id_auditoria', $post->id_auditoria)
												 ->where('id_estante', $estante->id_estante)
												 ->first();
					if($detalle_encargado){
						$encargado = [ 
							'id_usuario' => $detalle_encargado->id_usuario, 
							'nombre' => $detalle_encargado->usuario->nombre_completo()
						];
						//SE VALIDA SI TIENE SEGUIMIENTOS EL USUARIO
						$seguimientos = SeguimientoAuditoria::all()
										->where('estado', 1)
										->where('id_auditoria_detalle', $detalle_encargado->id_auditoria_detalle);

						//BUSCAMOS SI HAY ENCARGADOS SIGNADOS EN LOS CONTEOS DEL ESTANTE

						
					}

					
				}



				$detalle['estantes'][] = (object)[
					'id_estante' => $estante->id_estante,
					'nombre' => $estante->nombre,
					'encargado' => (object) $encargado,
					'tiene_seguimientos' => count($seguimientos) > 0 ? true : false,
					'encargados' => $encargados_conteo
				];
			}

			$data[] = $detalle;
			
		}
		return response()->json([
			'data' => $data
		]);
	}

	public function Guardar(Request $request)
	{
		$post = $request->all();
		$error = true;
		$mensaje = "";
		if($post){
			$post = (object) $post; 
			
			$post->auditoria = (object) $post->auditoria;
			$post->conteo = (object) $post->conteo;

			$inventario = Inventario::find($post->id_inventario);
			
			$auditoria = $post->auditoria->id_auditoria == null ? new Auditoria : Auditoria::find($post->auditoria->id_auditoria);
			$auditoria->id_inventario = $inventario->id_inventario;
			$auditoria->fecha_inicio = date('Y-m-d H:i:s', strtotime(str_replace("T", " ", $post->auditoria->fecha_inicio)));
			$auditoria->fecha_fin = date('Y-m-d H:i:s', strtotime(str_replace("T", " ", $post->auditoria->fecha_fin)));
			$auditoria->id_usuario = session('id_usuario');
			$auditoria->estado = $post->auditoria->estado;
			if($auditoria->save()){
				//ELIMINAMOS TODOS LOS DETALLES ANTIGUOS QUE NO TENGAN UN SEGUIMIENTO
				DB::statement("DELETE FROM auditoria_detalle WHERE id_auditoria_detalle in (SELECT ad.id_auditoria_detalle FROM auditoria_detalle ad LEFT JOIN seguimiento_auditoria s USING(id_auditoria_detalle) WHERE s.id_seguimiento_auditoria IS NULL AND ad.id_auditoria = ".$auditoria->id_auditoria.")");

				foreach ($post->auditoria->detalles as $detalle) {
					$detalle = (object) $detalle;
					foreach ($detalle->estantes as $asignacion) {

						$asignacion = (object) $asignacion;
						$asignacion->encargado = (object) $asignacion->encargado;
						if($asignacion->encargado->id_usuario != 0){
							$auditoria_detalle = AuditoriaDetalle::where('id_auditoria', $auditoria->id_auditoria)
															 ->where('id_estante', $asignacion->id_estante)
															 ->first();
							if($auditoria_detalle == null){
								$auditoria_detalle = new AuditoriaDetalle;
								$auditoria_detalle->id_auditoria = $auditoria->id_auditoria;
								$auditoria_detalle->id_estante = $asignacion->id_estante;
								$auditoria_detalle->id_usuario = $asignacion->encargado->id_usuario;
								$auditoria_detalle->save();
							}
						}
						
					}
				}

				$mensaje = "Cambios guardados exitosamente"; $error = false;
			}else{
				$mensaje = "Ocurrio el siguiente error al registrar la auditoria: ".$auditoria->errors[0];
			}
		}

		return response()->json([
			'mensaje' => $mensaje,
			'error' => $error
		]);
	}
}
