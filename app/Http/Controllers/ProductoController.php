<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Dominio;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function Listado()
	{
		$productos = Producto::orderByDesc('codigo')->get();
		return view('producto.listado', compact(['productos']));
	}

	public function ImportarExcel(Request $request)
	{
		$file = $request->file('file');
		$mensaje = "";
		$error = true;
        $ruta = "excel";
        $results = [];
        if ($file) {
	        try {
	        	 $cont_antes = Producto::all()->count();
	             $excel_result = Excel::import(new ProductsImport, request()->file('file'));
	             $cont_despues = Producto::all()->count();
	             $cont = $cont_despues - $cont_antes;
	             $error = false;
	             $mensaje = "Proceso exitoso, se han importado $cont productos nuevos.";
	        }catch (Exception $e) {
	        	$mensaje = "Ocurrio el siguiente error: ".$e->getMessage();
	        }     
	          
        }
        return view('producto.cargar_archivo', compact(['mensaje', 'error']));
	}
}
