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

	public function RefreshToken($token)
    {	
		$status_code = 200;
		$new_token = null;
    	$usuario = Usuario::where('api_token', $token)->first();
    	if($usuario){
    		$new_token = csrf_token();
    		$usuario->api_token = $new_token;
			$usuario->save();
			$status_code = 200;
    	}else{
    		$message = "Token expirado no valido";
    	}
    	return response()->json([
			'_token' => $new_token,
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
					$message = "No se encontro un producto valido";
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

    public function AuditoriasAuditor(Request $request)
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
					
					//PRIMERO BUSCAMOS LAS AUDITORIAS CON RELACION A LOS DETALLES
					$auditorias = DB::select("SELECT DISTINCT(a.id_auditoria) as id_auditoria, 
													 a.fecha_inicio, 
													 a.fecha_fin 
											  FROM auditoria a 
											  LEFT JOIN auditoria_detalle ad USING(id_auditoria) 
											  WHERE ad.id_usuario = ".$usuario->id_usuario);

					foreach ($auditorias as $auditoria) {
						$locaciones = DB::select("SELECT DISTINCT(l.id_locacion) as id_locacion, 
													 l.nombre
											  FROM auditoria a 
											  LEFT JOIN auditoria_detalle ad USING(id_auditoria) 
											  LEFT JOIN estante e USING(id_estante) 
											  LEFT JOIN locacion l USING(id_locacion) 
											  WHERE ad.id_usuario = ".$usuario->id_usuario);

						foreach ($locaciones as $locacion) {
							$estantes = DB::select("SELECT DISTINCT(e.id_estante) as id_estante, 
													 e.nombre,
													 ad.id_auditoria_detalle
											  FROM auditoria a 
											  LEFT JOIN auditoria_detalle ad USING(id_auditoria) 
											  LEFT JOIN estante e USING(id_estante) 
											  WHERE e.id_locacion = ".$locacion->id_locacion."
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

    public function GuardarSeguimientoAuditoria(Request $request)
    {
    	$post = $request->all();
		$status_code = 500;
		$message = "";
		if($post){
			$post = (object) $post;
			if(isset($post->id_auditoria_detalle)){
				if (isset($post->id_estante)) {
					if (isset($post->id_fila)) {
						if (isset($post->id_producto)) {
							if(Producto::find($post->id_producto)){
								$seguimiento = new SeguimientoAuditoria;
								$seguimiento->id_auditoria_detalle = $post->id_auditoria_detalle;
								$seguimiento->id_estante = $post->id_estante;
								$seguimiento->id_fila_estante = $post->id_fila;
								$seguimiento->id_producto = $post->id_producto;
								$seguimiento->save();
								$message = "OK"; $status_code = 200;
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
			'message' => $message
		], $status_code);
    }

    public function BorrarSeguimientoAuditoria(Request $request)
    {
    	$post = $request->all();
		$status_code = 500;
		$message = "";
		if($post){
			$post = (object) $post;
			if(isset($post->id_seguimiento_auditoria)){
				$seguimiento = SeguimientoAuditoria::find($post->id_seguimiento_auditoria);
				if($seguimiento){
					$seguimiento->estado = 0;
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
			'message' => $message
		], $status_code);
    }
}
