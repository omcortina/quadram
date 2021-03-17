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
    	'direccion'
    ];
    public function tipo()
	{
		return $this->belongsTo(Dominio::class, 'id_dominio_tipo_usuario', 'id_dominio');
	}

	public function tipo_documento()
	{
		return $this->belongsTo(Dominio::class, 'id_dominio_tipo_documento', 'id_dominio');
	}
}
