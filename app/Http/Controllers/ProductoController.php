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
		$productos = Producto::orderByDesc('codigo')->paginate(15);
		return view('producto.listado', compact(['productos']));
	}

	public function ImportarExcel(Request $request)
	{
		set_time_limit (0);
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

	public function Gestion(Request $request)
	{
		$post = $request->all();
    	$producto = new Producto;
    	$producto->estado = 1;
    	$mensaje = null;
        if($post) {
            $post = (object) $post;
            if(isset($post->producto)){
                $producto = Producto::find($post->producto);
                if($producto == null){ echo "Acceso denegado"; die; }
            }
        }
    	if($request->except(['producto'])){
            $post = (object) $post;
            $producto->fill($request->except(['_token', 'producto']));
            if($producto->save()){
                $request->session()->flash('message', 'Información guardada exitosamente');
                return redirect()->route("producto/listado");
            }else{
            	$mensaje = "ocurrio el siguiente error: ".$producto->errors[0];
            }
    	}
    	return view("producto.gestion", compact([
    		'producto', 'mensaje'
    	]));
	}
}
