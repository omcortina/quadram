<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dominio;
use App\Models\Inventario;
use App\Models\Almacen;
use App\Models\Auditoria;
use App\Models\Conteo;
use App\Exports\InformeGeneralInventario;
use App\Exports\ExportInformeGeneralInventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InventarioController extends Controller
{
    public function Gestion()
	{
		$almacenes = Almacen::all()->where('estado', 1);
		return view('inventario.gestion', compact(['almacenes']));
	}

	public function ObtenerListado()
	{
		$inventarios = Inventario::orderByDesc('fecha_inicio')->with('usuario')->with('almacen')->get();
		$fecha_actual = date('Y-m-d H:i:s');
		foreach ($inventarios as $inventario) {
			$auditoria = Auditoria::where('id_inventario', $inventario->id_inventario)
								  ->where('estado', 1)
								  ->first();
			$estado_auditoria = "No creada";
			$estado_conteo = "No creado";
			$conteo_actual = "No definido";
			$inventario->conteo_tiene_seguimientos = false;
			$inventario->id_auditoria = null;
			$inventario->id_conteo = null;
			if ($auditoria) {
				$estado_auditoria = $auditoria->estado_actual();
				//AHORA VALIDAMOS EL CONTEO
				$conteo = Conteo::where('id_auditoria', $auditoria->id_auditoria)
								  ->where('estado', 1)
								  ->first();
				if ($conteo) {
					$estado_conteo = $conteo->estado_actual();
					$conteo_actual = $conteo->texto_conteo_actual();
				}

				$inventario->conteo_tiene_seguimientos = $conteo->tiene_seguimientos();
				$inventario->id_auditoria = $auditoria->id_auditoria;
				$inventario->id_conteo = $conteo->id_conteo;
			}

			$inventario->estado_auditoria = $estado_auditoria;	
			$inventario->estado_conteo = $estado_conteo;	
			$inventario->conteo_actual = $conteo_actual;
			
		}
		return response()->json([
			'inventarios' => $inventarios
		]);
	}

	public function Guardar(Request $request)
	{
		$post = $request->all();
		$error = true;
		$mensaje = "";
		if($post){
			$post = (object) $post; $post->inventario = (object) $post->inventario;
			$inventario = $post->inventario->id_inventario == null ? new Inventario : Inventario::find($post->inventario->id_inventario);
			$inventario->id_usuario = session('id_usuario');
			$inventario->id_almacen = $post->inventario->id_almacen;
			$inventario->estado = $post->inventario->estado;
			$inventario->fecha_inicio = date('Y-m-d H:i:s', strtotime(str_replace("T", " ", $post->inventario->fecha_inicio)));
			$inventario->fecha_fin = date('Y-m-d H:i:s', strtotime(str_replace("T", " ", $post->inventario->fecha_fin)));
			if(!$inventario->save()){
				$mensaje = "Ocurrio el siguiente error: ".$inventario->errors[0];
			}else{
				$mensaje = "Inventario guardado exitosamente"; $error = false;
			}
		}

		return response()->json([
			'mensaje' => $mensaje,
			'error' => $error
		]);
	}

	public function InformeGeneral($id_inventario)
	{
		return Excel::download(new InformeGeneralInventario($id_inventario), 'Inventario #'.$id_inventario.'.xlsx');
	}

	public function ExportarInformeGeneral($id_inventario)
	{
		return Excel::download(new ExportInformeGeneralInventario($id_inventario), 'Exportación Inventario #'.$id_inventario.'.xlsx');
	}
}
