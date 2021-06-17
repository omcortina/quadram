<!DOCTYPE html>
<html>
<head>
	<title>Informe de diferencias en conteo</title>
	<style type="text/css">
		.table{
			width: 100%;
			font-family : "Calibri, sans-serif";
			font-size: 9px;
			border: 1px solid #000;
		}
		.table td, th{
			padding: 10px;
		}
		.table th{
			background-color: #5e72e4;
			color: white;
		}
		.table-head{
			width: 100%;
			font-family : "Calibri, sans-serif";
			font-size: 12px;
			border: 1px solid #000;
		}
		.table-head td, th{
			padding: 7px;
		}
	</style>
</head>
<body>
	<table cellpadding="0" cellspacing="0" class="table-head" border="1">
		<tr>
			<td rowspan="3" width="65%"><center><h2>INFORME DE DIFERENCIAS EN CONTEO</h2></center></td>
			<td><b>Supervisor: </b>{{ $conteo->auditoria->usuario->nombre_completo() }}</td>
		</tr>
		<tr><td><b>Almacen: </b>{{ $conteo->auditoria->inventario->almacen->nombre }}</td></tr>
		<tr><td><b>Auditoria: #</b>{{ $conteo->id_auditoria }}</td></tr>
		<tr><td colspan="2"><center>Desde <b>{{ date('d/m/Y H:i', strtotime($conteo->fecha_inicio)) }}</b> hasta <b>{{ date('d/m/Y H:i', strtotime($conteo->fecha_fin)) }}</b></center></td></tr>
	</table>
	<br>
	<table cellpadding="0" cellspacing="0" class="table" border="1">
		<tr>
			<th rowspan="2"><b><center>Estante</center></b></th>
			<th rowspan="2"><b><center>Fila</center></b></th>
			<th rowspan="2"><b><center>Producto</center></b></th>
			<th colspan="3"><b><center>Diferencias</center></b></th>
		</tr>
		<tr>
			<th><b><center>Cantidad</center></b></th>
			<th><b><center>Lote</center></b></th>
			<th><b><center>F. Vencimiento</center></b></th>
		</tr>
		@foreach ($seguimientos as $item)
			<tr>
				<td><center>{{ $item->auditoria_detalle->estante->nombre }}</center></td>
				<td><center>{{ $item->fila->nombre }}</center></td>
				<td><center>{{ $item->producto->codigo }} - {{ ucfirst(strtolower($item->producto->nombre)) }}</center></td>
				@php
					$cantidad_1 = 0;
					$cantidad_2 = 0;
					$lote_1 = "";
					$lote_2 = "";
					$vencimiento_1 = "";
					$vencimiento_2 = "";
					//BUSCAMOS LA CANTIDAD LOTES Y VENCIMIENTOS DEL PRIMER CONTEO
					$conteo_detalle = \App\Models\ConteoDetalle::where('estado', 1)
									   	->where('id_auditoria_detalle', $item->id_auditoria_detalle)
									   	->where('conteo', 1)
									   	->first();
					if($conteo_detalle){
						$seguimientos_conteo = \App\Models\SeguimientoConteo::all()
												->where('estado', 1)
											   	->where('id_conteo_detalle', $conteo_detalle->id_conteo_detalle)
											   	->where('id_fila_estante', $item->id_fila_estante)
											   	->where('id_producto', $item->id_producto);
						$cont = 0;
						foreach ($seguimientos_conteo as $seguimiento_conteo) {
							$cantidad_1 += $seguimiento_conteo->cantidad;
							$lote_1 .= $cont == 0 ? $seguimiento_conteo->lote : "|".$seguimiento_conteo->lote; 
							$vencimiento_1 .= $cont == 0 ? $seguimiento_conteo->fecha_vencimiento : "|".$seguimiento_conteo->fecha_vencimiento; 
							$cont++;
						}
					}
					//BUSCAMOS LA CANTIDAD LOTES Y VENCIMIENTOS DEL SEGUNDO CONTEO
					$conteo_detalle = \App\Models\ConteoDetalle::where('estado', 1)
									   	->where('id_auditoria_detalle', $item->id_auditoria_detalle)
									   	->where('conteo', 2)
									   	->first();
					if($conteo_detalle){
						$seguimientos_conteo = \App\Models\SeguimientoConteo::all()
												->where('estado', 1)
											   	->where('id_conteo_detalle', $conteo_detalle->id_conteo_detalle)
											   	->where('id_fila_estante', $item->id_fila_estante)
											   	->where('id_producto', $item->id_producto);
						$cont = 0;
						foreach ($seguimientos_conteo as $seguimiento_conteo) {
							$cantidad_2 += $seguimiento_conteo->cantidad;
							$lote_2 .= $cont == 0 ? $seguimiento_conteo->lote : "|".$seguimiento_conteo->lote; 
							$vencimiento_2 .= $cont == 0 ? $seguimiento_conteo->fecha_vencimiento : "|".$seguimiento_conteo->fecha_vencimiento; 
							$cont++;
						}
					}
				@endphp

				<td>
					<center>
						<b>
							@php
								$signo = $cantidad_2 > $cantidad_1 ? "+" : "-";
							@endphp
							{{ $cantidad_1 == $cantidad_2 ? 0 : $signo.abs($cantidad_2 - $cantidad_1)  }}
						</b>
					</center>
				</td>
				<td>
					<center>
						<b>
							{{ $lote_1 == $lote_2 ? "---" : "Se encontraron diferencias" }}
						</b>
					</center>
				</td>
				<td>
					<center>
						<b>
							{{ $vencimiento_1 == $vencimiento_2 ? "---" : "Se encontraron diferencias" }}
						</b>
					</center>
				</td>
			</tr>
		@endforeach
	</table>
</body>
</html>