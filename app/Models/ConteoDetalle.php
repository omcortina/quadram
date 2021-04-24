<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConteoDetalle extends Model
{
    protected $table = 'conteo_detalle';
    protected $primaryKey = 'id_conteo_detalle';

     public function usuario(){
      	return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function auditoria_detalle(){
      	return $this->belongsTo(AuditoriaDetalle::class, 'id_auditoria_detalle');
    }

    public function conteo(){
      	return $this->belongsTo(Conteo::class, 'id_conteo');
    }
}
