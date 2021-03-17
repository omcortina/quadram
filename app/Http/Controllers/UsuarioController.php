<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Dominio;
class UsuarioController extends Controller
{

	public function Formulario(Request $request)
	{
		$data = $request->all();
		$usuario = new Usuario;
		$tipos_usuario = Dominio::all()->where('id_padre', 1)->where('estado', 1);
		$tipos_documento = Dominio::all()->where('id_padre', 6)->where('estado', 1);
		$errors = [];
		if(isset($data['id'])) $usuario = Usuario::find($data['id']);
		if($request->except(['id'])){
			$data = (object) $data;
			$usuario->fill($request->except(['_token']));
			if($usuario->save()){
				return redirect()->route('usuario/listado');
			}else{
				$errors = $usuario->errors;
			}
		}

		return view('usuario.formulario', compact(['usuario', 'tipos_usuario', 'tipos_documento']));
	}

	public function Listado()
	{
		$usuarios = Usuario::all();
		return view('usuario.listado', compact(['usuarios']));
	}

    
}
