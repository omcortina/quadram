<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Dominio;

class UsuarioController extends Controller
{	
	
	public function Formulario(Request $request){
		$data = $request->all();
		$usuario = new Usuario;
		$tipos_usuario = Dominio::all()->where('id_padre', 1)->where('estado', 1);
		$tipos_documento = Dominio::all()->where('id_padre', 6)->where('estado', 1);
		$errors = [];
		$clave = $request['documento'];
		if(isset($data['id'])) $usuario = Usuario::find($data['id']);
		if($request->except(['id'])){
			$data = (object) $data;
			$usuario->fill($request->except(['_token']));
			$usuario->clave = md5($clave);
			if($usuario->save()){
				return redirect()->route('usuario/listado');
			}else{
				$errors = $usuario->errors;
			}
		}

		return view('usuario.formulario', compact(['usuario', 'tipos_usuario', 'tipos_documento']));
	}

	public function Validar(Request $request){
		$data = $request->all();
		$mensaje = "";
		if($data){
			$data = (object) $data;
			$usuario = Usuario::where("nombre_usuario", $data->nombre_usuario)
					  ->where("clave", md5($data->clave))
					  ->where("estado", 1)
					  ->first();
			if($usuario){
				$redirect = "";
				if($usuario->tipo->id_dominio == 2) $redirect = 'inventario/gestion';
				if($usuario->tipo->id_dominio == 11) $redirect = 'auditoria/listado/todos';
				session([
					'id_usuario' => $usuario->id_usuario,
					'tipo_usuario' => $usuario->tipo->id_dominio
				]);
				return redirect()->route('inventario/gestion');
			}else{
				$mensaje = "Credenciales invalidas";
        		session()->flash('mensaje_login', $mensaje);
        		return view("login.login");
			}
		}
	}

	public function CerrarSesion(Request $request){
        $request->session()->flush();
        return redirect("/");
    }

	public function Listado(){
		$usuarios = Usuario::all()->where("id_usuario", "<>", session('id_usuario'));
		return view('usuario.listado', compact(['usuarios']));
	}


	public function Perfil(Request $request, $id_usuario){
		$usuario = Usuario::find($id_usuario);
		$tipos_usuario = Dominio::all()->where('id_padre', 1)->where('estado', 1);
		$tipos_documento = Dominio::all()->where('id_padre', 6)->where('estado', 1);
		$errors = [];
		$data = $request->all();
		$mensaje = "";
		if($data){
			$data = (object) $data;
			$usuario->fill($request->except(['_token']));
			if(isset($data->url_imagen)){
				if($data->url_imagen != null){
                    $nombre_imagen = $data->url_imagen->getClientOriginalName();
                    $ruta = "images/users";
                    Storage::disk('public')->put($ruta."/".$nombre_imagen,  \File::get($data->url_imagen));
                    $usuario->url_imagen = $nombre_imagen;
                }
			}

			if($usuario->update()){
				$mensaje = "Perfil actualizado.";
        		session()->flash('mensaje_perfil', $mensaje);
				return redirect()->route('usuario/perfil', $usuario->id_usuario);
			}else{
				$errors = $usuario->errors;
			}
		}
		return view('usuario.perfil', compact(['usuario', 'tipos_usuario', 'tipos_documento']));
	}

	public function CambiarPassword(Request $request){
		$data = $request->all();
		$mensaje = "";
		$error = true;
		if($data){
			$data = (object) $data;
			$usuario = Usuario::where("id_usuario", $data->id_usuario)
							  ->where("password", md5($data->password))
							  ->first();
			
			if(!isset($data->id_usuario_para_cambiar)){
				if($usuario){
					$usuario->password = md5($data->password_nueva);
					if($usuario->update()){
						$mensaje = "Contraseña actualizada.";
						$error = false;
					}
				}else{
					$mensaje = "La contraseña actual es incorrecta.";
				}
			}else{
				if($usuario){
					$usuario_por_cambiar = Usuario::find($data->id_usuario_para_cambiar);
					$usuario_por_cambiar->password = md5($data->password_nueva);
					if($usuario_por_cambiar->update()){
						$mensaje = "Contraseña actualizada.";
						$error = false;
					}
				}else{
					$mensaje = "La contraseña actual es incorrecta.";
				}
			}
		}else{
			$mensaje = "Hubo un error.";
		}

		return response()->json([
			"mensaje" => $mensaje,
			"error" => $error
		]);
	}
    
}
