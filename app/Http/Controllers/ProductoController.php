<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Dominio;

class ProductoController extends Controller
{
    public function Listado()
	{
		$productos = Producto::all();
		return view('producto.listado', compact(['productos']));
	}
}
