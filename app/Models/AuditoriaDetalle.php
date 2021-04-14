<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaDetalle extends Model
{
    protected $table = 'auditoria_detalle';
    protected $primaryKey = 'id_auditoria_detalle';

    public function usuario(){
      	return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function auditoria(){
      	return $this->belongsTo(Auditoria::class, 'id_auditoria');
    }
}
