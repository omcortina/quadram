<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoConteo extends Model
{
    protected $table = 'seguimiento_conteo';
    protected $primaryKey = 'id_seguimiento_conteo';

    public function conteo_detalle(){
      	return $this->belongsTo(ConteoDetalle::class, 'id_conteo_detalle');
    }

    public function producto(){
      	return $this->belongsTo(Producto::class, 'id_producto');
    }
}
