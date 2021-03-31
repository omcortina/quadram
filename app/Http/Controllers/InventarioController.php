<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dominio;
use App\Models\Inventario;
use App\Models\Almacen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
}
