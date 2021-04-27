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
use App\Models\ConteoDetalle;
use App\Models\SeguimientoAuditoria;
use App\Models\SeguimientoConteo;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
	public function Login(Request $request)
	{
        set_time_limit(9999);
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


    //AUDITORIAS
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
											  WHERE '$fecha_actual' BETWEEN a.fecha_inicio AND a.fecha_fin
											  AND ad.id_usuario = ".$usuario->id_usuario);

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

									foreach ($seguimientos as $seguimiento) {
									$seguimientos_auditoria = DB::select("SELECT *
														   FROM seguimiento_auditoria sa
														   WHERE sa.id_producto = ".$seguimiento->id_producto."
														   AND sa.estado = 1
														   AND sa.id_auditoria_detalle = ".$estante->id_auditoria_detalle."
														   limit 1");
										$seguimiento->id_seguimiento_auditoria = count($seguimientos_auditoria) > 0 ? $seguimientos_auditoria[0]->id_seguimiento_auditoria : -1;
										$seguimiento->seguimiento = count($seguimientos_auditoria) > 0 ? $seguimientos_auditoria[0] : (object)[];
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

    public function HistorialAuditorias(Request $request)
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
											  WHERE a.fecha_fin < '$fecha_actual'
											  AND ad.id_usuario = ".$usuario->id_usuario);

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
											  AND '$fecha_actual' BETWEEN c.fecha_inicio AND c.fecha_fin
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
													 cd.finalizo,
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
									if ($conteo->conteo_activo != 3) {
										$seguimientos = DB::select("SELECT DISTINCT(p.id_producto) as id_producto,
													 s.id_seguimiento_auditoria,
                                                     s.id_fila_estante,													 
													 p.codigo,
													 p.codigo_barras,
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
																   AND sc.id_fila_estante = ".$fila->id_fila."
																   AND sc.id_conteo_detalle = ".$estante->id_conteo_detalle);
											$seguimiento->seguimientos = $seguimientos_conteo;
										}
									}else{

										$seguimientos = $this->ProductosConteo3($conteo, $fila);
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

    public function ProductosConteo3($conteo, $fila)
    {
    	$seguimientos = [];
		$seguimientos_conteo_1 = "SELECT DISTINCT(p.id_producto) as id_producto,
                                sc.id_fila_estante,													 
								p.codigo,
								p.codigo_barras,
								p.nombre,
								p.descripcion,
								sc.cantidad,
								sc.fecha_vencimiento,
								sc.lote,
								sc.id_seguimiento_conteo
							  	FROM seguimiento_conteo sc
							  	LEFT JOIN producto p USING(id_producto)
							  	LEFT JOIN conteo_detalle cd USING(id_conteo_detalle)
							  	WHERE cd.id_conteo = $conteo->id_conteo
							  	AND cd.conteo = 1
							  	AND sc.valido = 1
							  	AND sc.id_fila_estante = $fila->id_fila
							  	AND sc.estado = 1";
		

		$seguimientos_conteo_2 = "SELECT DISTINCT(p.id_producto) as id_producto,
                                sc.id_fila_estante,													 
								p.codigo,
								p.codigo_barras,
								p.nombre,
								p.descripcion,
								sc.cantidad,
								sc.fecha_vencimiento,
								sc.lote,
								sc.id_seguimiento_conteo 
								FROM seguimiento_conteo sc
								LEFT JOIN producto p USING(id_producto)
								LEFT JOIN conteo_detalle cd USING(id_conteo_detalle)
								WHERE cd.id_conteo = $conteo->id_conteo
								AND cd.conteo = 2
								AND sc.valido = 1
								AND sc.id_fila_estante = $fila->id_fila
								AND sc.estado = 1";

		//RECORREMOS TODOS LOS PRODUCTOS DEL PRIMER CONTEO Y MIRAMOS SI ESTAN EN IGUAL CANTIDAD EN EL SEGUNDO CONTEO
    	foreach (DB::select($seguimientos_conteo_1) as $conteo_1) { $conteo_1 = (object) $conteo_1;
			$encontro = false;
			foreach (DB::select($seguimientos_conteo_2) as $conteo_2) { $conteo_2 = (object) $conteo_2;
				if ($conteo_1->id_producto 		 == $conteo_2->id_producto and
					$conteo_1->lote 	   		 == $conteo_2->lote and
					$conteo_1->fecha_vencimiento == $conteo_2->fecha_vencimiento
				) {
					//ESTA VARIABLE PERMITIRA SABER SI EL PRODUCTO EXISTE EN AMBOS CONTEOS SI SOLO EXISTE EN EL PRIMER CONTEO DEBE SER VERIFICADO EN EL TERCER CONTEO
					$encontro = true;
					if ($conteo_1->cantidad != $conteo_2->cantidad) {
						$producto['id_producto'] = $conteo_2->id_producto;												 
						$producto['id_seguimiento_conteo'] = $conteo_2->id_seguimiento_conteo;												 
						$producto['id_fila_estante'] = $fila->id_fila;												 
						$producto['codigo'] = $conteo_2->codigo;
						$producto['codigo_barras'] = $conteo_2->codigo_barras;
						$producto['nombre'] = $conteo_2->nombre;
						$producto['descripcion'] = $conteo_2->descripcion;
						$producto['cantidad_1'] = $conteo_1->cantidad;
						$producto['cantidad_2'] = $conteo_2->cantidad;
						$producto['diferencia'] = $conteo_2->cantidad > $conteo_1->cantidad ? $conteo_2->cantidad - $conteo_1->cantidad : $conteo_1->cantidad - $conteo_2->cantidad;
						$producto['fecha_vencimiento'] = $conteo_2->fecha_vencimiento;
						$producto['lote'] = $conteo_2->lote;
						$seguimientos_conteo = DB::select("SELECT *
								   FROM seguimiento_conteo sc
								   LEFT JOIN conteo_detalle cd USING(id_conteo_detalle)
								   WHERE sc.id_producto = ".$conteo_2->id_producto."
								   AND sc.estado = 1
								   AND cd.conteo = 3
								   AND sc.lote = '".$producto['lote']."'
							   	   AND sc.fecha_vencimiento = '".$producto['fecha_vencimiento']."'
								   AND sc.id_fila_estante = ".$fila->id_fila."
								   AND cd.id_conteo = ".$conteo->id_conteo);
						$producto['seguimientos'] = $seguimientos_conteo;
						$seguimientos[] = (object) $producto;
					}
				}
			}
			if(!$encontro){
				$producto['id_producto'] = $conteo_1->id_producto;	
				$producto['id_seguimiento_conteo'] = $conteo_1->id_seguimiento_conteo;	
				$producto['id_fila_estante'] = $fila->id_fila;												 
				$producto['codigo'] = $conteo_1->codigo;
				$producto['codigo_barras'] = $conteo_1->codigo_barras;
				$producto['nombre'] = $conteo_1->nombre;
				$producto['descripcion'] = $conteo_1->descripcion;
				$producto['cantidad'] = $conteo_1->cantidad;
				$producto['cantidad_1'] = $conteo_1->cantidad;
				$producto['cantidad_2'] = 0;
				$producto['diferencia'] = $conteo_1->cantidad;
				$producto['fecha_vencimiento'] = $conteo_1->fecha_vencimiento;
				$producto['lote'] = $conteo_1->lote;
				$seguimientos_conteo = DB::select("SELECT *
							   FROM seguimiento_conteo sc
							   LEFT JOIN conteo_detalle cd USING(id_conteo_detalle)
							   LEFT JOIN producto p USING(id_producto)
							   WHERE sc.id_producto = ".$conteo_1->id_producto."
							   AND sc.estado = 1
							   AND cd.conteo = 3
							   AND sc.lote = '".$producto['lote']."'
							   AND sc.fecha_vencimiento = '".$producto['fecha_vencimiento']."'
							   AND sc.id_fila_estante = ".$fila->id_fila."
							   AND cd.id_conteo = ".$conteo->id_conteo);
				$producto['seguimientos'] = $seguimientos_conteo;
				$seguimientos[] = (object) $producto;
			}
		}

		//AHORA REVISAMOS DEL 2 CONTEO CUAL SEGUIMIENTO NO ESTA EN EL PRIMERO
    	foreach (DB::select($seguimientos_conteo_2) as $conteo_2) { $conteo_2 = (object) $conteo_2;
			//ESTA VARIABLE PERMITIRA SABER SI EL PRODUCTO EXISTE EN AMBOS CONTEOS SI SOLO EXISTE EN EL SEGUNDO CONTEO DEBE SER VERIFICADO EN EL TERCER CONTEO
			$encontro = false;
			foreach (DB::select($seguimientos_conteo_1) as $conteo_1) { $conteo_1 = (object) $conteo_1;
				if ($conteo_1->id_producto 		 == $conteo_2->id_producto and
					$conteo_1->lote 	   		 == $conteo_2->lote and
					$conteo_1->fecha_vencimiento == $conteo_2->fecha_vencimiento
				) {
					$encontro = true;
				}
			}
			if(!$encontro){
				$producto['id_producto'] = $conteo_2->id_producto;
				$producto['id_seguimiento_conteo'] = $conteo_2->id_seguimiento_conteo;
				$producto['id_fila_estante'] = $fila->id_fila;												 
				$producto['codigo'] = $conteo_2->codigo;
				$producto['codigo_barras'] = $conteo_2->codigo_barras;
				$producto['nombre'] = $conteo_2->nombre;
				$producto['descripcion'] = $conteo_2->descripcion;
				$producto['cantidad'] = $conteo_2->cantidad;
				$producto['cantidad_1'] = 0;
				$producto['cantidad_2'] = $conteo_2->cantidad;
				$producto['diferencia'] = $conteo_2->cantidad;
				$producto['fecha_vencimiento'] = $conteo_2->fecha_vencimiento;
				$producto['lote'] = $conteo_2->lote;
				$seguimientos_conteo = DB::select("SELECT *
							   FROM seguimiento_conteo sc
							   LEFT JOIN conteo_detalle cd USING(id_conteo_detalle)
							   LEFT JOIN producto p USING(id_producto)
							   WHERE sc.id_producto = ".$conteo_2->id_producto."
							   AND sc.estado = 1
							   AND cd.conteo = 3
							   AND sc.lote = '".$producto['lote']."'
							   AND sc.fecha_vencimiento = '".$producto['fecha_vencimiento']."'
							   AND sc.id_fila_estante = ".$fila->id_fila."
							   AND cd.id_conteo = ".$conteo->id_conteo);
				$producto['seguimientos'] = $seguimientos_conteo;
				$seguimientos[] = (object) $producto;
			}
		}

		return $seguimientos;
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
					$num_conteo = isset($post->num_conteo) ? $post->num_conteo : $conteo->conteo_activo;
					$locaciones = DB::select("SELECT DISTINCT(l.id_locacion) as id_locacion,
												 l.nombre
										  FROM conteo c
										  LEFT JOIN conteo_detalle cd USING(id_conteo)
										  LEFT JOIN estante e USING(id_estante)
										  LEFT JOIN locacion l USING(id_locacion)
										  WHERE c.id_conteo = ".$conteo->id_conteo."
										  AND cd.conteo = ".$num_conteo."
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
												 p.descripcion,
												 '' as seguimiento
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
									$seguimiento->seguimiento = count($seguimientos_conteo) > 0 ? $seguimientos_conteo[0] : (object)[];
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
					$producto->seguimientos = DB::select("SELECT *
													   FROM seguimiento_conteo sc
													   WHERE sc.id_producto = ".$producto->id_producto."
													   AND sc.estado = 1
													   AND sc.id_conteo_detalle = ".$seguimiento->id_conteo_detalle);
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
				if (isset($post->id_fila)) {
					if (isset($post->cantidad)) {
						if (isset($post->fecha_vencimiento)) {
							if (isset($post->lote)) {
								if (isset($post->id_producto)) {
									if(Producto::find($post->id_producto)){
		                                $producto = Producto::find($post->id_producto);
										$seguimiento = SeguimientoConteo::where('id_conteo_detalle', $post->id_conteo_detalle)
																->where('id_producto', $post->id_producto)
																->where('lote', $post->lote)
																->where('fecha_vencimiento', $post->fecha_vencimiento)
																->where('id_fila_estante', $post->id_fila)
		                                                        ->where('estado', 1)
																->first();
										if(is_null($seguimiento)) $seguimiento = new SeguimientoConteo;
										$seguimiento->id_conteo_detalle = $post->id_conteo_detalle;
										$seguimiento->id_producto = $post->id_producto;
										$seguimiento->cantidad = $seguimiento->cantidad + $post->cantidad;
										$seguimiento->fecha_vencimiento = $post->fecha_vencimiento;
										$seguimiento->lote = $post->lote;
										$seguimiento->id_fila_estante = $post->id_fila;
										$seguimiento->save();
		                                $producto->id_seguimiento_conteo = $seguimiento->id_seguimiento_conteo;
		                                $producto->seguimientos = DB::select("SELECT *
																		      FROM seguimiento_conteo sc
																		      WHERE sc.id_producto = ".$producto->id_producto."
																		      AND sc.estado = 1
																		      AND sc.id_conteo_detalle = ".$seguimiento->id_conteo_detalle);


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
					$message = "Parametro [id_fila] perteneciente a la fila donde se encuentra el producto contado no esta definido";
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

    public function HistorialConteos(Request $request)
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
													 cd.conteo as num_conteo,
													 c.fecha_inicio,
													 c.fecha_fin,
													 c.conteo_activo,
													 al.nombre as almacen
											  FROM conteo c
											  LEFT JOIN auditoria a USING(id_auditoria)
											  LEFT JOIN inventario i USING(id_inventario)
											  LEFT JOIN almacen al USING(id_almacen)
											  LEFT JOIN conteo_detalle cd USING(id_conteo)
											  WHERE c.fecha_fin < '$fecha_actual'
											  AND c.conteo_activo = cd.conteo
											  AND cd.id_usuario = ".$usuario->id_usuario);

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
													 cd.finalizo
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
										$seguimiento->seguimiento = count($seguimientos_conteo) > 0 ? $seguimientos_conteo[0] : (object)[];
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

    public function FinalizarConteo(Request $request)
    {
    	$post = $request->all();
		$status_code = 500;
		$message = "";
        $producto = new Producto;
		if($post){
			$post = (object) $post;
			if(isset($post->id_conteo_detalle)){
				$detalle = ConteoDetalle::find($post->id_conteo_detalle);
				if($detalle){
					//VALIDAMOS QUE SE CUENTEN TODOS LOS PRODUCTOS ASIGNADOS A LA AUDITORIA
					$seguimientos_auditoria = DB::select("SELECT DISTINCT(s.id_producto) as id_producto
														  FROM seguimiento_auditoria s
														  WHERE s.estado = 1
														  AND s.id_auditoria_detalle = ".$detalle->id_auditoria_detalle);

					$seguimientos_conteo    = DB::select("SELECT DISTINCT(sc.id_producto) as id_producto
														  FROM seguimiento_conteo sc
														  WHERE sc.estado = 1
														  AND sc.id_conteo_detalle = ".$detalle->id_conteo_detalle);
					if(count($seguimientos_conteo) >= count($seguimientos_auditoria)){
						$detalle->finalizo = 1;
						$detalle->save();
						$this->ActualizarConteosActuales($detalle->id_usuario);
						$status_code = 200; $message = "Finalización exitosa";
					}else{
						$message = "No se puede finalizar el conteo de este estante debido a que no se han contado todos los productos previamente auditados";
					}
				}else{
					$message = "Conteo detalle invalido";
				}
			}else{
				$message = "Parametro [id_conteo_detalle] perteneciente al conteo previamente registrado no esta definido";
			}
		}
		return response()->json([
			'message' => $message
		], $status_code);
    }

    public function InvalidarSeguimiento(Request $request)
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
					$seguimiento->valido = 0;
					$seguimiento->save();
					$producto = $seguimiento->producto;
					$producto->id_seguimiento_conteo = $seguimiento->id_seguimiento_conteo;
					$producto->seguimientos = DB::select("SELECT *
													   FROM seguimiento_conteo sc
													   WHERE sc.id_producto = ".$producto->id_producto."
													   AND sc.estado = 1
													   AND sc.id_conteo_detalle = ".$seguimiento->id_conteo_detalle);
					$message = "Seguimiento invalidado exitosamente"; $status_code = 200;
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

}
