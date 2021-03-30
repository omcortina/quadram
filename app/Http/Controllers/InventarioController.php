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
    public function Listado()
	{
		$almacenes = Almacen::all()->where('estado', 1);
		return view('inventario.listado', compact(['almacenes']));
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
		
	}
}
