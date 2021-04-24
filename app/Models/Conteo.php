<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Conteo extends Model
{
    protected $table = 'conteo';
    protected $primaryKey = 'id_conteo';

    public function auditoria(){
      	return $this->belongsTo(Auditoria::class, 'id_auditoria');
    }

    public function ActualizarConteoActual()
    {
        $detalles_conteo_1 = ConteoDetalle::all()
                                        ->where('id_conteo', $this->id_conteo)
                                        ->where('estado', 1)
                                        ->where('conteo', 1);
        $detalles_conteo_2 = ConteoDetalle::all()
                                        ->where('id_conteo', $this->id_conteo)
                                        ->where('estado', 1)
                                        ->where('conteo', 2);

        $detalles_finalizados_conteo_1 = ConteoDetalle::all()
                                        ->where('id_conteo', $this->id_conteo)
                                        ->where('estado', 1)
                                        ->where('conteo', 1)
                                        ->where('finalizo', 1);
        $detalles_finalizados_conteo_2 = ConteoDetalle::all()
                                        ->where('id_conteo', $this->id_conteo)
                                        ->where('estado', 1)
                                        ->where('conteo', 2)
                                        ->where('finalizo', 1);
        $this->conteo_activo = 1;
        if (count($detalles_finalizados_conteo_1) >= count($detalles_conteo_1)) $this->conteo_activo = 2;
        if (count($detalles_finalizados_conteo_2) >= count($detalles_conteo_2)) $this->conteo_activo = 3;
        $this->save();
    }

    public function ActualizarConteoActualOLD()
    {
        $seguimientos_auditoria = DB::select("SELECT *
                                              FROM seguimiento_auditoria sa
                                              LEFT JOIN auditoria_detalle ad USING(id_auditoria_detalle)
                                              WHERE sa.estado = 1
                                              AND ad.id_auditoria = ".$this->id_auditoria);

        $this->conteo_activo = 1;

        $conteos_1 = DB::select("SELECT *
                                 FROM seguimiento_conteo sc
                                 LEFT JOIN conteo_detalle cd USING(id_conteo_detalle)
                                 LEFT JOIN auditoria_detalle ad USING(id_auditoria_detalle)
                                 WHERE sc.estado = 1
                                 AND cd.conteo = 1
                                 AND ad.id_auditoria = ".$this->id_auditoria);
        $conteos_2 = DB::select("SELECT *
                                 FROM seguimiento_conteo sc
                                 LEFT JOIN conteo_detalle cd USING(id_conteo_detalle)
                                 LEFT JOIN auditoria_detalle ad USING(id_auditoria_detalle)
                                 WHERE sc.estado = 1
                                 AND cd.conteo = 2
                                 AND ad.id_auditoria = ".$this->id_auditoria);

        if(count($conteos_1) >= count($seguimientos_auditoria)) $this->conteo_activo = 2;
        if(count($conteos_2) >= count($seguimientos_auditoria)) $this->conteo_activo = 3;
        $this->save();
    }
}
