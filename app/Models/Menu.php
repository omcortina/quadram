<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    public static function loadMenu()
    {
    	$server = config('global.servidor');
    	$id_dominio_tipo_usuario = session('tipo_usuario');
    	$menus_padres = Menu::where('id_padre', null)
    							   ->orderBy('orden', 'asc')
    							   ->get();

    	$menu = '<ul class="nav navbar-nav">';
    	foreach ($menus_padres as $menu_padre) {

    		//primero miramos si tiene permiso para ese menu padre
    		$menu_perfil = MenuPerfil::where('id_dominio_tipo_usuario', $id_dominio_tipo_usuario)
    								 ->where('id_menu', $menu_padre->id_menu)
    								 ->where('estado', 1)
    								 ->first();
    		if($menu_perfil){
	    		//primero miramos si tiene hijos el menu padre
	    		$menu_hijos = Menu::where('id_padre',$menu_padre->id_menu)
	    							->orderBy('orden', 'asc')
    							    ->get();
	    		if(count($menu_hijos) > 0){
	    			$key_menu = 'navbar-'.$menu_padre->id_menu;
	    			$menu .= '<li class="nav-item">
	    						<a href="#'.$key_menu.'" class="nav-link collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="'.$key_menu.'"> 
	    							<i class="text-primary '.$menu_padre->icono.'"></i>
	    							<span class="nav-link-text">'.$menu_padre->nombre.'</span></a>

	    							<div class="collapse" id="'.$key_menu.'" style="">
						                <ul class="nav nav-sm flex-column">';
                	foreach ($menu_hijos as $menu_hijo) {
                		//ahora se pregunta si tiene menuperfil el hijo actual
                		$menu_perfil_hijo = MenuPerfil::where('id_dominio_tipo_usuario', $id_dominio_tipo_usuario)
			    								 ->where('id_menu', $menu_hijo->id_menu)
			    								 ->where('estado', 1)
			    								 ->first();
			    		if($menu_perfil_hijo){
			    			$menu .= '<li class="nav-item">
					                    <a href="'.$server.'/'.$menu_hijo->ruta.'" class="nav-link">
					                      <span class="sidenav-normal"> '.$menu_hijo->nombre.' </span>
					                    </a>
					                  </li>';
			    		} 
       				}
	               $menu .=   '</ul>
	               			</div>';
	    		}else{
	    			//aca es porque no tiene hijos el menu padre
	    			$menu .= '
	    			<li class="nav-item">
                        <a class="nav-link" href="'.$server.'/'.$menu_padre->ruta.'">
                        <i class="'.$menu_padre->icono.' text-primary"></i>
                        <span class="nav-link-text">'.$menu_padre->nombre.'</span>
                        </a>
                    </li>';
	    		}
    		}
    	}
    	echo $menu;
    }
}
