<?php

namespace App\Exports;

use App\Models\Inventario;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class InformeGeneralInventario implements FromView
{
	protected $id_inventario;
    
    public function __construct($id_inventario)
    {
        $this->id_inventario = $id_inventario;
    }

    public function view(): View
    {
    	$inventario = Inventario::find($this->id_inventario);
    	$sql = "SELECT p.id_producto, 
					   p.nombre as producto, 
					   p.codigo, 
					   p.marca, 
					   p.presentacion, 
					   fe.id_fila_estante, 
					   fe.nombre as fila, 
					   e.id_estante, 
					   e.nombre as estante, 
					   l.nombre as locacion, 
					   sc.lote, 
					   sc.fecha_vencimiento,
					   (SELECT SUM(sc1.cantidad) 
					   		   FROM seguimiento_conteo sc1
					   		   LEFT JOIN conteo_detalle cd1 USING(id_conteo_detalle) 
							   LEFT JOIN auditoria_detalle ad1 USING(id_auditoria_detalle) 
							   LEFT JOIN auditoria a1 USING(id_auditoria)
					   		   WHERE cd1.conteo = 1
					   		   AND sc1.valido = 1
					   		   AND sc1.estado = 1
					   		   AND sc1.id_producto = p.id_producto
					   		   AND sc1.id_fila_estante = fe.id_fila_estante
					   		   AND sc1.lote = sc.lote
					   		   AND sc1.fecha_vencimiento = sc.fecha_vencimiento
					   		   AND a1.id_inventario = a.id_inventario
					   		   ) as total_conteo_1,
					   (SELECT SUM(sc1.cantidad) 
					   		   FROM seguimiento_conteo sc1
					   		   LEFT JOIN conteo_detalle cd1 USING(id_conteo_detalle) 
							   LEFT JOIN auditoria_detalle ad1 USING(id_auditoria_detalle) 
							   LEFT JOIN auditoria a1 USING(id_auditoria)
					   		   WHERE cd1.conteo = 2
					   		   AND sc1.valido = 1
					   		   AND sc1.estado = 1
					   		   AND sc1.id_producto = p.id_producto
					   		   AND sc1.id_fila_estante = fe.id_fila_estante
					   		   AND sc1.lote = sc.lote
					   		   AND sc1.fecha_vencimiento = sc.fecha_vencimiento
					   		   AND a1.id_inventario = a.id_inventario
					   		   ) as total_conteo_2,
					   (SELECT SUM(sc1.cantidad) 
					   		   FROM seguimiento_conteo sc1
					   		   LEFT JOIN conteo_detalle cd1 USING(id_conteo_detalle) 
							   LEFT JOIN auditoria_detalle ad1 USING(id_auditoria_detalle) 
							   LEFT JOIN auditoria a1 USING(id_auditoria)
					   		   WHERE cd1.conteo = 3
					   		   AND sc1.valido = 1
					   		   AND sc1.estado = 1
					   		   AND sc1.id_producto = p.id_producto
					   		   AND sc1.id_fila_estante = fe.id_fila_estante
					   		   AND sc1.lote = sc.lote
					   		   AND sc1.fecha_vencimiento = sc.fecha_vencimiento
					   		   AND a1.id_inventario = a.id_inventario
					   		   ) as total_conteo_3
					   FROM seguimiento_conteo sc 
					   LEFT JOIN producto p 
					   ON sc.id_producto = p.id_producto 
					   LEFT JOIN fila_estante fe ON fe.id_fila_estante = sc.id_fila_estante 
					   LEFT JOIN estante e ON e.id_estante = fe.id_estante 
					   LEFT JOIN locacion l ON e.id_locacion = l.id_locacion 
					   LEFT JOIN conteo_detalle cd USING(id_conteo_detalle) 
					   LEFT JOIN auditoria_detalle ad USING(id_auditoria_detalle) 
					   LEFT JOIN auditoria a USING(id_auditoria) 
					   WHERE a.id_inventario = $inventario->id_inventario
					   AND sc.valido = 1
					   AND sc.estado = 1
					   AND cd.estado = 1
					   AND ad.estado = 1
					   AND a.estado  = 1
					   GROUP BY 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 , 11, 12, 13, 14, 15";

		$seguimientos = DB::select($sql);			  	 
        return view('excel.informe_inventario_general', [
            'inventario' => $inventario,
            'seguimientos' => $seguimientos
        ]);
    }
}