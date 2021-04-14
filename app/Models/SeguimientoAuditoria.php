<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoAuditoria extends Model
{
    protected $table = 'seguimiento_auditoria';
    protected $primaryKey = 'id_seguimiento_auditoria';

    public function auditoria_detalle(){
      	return $this->belongsTo(AuditoriaDetalle::class, 'id_auditoria_detalle');
    }
}
