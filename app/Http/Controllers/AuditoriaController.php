<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\Inventario;

class AuditoriaController extends Controller
{
    public function Gestion($id_inventario)
	{
		$inventario = Inventario::find($id_inventario);
		return view('auditoria.gestion', compact(['inventario']));
	}
}
