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
        if (count($detalles_finalizados_conteo_1) >= count($detalles_conteo_1) and count($detalles_conteo_1) > 0) $this->conteo_activo = 2;
        if (count($detalles_finalizados_conteo_2) >= count($detalles_conteo_2) and count($detalles_conteo_2) > 0) $this->conteo_activo = 3;
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
        if ($conteo == 3) {
            $sql = "SELECT p.id_producto, 
                       fe.id_fila_estante, 
                       e.id_estante, 
                       sc.lote, 
                       sc.fecha_vencimiento
                       FROM seguimiento_conteo sc 
                       LEFT JOIN producto p ON sc.id_producto = p.id_producto 
                       LEFT JOIN fila_estante fe ON fe.id_fila_estante = sc.id_fila_estante 
                       LEFT JOIN estante e ON e.id_estante = fe.id_estante 
                       LEFT JOIN conteo_detalle cd USING(id_conteo_detalle) 
                       LEFT JOIN auditoria_detalle ad USING(id_auditoria_detalle) 
                       LEFT JOIN auditoria a USING(id_auditoria) 
                       WHERE a.id_auditoria = $id_auditoria
                       AND sc.valido = 1
                       AND sc.estado = 1
                       AND cd.estado = 1
                       AND ad.estado = 1
                       AND a.estado  = 1
                       GROUP BY 1, 2, 3, 4, 5";

            $seguimientos = DB::select($sql);   
            $total_a_contar = 0;
            $total_contado = 0;
            foreach ($seguimientos as $seguimiento) {
                $seguimiento = (object) $seguimiento;
                $total_conteo_1 = DB::select("SELECT SUM(sc1.cantidad) as total
                               FROM seguimiento_conteo sc1
                               LEFT JOIN conteo_detalle cd1 USING(id_conteo_detalle) 
                               LEFT JOIN auditoria_detalle ad1 USING(id_auditoria_detalle) 
                               LEFT JOIN auditoria a1 USING(id_auditoria)
                               WHERE cd1.conteo = 1
                               AND sc1.valido = 1
                               AND sc1.estado = 1
                               AND sc1.id_producto = $seguimiento->id_producto
                               AND sc1.id_fila_estante = $seguimiento->id_fila_estante
                               AND sc1.lote = '$seguimiento->lote'
                               AND sc1.fecha_vencimiento = '$seguimiento->fecha_vencimiento'
                               AND a1.id_auditoria = $id_auditoria")[0]->total;
                $total_conteo_2 = DB::select("SELECT SUM(sc1.cantidad) as total
                               FROM seguimiento_conteo sc1
                               LEFT JOIN conteo_detalle cd1 USING(id_conteo_detalle) 
                               LEFT JOIN auditoria_detalle ad1 USING(id_auditoria_detalle) 
                               LEFT JOIN auditoria a1 USING(id_auditoria)
                               WHERE cd1.conteo = 2
                               AND sc1.valido = 1
                               AND sc1.estado = 1
                               AND sc1.id_producto = $seguimiento->id_producto
                               AND sc1.id_fila_estante = $seguimiento->id_fila_estante
                               AND sc1.lote = '$seguimiento->lote'
                               AND sc1.fecha_vencimiento = '$seguimiento->fecha_vencimiento'
                               AND a1.id_auditoria = $id_auditoria")[0]->total;
                $total_conteo_3 = DB::select("SELECT SUM(sc1.cantidad) as total
                               FROM seguimiento_conteo sc1
                               LEFT JOIN conteo_detalle cd1 USING(id_conteo_detalle) 
                               LEFT JOIN auditoria_detalle ad1 USING(id_auditoria_detalle) 
                               LEFT JOIN auditoria a1 USING(id_auditoria)
                               WHERE cd1.conteo = 3
                               AND sc1.valido = 1
                               AND sc1.estado = 1
                               AND sc1.id_producto = $seguimiento->id_producto
                               AND sc1.id_fila_estante = $seguimiento->id_fila_estante
                               AND sc1.lote = '$seguimiento->lote'
                               AND sc1.fecha_vencimiento = '$seguimiento->fecha_vencimiento'
                               AND a1.id_auditoria = $id_auditoria")[0]->total;
                if ($total_conteo_1 != $total_conteo_2) $total_a_contar++;
                if (is_numeric($total_conteo_3)) $total_contado++;
            }

        }else{
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
        }

        $porcentaje = 0;
        if ($total_a_contar > 0) {
           $porcentaje = ($total_contado / $total_a_contar) * 100;
        }
        return round($porcentaje, 1);
    }
}
