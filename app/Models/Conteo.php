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

    public function detalles(){
        return $this->hasMany(ConteoDetalle::class, 'id_conteo');
    }

    public function tiene_seguimientos()
    {
        $respuesta = false;
        foreach ($this->detalles as $detalle) {
            if (count($detalle->seguimientos) > 0) $respuesta = true;
        }
        return $respuesta;
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

    public function estado_actual()
    {   
        $fecha_actual = date('Y-m-d H:i:s');
        $estado = "No creado";

        if ($fecha_actual >= $this->fecha_inicio and $fecha_actual <= $this->fecha_fin) $estado = "Iniciado";
        
        if ($this->tiene_seguimientos()) $estado = "En progreso";
        
        if ($fecha_actual < $this->fecha_inicio) $estado = "Sin Iniciar";
        
        if ($fecha_actual > $this->fecha_fin) $estado = "Finalizado";

        return $estado;
    }

    public function texto_conteo_actual()
    {
        $fecha_actual = date('Y-m-d H:i:s');
        $estado = "No iniciado";
        if ($fecha_actual >= $this->fecha_inicio){
            if ($this->conteo_activo == 1) $estado = "Primer Conteo";
            if ($this->conteo_activo == 2) $estado = "Segundo Conteo";
            if ($this->conteo_activo == 3) $estado = "Tercer Conteo";
        }
        return $estado;
    }

    public function progreso($conteo)
    {
        $id_auditoria = $this->id_auditoria;
        $sql = "SELECT sa.id_producto, sa.id_fila_estante 
                FROM seguimiento_auditoria sa 
                LEFT JOIN auditoria_detalle ad USING(id_auditoria_detalle)
                WHERE ad.id_auditoria = $id_auditoria
                AND sa.estado = 1
                GROUP BY 1, 2";
        $total_a_contar = count(DB::select($sql));

        $sql = "SELECT sc.id_producto, sc.id_fila_estante 
                FROM seguimiento_conteo sc 
                LEFT JOIN conteo_detalle cd USING(id_conteo_detalle)
                LEFT JOIN auditoria_detalle ad USING(id_auditoria_detalle)
                WHERE ad.id_auditoria = $id_auditoria
                AND sc.estado = 1
                AND cd.conteo = $conteo
                GROUP BY 1, 2";
        $total_contado = count(DB::select($sql)); 
        
        $porcentaje = 0;
        if ($total_a_contar > 0) {
           $porcentaje = ($total_contado / $total_a_contar) * 100;
        }       
        return $porcentaje;
    }
}
