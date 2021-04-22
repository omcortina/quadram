<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\Conteo;
use App\Models\Inventario;
use App\Models\Locacion;
use App\Models\Usuario;
use App\Models\AuditoriaDetalle;
use App\Models\ConteoDetalle;
use App\Models\SeguimientoAuditoria;
use App\Models\SeguimientoConteo;
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
			 $conteo->fecha_inicio = $auditoria->fecha_fin;
			 $conteo->fecha_fin = str_replace(" ", "T", $inventario->fecha_fin);
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
						$detalles_conteos = ConteoDetalle::all()
												->where('id_auditoria_detalle', $detalle_encargado->id_auditoria_detalle);
						foreach ($detalles_conteos as $detalle_conteo) {
							//SE VALIDA SI TIENE SEGUIMIENTOS EL USUARIO
							$seguimientos_conteo = SeguimientoConteo::all()
										->where('estado', 1)
										->where('id_conteo_detalle', $detalle_conteo->id_conteo_detalle);

							$encargados_conteo[] = (object)[
								'id_usuario' => $detalle_conteo->id_usuario, 
								'nombre' => $detalle_conteo->usuario->nombre_completo(),
								'conteo' => $detalle_conteo->conteo,
								'tiene_seguimientos' => count($seguimientos_conteo) > 0 ? true : false,
							];

							
						}	
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
			DB::beginTransaction();
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

				//AHORA GUARDAMOS EL CONTEO
				$conteo = $post->conteo->id_conteo == null ? new Conteo : Conteo::find($post->conteo->id_conteo);
				$conteo->id_auditoria = $auditoria->id_auditoria;
				$conteo->fecha_inicio = date('Y-m-d H:i:s', strtotime(str_replace("T", " ", $post->conteo->fecha_inicio)));
				$conteo->fecha_fin = date('Y-m-d H:i:s', strtotime(str_replace("T", " ", $post->conteo->fecha_fin)));
				$conteo->id_usuario = session('id_usuario');
				$conteo->estado = $post->conteo->estado;
				if($conteo->save()){
					//ELIMINAMOS TODOS LOS DETALLES ANTIGUOS QUE NO TENGAN UN SEGUIMIENTO
					DB::statement("DELETE FROM conteo_detalle WHERE id_conteo_detalle in (SELECT cd.id_auditoria_detalle FROM conteo_detalle cd LEFT JOIN seguimiento_conteo s USING(id_conteo_detalle) WHERE s.id_seguimiento_conteo IS NULL AND cd.id_conteo = ".$conteo->id_conteo.")");
					foreach ($post->conteo->detalles as $detalle) {
						$detalle = (object) $detalle;
						foreach ($detalle->estantes as $asignacion) {
							$asignacion = (object) $asignacion;
							if (isset($asignacion->encargados)) {
								foreach ($asignacion->encargados as $encargado) {
									$encargado = (object) $encargado;
									if($encargado->id_usuario != 0){
										$auditoria_detalle = AuditoriaDetalle::where('id_auditoria', $auditoria->id_auditoria)
																		 ->where('id_estante', $asignacion->id_estante)
																		 ->first();
										if($auditoria_detalle != null){
											$conteo_detalle = ConteoDetalle::where('id_conteo', $conteo->id_conteo)
															->where('id_auditoria_detalle', $auditoria_detalle->id_auditoria_detalle)
															->where('id_estante', $asignacion->id_estante)
															->where('conteo', $encargado->conteo)
															->first();
											if ($conteo_detalle == null) {
												$conteo_detalle = new ConteoDetalle;
												$conteo_detalle->id_conteo = $conteo->id_conteo;
												$conteo_detalle->id_auditoria_detalle = $auditoria_detalle->id_auditoria_detalle;
												$conteo_detalle->id_estante = $asignacion->id_estante;
												$conteo_detalle->id_usuario = $encargado->id_usuario;
												$conteo_detalle->conteo = $encargado->conteo;
												$conteo_detalle->save();
											}
										}
									}
								}
							}
						}
					}
					$conteo->ActualizarConteoActual();
					DB::commit();
					$mensaje = "Cambios guardados exitosamente"; $error = false;
				}else{
					DB::rollBack();
					$mensaje = "Ocurrio el siguiente error al registrar el conteo: ".$conteo->errors[0];
				}
			}else{
				DB::rollBack();
				$mensaje = "Ocurrio el siguiente error al registrar la auditoria: ".$auditoria->errors[0];
			}
		}else{
			$mensaje = "Informacion no valida";
		}

		return response()->json([
			'mensaje' => $mensaje,
			'error' => $error
		]);
	}
}
