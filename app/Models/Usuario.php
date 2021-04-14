<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
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
        return $this->url_imagen != null ? asset('images/users/'.$this->url_imagen) : asset('images/user.png');
    }

    public function nombre_completo()
    {
        return $this->nombres." ".$this->apellidos;
    }
}
