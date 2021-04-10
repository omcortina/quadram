<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';

    public function inventario(){
      	return $this->belongsTo(Inventario::class, 'id_inventario');
    }

    public function usuario(){
      	return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function estado(){
      	return $this->belongsTo(Dominio::class, 'id_dominio_estado', 'id_dominio');
    }

    public function conteos(){
        return $this->hasMany(Conteo::class, 'id_auditoria');
    }

    public function conteo()
    {
       if(count($this->conteos) > 0) return $this->conteos[0];
       return null;
    }

}
