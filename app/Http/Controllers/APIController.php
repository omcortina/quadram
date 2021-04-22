<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\Inventario;
use App\Models\Locacion;
use App\Models\Usuario;
use App\Models\Conteo;
use App\Models\Producto;
use App\Models\AuditoriaDetalle;
use App\Models\SeguimientoAuditoria;
use App\Models\SeguimientoConteo;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
	public function Login(Request $request)
	{
		$post = $request->all();
		$token = null;
		$user = null;
		$status_code = 401;
		$message = "";
		if($post){
			$post = (object) $post;
			$usuario = Usuario::where("nombre_usuario", $post->usuario)
							  ->where("clave", md5($post->clave))
							  ->where("estado", 1)
							  ->first();
			if($usuario){
				$token = csrf_token();
				$user = (object) [
					'id' => $usuario->id_usuario,
					'type' => $usuario->id_dominio_tipo_usuario,
					'type_name' => $usuario->tipo->nombre,
					'first_name' => $usuario->nombres,
					'last_name' => $usuario->apellidos,
					'phone' => $usuario->telefono,
					'username' => $usuario->nombre_usuario,
					'token' => $token
				];
				$status_code = 200; $message = "OK";
				//GUARDAMOS EN EL USUARIO EL TOKEN
				$usuario->api_token = $token;
				$usuario->save();
			}else{
				$message = "Credenciales invalidas";
			}
		}

		return response()->json([
			'message' => $message,
			'user' => $user
		], $status_code);
	}

    public function BuscarProductoPorCodigoBarra(Request $request)
    {
    	$post = $request->all();
		$status_code = 500;
		$message = "";
		$product = null;
		if($post){
			$post = (object) $post;
			if(isset($post->codigo_barras)){
				$product = Producto::where('codigo_barras', $post->codigo_barras)->first();
				if($product){
					$message = "OK"; $status_code = 200;
				}else{
					$message = "Producto no valido";
				}
			}else{
				$message = "Parametro [codigo_barras] perteneciente al CB del producto no esta definido";
			}
		}

		return response()->json([
			'message' => $message,
			'product' => $product
		], $status_code);
    }

    public function Auditorias(Request $request)
    {
    	$post = $request->all();
    	$status_code = 500;
		$message = "";
		$data = null;
		$auditorias = [];
		if($post){
			$post = (object) $post;
			if (isset($post->id)) {
				$usuario = Usuario::find($post->id);
				if($usuario){
					$fecha_actual = date('Y-m-d H:i').":00";
					//PRIMERO BUSCAMOS LAS AUDITORIAS CON RELACION A LOS DETALLES
					$auditorias = DB::select("SELECT DISTINCT(a.id_auditoria) as id_auditoria,
													 a.fecha_inicio,
													 a.fecha_fin,
													 al.nombre as almacen
											  FROM auditoria a
											  LEFT JOIN auditoria_detalle ad USING(id_auditoria)
											  LEFT JOIN inventario i USING(id_inventario)
											  LEFT JOIN almacen al USING(id_almacen)
											  WHERE ad.id_usuario = ".$usuario->id_usuario);

					foreach ($auditorias as $auditoria) {
						$locaciones = DB::select("SELECT DISTINCT(l.id_locacion) as id_locacion,
													 l.nombre
											  FROM auditoria a
											  LEFT JOIN auditoria_detalle ad USING(id_auditoria)
											  LEFT JOIN estante e USING(id_estante)
											  LEFT JOIN locacion l USING(id_locacion)
											  WHERE a.id_auditoria = ".$auditoria->id_auditoria."
											  AND ad.id_usuario = ".$usuario->id_usuario);

						foreach ($locaciones as $locacion) {
							$estantes = DB::select("SELECT DISTINCT(e.id_estante) as id_estante,
													 e.nombre,
													 ad.id_auditoria_detalle
                                                        FROM auditoria a
                                                        LEFT JOIN auditoria_detalle ad USING(id_auditoria)
                                                        LEFT JOIN estante e USING(id_estante)
                                                        WHERE a.id_auditoria = ".$auditoria->id_auditoria."
                                                        AND e.id_locacion = ".$locacion->id_locacion."
                                                        AND ad.id_usuario = ".$usuario->id_usuario);

							foreach ($estantes as $estante) {
								$filas = DB::select("SELECT id_fila_estante as id_fila,
													 nombre
											  FROM fila_estante
											  WHERE id_estante = ".$estante->id_estante);

								//RECORREMOS LAS FILAS PARA BUSCAR SEGUIMIENTOS YA REALIZADOS POR EL USUARIO
								foreach ($filas as $fila) {
									$seguimientos = DB::select("SELECT s.id_seguimiento_auditoria,
                                                     s.id_fila_estante,
													 p.id_producto,
													 p.codigo,
													 p.nombre,
													 p.descripcion
											  FROM seguimiento_auditoria s
											  LEFT JOIN producto p USING(id_producto)
											  WHERE s.id_fila_estante = ".$fila->id_fila."
											  AND s.estado = 1
											  AND s.id_auditoria_detalle = ".$estante->id_auditoria_detalle);
									$fila->productos = $seguimientos;
								}

								$estante->filas = $filas;
							}
							$locacion->estantes = $estantes;
						}

						$auditoria->locaciones = $locaciones;
					}

					$status_code = 200; $message = "OK";
				}else{
					$mensaje = "Usuario invalido";
				}
			}else{
				$message = "Parametro [id] perteneciente al usuario no esta definido";
			}
		}else{
			$mensaje = "Favor verifique los parametros enviados";
		}

		return response()->json([
			'message' => $message,
			'auditorias' => $auditorias
		], $status_code);
    }

    public function LocacionesAuditoriaAuditor(Request $request)
    {
    	$post = $request->all();
    	$status_code = 500;
		$message = "";
		$data = null;
		$locaciones = [];
		if($post){
			$post = (object) $post;
			if (isset($post->auditoria)) {
				$usuario = Usuario::find($post->usuario);
				if($usuario){
						$locaciones = DB::select("SELECT DISTINCT(l.id_locacion) as id_locacion,
													 l.nombre
											  FROM auditoria a
											  LEFT JOIN auditoria_detalle ad USING(id_auditoria)
											  LEFT JOIN estante e USING(id_estante)
											  LEFT JOIN locacion l USING(id_locacion)
											  WHERE a.id_auditoria = ".$post->auditoria."
											  AND ad.id_usuario = ".$usuario->id_usuario);

						foreach ($locaciones as $locacion) {
							$estantes = DB::select("SELECT DISTINCT(e.id_estante) as id_estante,
													 e.nombre,
													 ad.id_auditoria_detalle
											  FROM auditoria a
											  LEFT JOIN auditoria_detalle ad USING(id_auditoria)
											  LEFT JOIN estante e USING(id_estante)
											  WHERE a.id_auditoria = ".$post->auditoria."
											  AND e.id_locacion = ".$locacion->id_locacion."
											  AND ad.id_usuario = ".$usuario->id_usuario);

							foreach ($estantes as $estante) {
								$filas = DB::select("SELECT id_fila_estante as id_fila,
													 nombre
											  FROM fila_estante
											  WHERE id_estante = ".$estante->id_estante);

								//RECORREMOS LAS FILAS PARA BUSCAR SEGUIMIENTOS YA REALIZADOS POR EL USUARIO
								foreach ($filas as $fila) {
									$seguimientos = DB::select("SELECT s.id_seguimiento_auditoria,
													 p.id_producto,
													 p.codigo,
													 p.nombre,
													 p.descripcion
											  FROM seguimiento_auditoria s
											  LEFT JOIN producto p USING(id_producto)
											  WHERE s.id_fila_estante = ".$fila->id_fila."
											  AND s.estado = 1
											  AND s.id_auditoria_detalle = ".$estante->id_auditoria_detalle);
									$fila->productos = $seguimientos;
								}

								$estante->filas = $filas;
							}
							$locacion->estantes = $estantes;
						}
					$status_code = 200; $message = "OK";
				}else{
					$mensaje = "Usuario invalido";
				}
			}else{
				$message = "Parametro [auditoria] no esta definido";
			}
		}else{
			$mensaje = "Favor verifique los parametros enviados";
		}

		return response()->json([
			'message' => $message,
			'locaciones' => $locaciones
		], $status_code);
    }

    public function GuardarSeguimientoAuditoria(Request $request)
    {
    	$post = $request->all();
		$status_code = 500;
		$message = "";
        $producto = new Producto;
		if($post){
			$post = (object) $post;
			if(isset($post->id_auditoria_detalle)){
				if (isset($post->id_estante)) {
					if (isset($post->id_fila)) {
						if (isset($post->id_producto)) {
							if(Producto::find($post->id_producto)){
                                $producto = Producto::find($post->id_producto);
								$seguimiento = SeguimientoAuditoria::where('id_auditoria_detalle', $post->id_auditoria_detalle)
														->where('id_estante', $post->id_estante)
														->where('id_fila_estante', $post->id_fila)
														->where('id_producto', $post->id_producto)
                                                        ->where('estado', 1)
														->first();
								if(is_null($seguimiento)) $seguimiento = new SeguimientoAuditoria;
								$seguimiento->id_auditoria_detalle = $post->id_auditoria_detalle;
								$seguimiento->id_estante = $post->id_estante;
								$seguimiento->id_fila_estante = $post->id_fila;
								$seguimiento->id_producto = $post->id_producto;
								$seguimiento->save();
                                $producto->id_seguimiento_auditoria = $seguimiento->id_seguimiento_auditoria;
								$message = "Producto agregado correctamente"; $status_code = 200;
							}else{
								$message = "El producto no es valido";
							}
						}else{
							$message = "Parametro [id_producto] perteneciente al producto auditado no esta definido";
						}
					}else{
						$message="Parametro [id_fila] perteneciente a la fila donde se audito el producto no esta definido";
					}
				}else{
					$message="Parametro [id_estante] perteneciente al estante auditado no esta definido";
				}
			}else{
				$message = "Parametro [id_auditoria_detalle] perteneciente a la auditoria detalle no esta definido";
			}
		}

		return response()->json([
            'product' => $producto,
			'message' => $message
		], $status_code);
    }

    public function BorrarSeguimientoAuditoria(Request $request)
    {
    	$post = $request->all();
		$status_code = 500;
		$message = "";
        $producto = new Producto;
		if($post){
			$post = (object) $post;
			if(isset($post->id_seguimiento_auditoria)){
				$seguimiento = SeguimientoAuditoria::find($post->id_seguimiento_auditoria);
				if($seguimiento){
					$seguimiento->estado = 0;
                    $producto = $seguimiento->producto;
					$seguimiento->save();
					$message = "OK"; $status_code = 200;
				}else{
					$message = "Seguimiento invalido";
				}
			}else{
				$message = "Parametro [id_seguimiento_auditoria] perteneciente al seguimiento previamente registrado no esta definido";
			}
		}

		return response()->json([
			'message' => $message,
            'product' => $producto
		], $status_code);
    }


    //CONTEO
    public function Conteos(Request $request)
    {
    	$post = $request->all();
    	$status_code = 500;
		$message = "";
		$data = null;
		$conteos = [];
		if($post){
			$post = (object) $post;
			if (isset($post->id)) {
				$usuario = Usuario::find($post->id);
				if($usuario){
					$fecha_actual = date('Y-m-d H:i').":00";

					$this->ActualizarConteosActuales($usuario->id_usuario);

					//PRIMERO BUSCAMOS LOS CONTEOS CON RELACION A LOS DETALLES
					$conteos = DB::select("SELECT DISTINCT(c.id_conteo) as id_conteo,
													 c.fecha_inicio,
													 c.fecha_fin,
													 c.conteo_activo,
													 al.nombre as almacen
											  FROM conteo c
											  LEFT JOIN auditoria a USING(id_auditoria)
											  LEFT JOIN inventario i USING(id_inventario)
											  LEFT JOIN almacen al USING(id_almacen)
											  LEFT JOIN conteo_detalle cd USING(id_conteo)
											  WHERE cd.id_usuario = ".$usuario->id_usuario."
											  AND c.conteo_activo = cd.conteo");

					//ACTUALIZAMOS LOS CONTEOS ACTUALES PARA SABER CUAL ESTA ACTIVO

					foreach ($conteos as $conteo) {

						$locaciones = DB::select("SELECT DISTINCT(l.id_locacion) as id_locacion,
													 l.nombre
											  FROM conteo c
											  LEFT JOIN conteo_detalle cd USING(id_conteo)
											  LEFT JOIN estante e USING(id_estante)
											  LEFT JOIN locacion l USING(id_locacion)
											  WHERE c.id_conteo = ".$conteo->id_conteo."
											  AND cd.conteo = ".$conteo->conteo_activo."
											  AND cd.id_usuario = ".$usuario->id_usuario);

						foreach ($locaciones as $locacion) {
							$estantes = DB::select("SELECT DISTINCT(e.id_estante) as id_estante,
													 e.nombre,
													 cd.id_conteo_detalle,
													 cd.id_auditoria_detalle
                                                        FROM conteo c
                                                        LEFT JOIN conteo_detalle cd USING(id_conteo)
                                                        LEFT JOIN estante e USING(id_estante)
                                                        WHERE c.id_conteo = ".$conteo->id_conteo."
                                                        AND cd.conteo = ".$conteo->conteo_activo."
                                                        AND e.id_locacion = ".$locacion->id_locacion."
                                                        AND cd.id_usuario = ".$usuario->id_usuario);

							foreach ($estantes as $estante) {
								$filas = DB::select("SELECT id_fila_estante as id_fila,
													 nombre
											  FROM fila_estante
											  WHERE id_estante = ".$estante->id_estante);

								//RECORREMOS LAS FILAS PARA BUSCAR SEGUIMIENTOS YA REALIZADOS POR EL AUDITOR
								foreach ($filas as $fila) {
									$seguimientos = DB::select("SELECT s.id_seguimiento_auditoria,
                                                     s.id_fila_estante,
													 p.id_producto,
													 p.codigo,
													 p.nombre,
													 p.descripcion
											  FROM seguimiento_auditoria s
											  LEFT JOIN producto p USING(id_producto)
											  WHERE s.id_fila_estante = ".$fila->id_fila."
											  AND s.estado = 1
											  AND s.id_auditoria_detalle = ".$estante->id_auditoria_detalle);

									foreach ($seguimientos as $seguimiento) {
										$seguimientos_conteo = DB::select("SELECT *
															   FROM seguimiento_conteo sc
															   WHERE sc.id_producto = ".$seguimiento->id_producto."
															   AND sc.estado = 1
															   AND sc.id_conteo_detalle = ".$estante->id_conteo_detalle."
															   limit 1");
										$seguimiento->id_seguimiento_conteo = count($seguimientos_conteo) > 0 ? $seguimientos_conteo[0]->id_seguimiento_conteo : -1;
									}

									$fila->productos = $seguimientos;
								}
								$estante->filas = $filas;
							}
							$locacion->estantes = $estantes;
						}

						$conteo->locaciones = $locaciones;
					}

					$status_code = 200; $message = "OK";
				}else{
					$mensaje = "Usuario invalido";
				}
			}else{
				$message = "Parametro [id] perteneciente al usuario no esta definido";
			}
		}else{
			$mensaje = "Favor verifique los parametros enviados";
		}

		return response()->json([
			'message' => $message,
			'conteos' => $conteos
		], $status_code);
    }

    public function ActualizarConteosActuales($id_usuario)
    {
    	$conteos = DB::select("SELECT DISTINCT(c.id_conteo) as id_conteo
								  FROM conteo c
								  LEFT JOIN conteo_detalle cd USING(id_conteo)
								  WHERE cd.id_usuario = ".$id_usuario);
		foreach ($conteos as $result) {
			$result = (object) $result;
			$conteo = Conteo::find($result->id_conteo);
			$conteo->ActualizarConteoActual();
		}
    }

    public function LocacionesConteoContador(Request $request)
    {
    	$post = $request->all();
    	$status_code = 500;
		$message = "";
		$data = null;
		$locaciones = [];
		if($post){
			$post = (object) $post;
			if (isset($post->usuario)) {
				$usuario = Usuario::find($post->usuario);
				if($usuario){
					$fecha_actual = date('Y-m-d H:i').":00";
					$this->ActualizarConteosActuales($usuario->id_usuario);
					$conteo = Conteo::find($post->conteo);
					$locaciones = DB::select("SELECT DISTINCT(l.id_locacion) as id_locacion,
												 l.nombre
										  FROM conteo c
										  LEFT JOIN conteo_detalle cd USING(id_conteo)
										  LEFT JOIN estante e USING(id_estante)
										  LEFT JOIN locacion l USING(id_locacion)
										  WHERE c.id_conteo = ".$conteo->id_conteo."
										  AND cd.conteo = ".$conteo->conteo_activo."
										  AND cd.id_usuario = ".$usuario->id_usuario);
					foreach ($locaciones as $locacion) {
						$estantes = DB::select("SELECT DISTINCT(e.id_estante) as id_estante,
												 e.nombre,
												 cd.id_conteo_detalle,
												 cd.id_auditoria_detalle
                                                    FROM conteo c
                                                    LEFT JOIN conteo_detalle cd USING(id_conteo)
                                                    LEFT JOIN estante e USING(id_estante)
                                                    WHERE c.id_conteo = ".$conteo->id_conteo."
                                                    AND cd.conteo = ".$conteo->conteo_activo."
                                                    AND e.id_locacion = ".$locacion->id_locacion."
                                                    AND cd.id_usuario = ".$usuario->id_usuario);

						foreach ($estantes as $estante) {
							$filas = DB::select("SELECT id_fila_estante as id_fila,
												 nombre
										  FROM fila_estante
										  WHERE id_estante = ".$estante->id_estante);

							//RECORREMOS LAS FILAS PARA BUSCAR SEGUIMIENTOS YA REALIZADOS POR EL AUDITOR
							foreach ($filas as $fila) {
								$seguimientos = DB::select("SELECT s.id_seguimiento_auditoria,
                                                 s.id_fila_estante,
												 p.id_producto,
												 p.codigo,
												 p.nombre,
												 p.descripcion
										  FROM seguimiento_auditoria s
										  LEFT JOIN producto p USING(id_producto)
										  WHERE s.id_fila_estante = ".$fila->id_fila."
										  AND s.estado = 1
										  AND s.id_auditoria_detalle = ".$estante->id_auditoria_detalle);

								foreach ($seguimientos as $seguimiento) {
									$seguimientos_conteo = DB::select("SELECT *
														   FROM seguimiento_conteo sc
														   WHERE sc.id_producto = ".$seguimiento->id_producto."
														   AND sc.estado = 1
														   AND sc.id_conteo_detalle = ".$estante->id_conteo_detalle."
														   limit 1");
									$seguimiento->id_seguimiento_conteo = count($seguimientos_conteo) > 0 ? $seguimientos_conteo[0]->id_seguimiento_conteo : -1;
								}

								$fila->productos = $seguimientos;
							}
							$estante->filas = $filas;
						}
						$locacion->estantes = $estantes;
					}
					$status_code = 200; $message = "OK";
				}else{
					$mensaje = "Usuario invalido";
				}
			}else{
				$message = "Parametro [id] perteneciente al usuario no esta definido";
			}
		}else{
			$mensaje = "Favor verifique los parametros enviados";
		}

		return response()->json([
			'message' => $message,
			'locaciones' => $locaciones
		], $status_code);
    }

    public function BorrarSeguimientoConteo(Request $request)
    {
    	$post = $request->all();
		$status_code = 500;
		$message = "";
        $producto = new Producto;
		if($post){
			$post = (object) $post;
			if(isset($post->id_seguimiento_conteo)){
				$seguimiento = SeguimientoConteo::find($post->id_seguimiento_conteo);
				if($seguimiento){
					$seguimiento->estado = 0;
					$seguimiento->save();
					$producto = $seguimiento->producto;
					$message = "Producto eliminado"; $status_code = 200;
				}else{
					$message = "Seguimiento invalido";
				}
			}else{
				$message = "Parametro [id_seguimiento_conteo] perteneciente al seguimiento previamente registrado no esta definido";
			}
		}

		return response()->json([
			'message' => $message,
            'product' => $producto
		], $status_code);
    }

    public function GuardarSeguimientoConteo(Request $request)
    {
    	$post = $request->all();
		$status_code = 500;
		$message = "";
        $producto = new Producto;
		if($post){
			$post = (object) $post;
			if(isset($post->id_conteo_detalle)){
				if (isset($post->cantidad)) {
					if (isset($post->fecha_vencimiento)) {
						if (isset($post->lote)) {
							if (isset($post->id_producto)) {
								if(Producto::find($post->id_producto)){
	                                $producto = Producto::find($post->id_producto);
									$seguimiento = SeguimientoConteo::where('id_conteo_detalle', $post->id_conteo_detalle)
															->where('id_producto', $post->id_producto)
	                                                        ->where('estado', 1)
															->first();
									if(is_null($seguimiento)) $seguimiento = new SeguimientoConteo;
									$seguimiento->id_conteo_detalle = $post->id_conteo_detalle;
									$seguimiento->id_producto = $post->id_producto;
									$seguimiento->cantidad = $post->cantidad;
									$seguimiento->fecha_vencimiento = $post->fecha_vencimiento;
									$seguimiento->lote = $post->lote;
									$seguimiento->save();
	                                $producto->id_seguimiento_conteo = $seguimiento->id_seguimiento_conteo;
									$message = "Producto agregado correctamente"; $status_code = 200;
								}else{
									$message = "El producto no es valido";
								}
							}else{
								$message = "Parametro [id_producto] perteneciente al producto contado no esta definido";
							}
						}else{
							$message = "Parametro [lote] perteneciente al del producto contado no esta definido";
						}
					}else{
						$message = "Parametro [fecha_vencimiento] perteneciente a la fecha de vencimiento del producto contado no esta definido";
					}
				}else{
					$message = "Parametro [cantidad] perteneciente a la cantidad contada no esta definida";
				}
			}else{
				$message = "Parametro [id_conteo_detalle] perteneciente al conteo detalle no esta definido";
			}
		}

		return response()->json([
            'product' => $producto,
			'message' => $message
		], $status_code);
    }

}
