<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    public $servidor;
    public function __construct() {
        $this->servidor = config('global.servidor');
    }

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
    	'id_dominio_tipo_documento',
    	'documento',
    	'fecha_nacimiento',
    	'nombres',
    	'apellidos',
    	'telefono',
    	'nombre_usuario',
    	'id_dominio_tipo_usuario',
    	'direccion',
		'url_imagen'
    ];
    public function tipo(){
		return $this->belongsTo(Dominio::class, 'id_dominio_tipo_usuario', 'id_dominio');
	}

	public function tipo_documento(){
		return $this->belongsTo(Dominio::class, 'id_dominio_tipo_documento', 'id_dominio');
	}

	public function obtenerImagen(){
        $ruta_imagen = "";
        if($this->id_dominio_tipo_usuario == 2){
            $ruta_imagen = asset('design/assets/img/theme/team-4.jpg');
        }

        if($this->url_imagen != null and $this->url_imagen != ""){
            $ruta_imagen = asset('images/users/'.$this->url_imagen);
        }
        return $ruta_imagen;
    }

    public function nombre_completo()
    {
        return $this->nombres." ".$this->apellidos;
    }
}
